<?php 
include "conn.php";
include "header.php";

$sujet_id = $_GET['sujet_id'];

// معلومات الموضوع
$stmt = $database->prepare("SELECT * FROM sujet WHERE id = ?");
$stmt->execute([$sujet_id]);
$sujet = $stmt->fetch();

// ================= Pagination =================
$limit = 4; // عدد العناصر في كل صفحة

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

// عدد العناصر
$stmt = $database->prepare("SELECT COUNT(*) FROM items WHERE sujet_id = ?");
$stmt->execute([$sujet_id]);
$total_items = $stmt->fetchColumn();

$total_pages = ceil($total_items / $limit);

// جلب العناصر مع LIMIT
$stmt = $database->prepare("SELECT * FROM items WHERE sujet_id = ? LIMIT $limit OFFSET $offset");
$stmt->execute([$sujet_id]);
$items = $stmt->fetchAll();
?>

<h1 style="text-align:center; margin-top:100px;">
    <?php echo $sujet['nom']; ?>
</h1>

<div class="char-list">
    <?php foreach($items as $item): ?>
        <div class="char-item">
            <img src='<?php echo $item['image']; ?>' class="clickable-img">
            <div class="char-content">
                <h3><?php echo $item['title']; ?></h3>
                <p><?php echo $item['description']; ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<br>

<!-- ================= Pagination ================= -->
<div class="pagination">

    <?php if ($page > 1): ?>
        <a href="?sujet_id=<?php echo $sujet_id; ?>&page=<?php echo $page - 1; ?>">«</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?sujet_id=<?php echo $sujet_id; ?>&page=<?php echo $i; ?>"class="<?php echo ($i == $page) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?sujet_id=<?php echo $sujet_id; ?>&page=<?php echo $page + 1; ?>">»</a>
    <?php endif; ?>

</div>
<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImg">
</div>
<script>
console.log("SCRIPT WORKING");

document.addEventListener("DOMContentLoaded", function () {

    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");
    const images = document.querySelectorAll(".clickable-img");
    const closeBtn = document.querySelector(".close");

    console.log("Images found:", images.length);

    images.forEach(img => {
        img.addEventListener("click", function () {
            console.log("IMAGE CLICKED");
            modal.style.display = "block";
            modalImg.src = this.src;
        });
    });

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

});
</script>
<?php include "footer.php"; ?>
<script src="script.js"></script>