<?php


include "conn.php";
include "header.php";

$q = trim($_GET['q'] ?? "");

if($q === ""){
    echo "<h2 style='text-align:center;margin-top:120px'>اكتب كلمة للبحث</h2>";
    exit;
}

try {

    $stmt = $database->prepare("SELECT * FROM items
        WHERE title LIKE :q
        OR description LIKE :q
        LIMIT 5
    ");

    $stmt->execute([
        ":q" => "%$q%"
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(Exception $e){
    die("SQL Error: " . $e->getMessage());
}
?>

<div class="container" style="margin-top:120px">

    <h2>نتائج البحث عن: <?= htmlspecialchars($q) ?></h2>

    <?php if(!empty($results)): ?>

        <div class="char-list">
            <?php foreach($results as $item): ?>
                <div class="char-item">
                    <img src='<?php echo htmlspecialchars($item['image']); ?>' class="clickable-img" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <div class="char-content">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <p style="text-align:center;">لا توجد نتائج لـ "<?= htmlspecialchars($q) ?>"</p>
    <?php endif; ?>

</div>

<?php include "footer.php"; ?>
<script src="script.js"></script>