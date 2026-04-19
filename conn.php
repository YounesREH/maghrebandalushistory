<?php
$username = "root";
$password ="";
try{
    $database= new PDO("mysql:host=localhost;dbname=maghreb_history;charset=utf8mb4",$username,$password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /*echo "تم الاتصال بنجاح";*/
}catch(PDOException $e){
    die("فشل في الاتصال: " . $e->getMessage());
}
?>