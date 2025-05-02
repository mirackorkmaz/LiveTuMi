<?php
session_start();
if (isset($_SESSION["id"])) {
    include("baglanti.php");
    $id = $_SESSION["id"];

    // Eğer oturumdaki id "admin" ise yönetici sayfasına yönlendir
    if ($id === "admin") {
        echo "<center><br>Yönetici, yönetici sayfasinda kalmalidir.</center>";
        header("Refresh: 3; url=yonetici.php");
        exit;
    }

    $sorgu = mysqli_query($baglanti, "SELECT * FROM kullanicilar WHERE id='$id'");

    // Kullanıcı sorgusunun sonucunu kontrol et
    if ($sorgu && mysqli_num_rows($sorgu) > 0) {
        $kullanici = mysqli_fetch_assoc($sorgu);
        $ad = $kullanici["ad"];
        $soyad = $kullanici["soyad"];
        $mailadresi = $kullanici["mailadresi"];
        $dogumtarihi = $kullanici["dogumtarihi"];
        $status = $kullanici["status"];

        echo "<center><h1>Hoşgeldiniz, $ad $soyad!</h1></center>";
        echo "<center><a href='cikis.php' id='cikis-buton'>Çikiş Yap</a></center>";
    } else {
        // Kullanıcı bulunamadıysa oturumu sonlandır ve giriş sayfasına yönlendir
        echo "<center><br>Kullanici bulunamadi. Lütfen tekrar giriş yapin.</center>";
        session_destroy();
        header("Refresh: 3; url=girisformu.php");
        exit;
    }
} else {
    // Oturum açılmamışsa giriş sayfasına yönlendir
    header("Location: girisformu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #cikis-buton {
            margin: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        #cikis-buton:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
</body>
</html>