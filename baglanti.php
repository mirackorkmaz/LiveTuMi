<?php
//kdsmfdsöşfösğ
$vt_sunucu = "localhost";
$vt_kullanici = "root";
$vt_sifre = "";
$vt_adi = "livetumi";

$baglanti = mysqli_connect($vt_sunucu, $vt_kullanici, $vt_sifre, $vt_adi);

if (!$baglanti) {
    die("Vertitabani baglanti islemi basarisiz" . mysqli_connect_error());
} else {
    echo "Baglanti basarili.";
}
