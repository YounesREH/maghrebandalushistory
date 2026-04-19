<?php 
include "conn.php"; 
include "header.php"; 

// ========================
// دالة تحويل التاريخ
// ========================
function formatDateArabic($date) {
    list($year, $month, $day) = explode("-", $date);

    $months = [
        "01" => "يناير",
        "02" => "فبراير",
        "03" => "مارس",
        "04" => "أبريل",
        "05" => "مايو",
        "06" => "يونيو",
        "07" => "يوليو",
        "08" => "أغسطس",
        "09" => "سبتمبر",
        "10" => "أكتوبر",
        "11" => "نوفمبر",
        "12" => "ديسمبر"
    ];

    return $day . " " . $months[$month] . " " . $year;
}

// ========================
// جلب المواضيع
// ========================
$stmt = $database->query("SELECT * FROM sujet");
$sujets = $stmt->fetchAll();

// ========================
// جلب أحداث هذا اليوم
// ========================
$today = date("m-d");

$stmt = $database->prepare("
    SELECT * FROM events 
    WHERE DATE_FORMAT(event_date, '%m-%d') = ?
");
$stmt->execute([$today]);
$events_today = $stmt->fetchAll();
?>

<div class="intro">
    <h1>أهلا و سهلا بكم في موقعنا</h1>
    <p>منصة مهتمة بتاريخ المغرب الإسلامي والأندلس</p>
</div>

<!-- ========================
     في مثل هذا اليوم
======================== -->
<?php if($events_today): ?>
<div class="today-events">
    <h2> في مثل هذا اليوم</h2>

    <?php foreach($events_today as $event): ?>
        <div class="event-card">
            <p>
                <strong><?php echo formatDateArabic($event['event_date']); ?></strong><br>
                <strong><?php echo $event['title']; ?></strong><br>
                <?php echo $event['description']; ?>
            </p>
        </div>
    <?php endforeach; ?>

</div>
<?php endif; ?>

<!-- ========================
     المواضيع
======================== -->
<section class="features">

<?php foreach($sujets as $data): ?>

    <article class="feature-card">
        <a href="items.php?sujet_id=<?php echo $data['id']; ?>">

            <img src="<?php echo $data['image']; ?>" alt="">

            <h3><?php echo $data['nom']; ?></h3>

        </a>
    </article>

<?php endforeach; ?>

</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>