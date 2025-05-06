<?php
session_start();
include("baglanti.php");

if (isset($_SESSION["id"]) && isset($_GET["etkinlik_id"])) {
    $kullanici_id = $_SESSION["id"];
    $etkinlik_id = $_GET["etkinlik_id"];

    // Sepetten etkinliği çıkar
    $kontrol = mysqli_query($baglanti, "SELECT * FROM sepet WHERE kullanici_id='$kullanici_id' AND etkinlik_id='$etkinlik_id'");
    if (mysqli_num_rows($kontrol) > 0) {
        $sepet = mysqli_fetch_assoc($kontrol);
        if ($sepet['adet'] > 1) {
            // Eğer adet 1'den büyükse, sadece adeti azalt
            mysqli_query($baglanti, "UPDATE sepet SET adet = adet - 1 WHERE kullanici_id='$kullanici_id' AND etkinlik_id='$etkinlik_id'");
        } else {
            // Eğer adet 1 ise, ürünü tamamen sepetten çıkar
            mysqli_query($baglanti, "DELETE FROM sepet WHERE kullanici_id='$kullanici_id' AND etkinlik_id='$etkinlik_id'");
        }
    }

    header("Location: index.php");
    exit;
} else {
    header("Location: girisformu.php");
    exit;
}
?>