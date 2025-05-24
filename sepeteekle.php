<?php
session_start(); // Oturum başlatılıyor
include("baglanti.php"); // Veritabanı bağlantısı dahil ediliyor

// Kullanıcı girişi ve etkinlik ID'si kontrolü
if (isset($_SESSION["id"]) && isset($_GET["etkinlik_id"])) {
    $kullanici_id = $_SESSION["id"]; // Oturumdaki kullanıcı ID'sini al
    $etkinlik_id = $_GET["etkinlik_id"]; // URL'den gelen etkinlik ID'sini al

    // Sepette aynı etkinlik var mı kontrol et
    $kontrol = mysqli_query($baglanti, "SELECT * FROM sepet WHERE kullanici_id='$kullanici_id' AND etkinlik_id='$etkinlik_id'");
    if (mysqli_num_rows($kontrol) > 0) {
        // Etkinlik sepette varsa adetini bir artır
        mysqli_query($baglanti, "UPDATE sepet SET adet = adet + 1 WHERE kullanici_id='$kullanici_id' AND etkinlik_id='$etkinlik_id'");
    } else {
        // Etkinlik sepette yoksa yeni kayıt oluştur
        mysqli_query($baglanti, "INSERT INTO sepet (kullanici_id, etkinlik_id, adet) VALUES ('$kullanici_id', '$etkinlik_id', 1)");
    }

    // İşlem tamamlandıktan sonra ana sayfaya yönlendir
    header("Location: index.php");
    exit;
} else {
    // Oturum veya etkinlik ID'si yoksa giriş sayfasına yönlendir
    header("Location: girisformu.php");
    exit;
}
?>