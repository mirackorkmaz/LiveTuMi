<?php
session_start();
include("baglanti.php");

if (isset($_SESSION["id"])) {
    $kullanici_id = $_SESSION["id"];

    // Sepetteki ürünleri al ve stokları güncelle
    $sepet_sorgu = mysqli_query($baglanti, "SELECT etkinlik_id, adet FROM sepet WHERE kullanici_id='$kullanici_id'");
    while ($sepet = mysqli_fetch_assoc($sepet_sorgu)) {
        $etkinlik_id = $sepet["etkinlik_id"];
        $adet = $sepet["adet"];
        mysqli_query($baglanti, "UPDATE etkinlikler SET stok = stok - $adet WHERE id='$etkinlik_id'");
    }

    // Sepeti temizle
    mysqli_query($baglanti, "DELETE FROM sepet WHERE kullanici_id='$kullanici_id'");

    echo "<p>Satın alma işlemi başarılı! Stoklar güncellendi.</p>";
    header("Refresh: 3; url=index.php");
    exit;
} else {
    header("Location: girisformu.php");
    exit;
}
?>