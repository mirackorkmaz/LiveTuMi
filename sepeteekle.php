<?php
session_start();
include("baglanti.php");

if (isset($_SESSION["id"]) && isset($_GET["etkinlik_id"])) {
    $kullanici_id = $_SESSION["id"];
    $etkinlik_id = $_GET["etkinlik_id"];

    // Sepette aynı etkinlik varsa adet artır
    $kontrol = mysqli_query($baglanti, "SELECT * FROM sepet WHERE kullanici_id='$kullanici_id' AND etkinlik_id='$etkinlik_id'");
    if (mysqli_num_rows($kontrol) > 0) {
        mysqli_query($baglanti, "UPDATE sepet SET adet = adet + 1 WHERE kullanici_id='$kullanici_id' AND etkinlik_id='$etkinlik_id'");
    } else {
        mysqli_query($baglanti, "INSERT INTO sepet (kullanici_id, etkinlik_id, adet) VALUES ('$kullanici_id', '$etkinlik_id', 1)");
    }

    header("Location: index.php");
    exit;
} else {
    header("Location: girisformu.php");
    exit;
}
?>