<?php
session_start();
if (isset($_SESSION["id"])) {
    include("baglanti.php");
    $id = $_SESSION["id"];
    $sorgu = mysqli_query($baglanti, "SELECT * FROM kullanicilar WHERE id='$id'");
    $kullanici = mysqli_fetch_assoc($sorgu);
    $ad = $kullanici["ad"];
    $soyad = $kullanici["soyad"];
    $mailadresi = $kullanici["mailadresi"];
    $dogumtarihi = $kullanici["dogumtarihi"];
    $status = $kullanici["status"];

    echo "<center><h1>Hoşgeldiniz, $ad $soyad!</h1></center>";
    echo "<center>Oturumu kapatmak için <a href='cikis.php'>buraya</a> tiklayin.</center>";
} else {
    header("Location: girisformu.php");
    exit;
}
?>