<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$userMessage = trim($data['message'] ?? "");

if(empty($userMessage)){
    echo json_encode(["reply" => "أدخل سؤال"]);
    exit;
}

// ================== 1. LOCAL RESPONSES ==================
$faq = [
    "من هو طارق بن زياد" => "طارق بن زياد هو قائد عسكري مسلم من أصل بربري، قاد الفتح الإسلامي لشبه الجزيرة الإيبيرية (الأندلس) سنة 711م. عبر البحر من المغرب إلى إسبانيا بجيش صغير، وانتصر في معركة وادي لكة ضد القوط، مما أدى إلى بداية الحكم الإسلامي في الأندلس. يُعد من أبرز القادة في تاريخ الإسلام.",

    "ما هي الأندلس" => "الأندلس هي الاسم الذي أطلقه المسلمون على المناطق التي حكموها في شبه الجزيرة الإيبيرية (إسبانيا والبرتغال حاليًا) ابتداءً من سنة 711م. استمرت الحضارة الأندلسية لقرون، وشهدت ازدهارًا كبيرًا في العلوم والفنون والعمارة، وكانت مركزًا للتعايش بين المسلمين والمسيحيين واليهود."
];

if(isset($faq[$userMessage])){
    echo json_encode(["reply" => $faq[$userMessage]]);
    exit;
}

// ================== 2. CACHE ==================
$cacheFile = "cache/" . md5($userMessage) . ".txt";

// إذا السؤال موجود في الكاش
if(file_exists($cacheFile)){
    echo json_encode([
        "reply" => file_get_contents($cacheFile)
    ]);
    exit;
}

// ================== 3. LIMIT ==================
session_start();

if(!isset($_SESSION['count'])){
    $_SESSION['count'] = 0;
}

$_SESSION['count']++;

if($_SESSION['count'] > 6){
    echo json_encode([
        "reply" => "لقد تجاوزت الحد اليومي، حاول لاحقًا"
    ]);
    exit;
}

// ================== 4. API ==================
$config = require "config.php";

$apiKey = $config["HF_API_KEY"];
$url = "https://router.huggingface.co/v1/chat/completions";

$systemPrompt = "أنت مساعد متخصص فقط في تاريخ المغرب الإسلامي والأندلس. و اسمك الوجيه

- أعطِ إجابة ليست طويلة ومفصلة ومنظمة.
- قسم الإجابة إلى فقرات بعناوين واضحة مثل: التعريف، الأحداث، الشخصيات، النتائج.
-  اختصر المعلومات.
- لا تنهي الإجابة بسرعة، وقدم شرحًا تاريخيًا عميقًا.
- إذا كان السؤال خارج المجال قل فقط: هذا السؤال خارج اختصاصي.";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "moonshotai/Kimi-K2-Instruct-0905",
    "messages" => [
        ["role" => "system", "content" => $systemPrompt],
        ["role" => "user", "content" => $userMessage]
    ],
    "max_tokens" => 800
]));

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo json_encode(["reply" => curl_error($ch)]);
    exit;
}

curl_close($ch);

$result = json_decode($response, true);

$reply = $result['choices'][0]['message']['content'] ?? "خطأ في الرد";

// ================== 5. SAVE CACHE ==================
file_put_contents($cacheFile, $reply);

// ================== 6. OUTPUT ==================
echo json_encode([
    "reply" => trim($reply)
]);