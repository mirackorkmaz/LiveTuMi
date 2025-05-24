<?php
session_start(); // Oturum başlatılıyor
include("baglanti.php"); // Veritabanı bağlantısı dahil ediliyor

// Kullanıcı girişi ve etkinlik ID'si kontrolü
if (isset($_SESSION["id"]) && isset($_GET["etkinlik_id"])) {
    $kullanici_id = $_SESSION["id"]; // Oturumdaki kullanıcı ID'sini al
    $etkinlik_id = $_GET["etkinlik_id"]; // URL'den gelen etkinlik ID'sini al

    // Sepetteki etkinliği kontrol et
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

    // İşlem tamamlandıktan sonra ana sayfaya yönlendir
    header("Location: index.php");
    exit;
} else {
    // Oturum veya etkinlik ID'si yoksa giriş sayfasına yönlendir
    header("Location: girisformu.php");
    exit;
}

/*
Sık kullanılan PHP fonksiyonları ve anlamları:

session_start() : Oturum başlatır veya mevcut oturumu devam ettirir
isset() : Bir değişkenin tanımlı olup olmadığını kontrol eder
mysqli_query() : MySQL veritabanına sorgu gönderir
mysqli_fetch_assoc() : Sorgu sonucunu ilişkisel dizi olarak döndürür
mysqli_num_rows() : Sorgu sonucunda dönen satır sayısını verir
header() : HTTP başlığı gönderir (genellikle yönlendirme için kullanılır)
exit : Scriptin çalışmasını sonlandırır
$_SESSION : Oturum değişkenlerini tutan süper global dizi
$_GET : URL parametrelerini tutan süper global dizi
*/

?>

