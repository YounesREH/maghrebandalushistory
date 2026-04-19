<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo.png" alt="logo">
        </div>
        <ul class="nav-links">
            <li><a href="main.php">الرئيسية</a></li>
            <li><a href="us.php">من نحن</a></li>
            <li><a href="chat_page.php">تكلم مع الوجيه</a></li>
        </ul>
        <div class="search-box">
    <form action="search.php" method="GET">
        <input type="text" name="q" placeholder="ابحث...">
        <button type="submit">بحث</button>
    </form>

    <div class="search-toggle">
        <i class="fas fa-search"></i>
    </div>
</div>
        <div class="menu-toggle">&#9776;</div>
    </nav>
