<?php
session_start(); // Oturum başlatılıyor
include("baglanti.php"); // Veritabanı bağlantısı dahil ediliyor

// Kullanıcının oturum kontrolü
if (!isset($_SESSION["id"])) {
    header("Location: girisformu.php");
    exit;
}

// Form POST edildiğinde şifre güncelleme işlemini başlat
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Formdan gelen yeni şifreyi güvenli hale getir
    $yeni_parola = mysqli_real_escape_string($baglanti, $_POST["yeni_parola"]);
    $id = $_SESSION["id"];

    // Şifreyi güncelle ve ilk_giris değerini sıfırla
    $sorgu = "UPDATE kullanicilar SET parola='$yeni_parola', ilk_giris=0 WHERE id='$id'";
    if (mysqli_query($baglanti, $sorgu)) {
        // Güncelleme başarılıysa mesaj göster ve ana sayfaya yönlendir
        echo "<center><br>Şifreniz başarıyla güncellendi. Ana sayfaya yönlendiriliyorsunuz...</center>";
        header("Refresh: 4; url=index.php");
        exit;
    } else {
        // Güncelleme başarısızsa hata mesajı göster
        echo "<center><br>Şifre güncellenirken bir hata oluştu. Lütfen tekrar deneyin.</center>";
    }
}
/*
Sık kullanılan PHP fonksiyonları ve anlamları:

session_start() : Oturum başlatır veya mevcut oturumu devam ettirir
isset() : Bir değişkenin tanımlı olup olmadığını kontrol eder
mysqli_real_escape_string() : SQL enjeksiyonunu önlemek için özel karakterleri kaçış karakteriyle işaretler
mysqli_query() : MySQL veritabanına sorgu gönderir
header() : HTTP başlığı gönderir (genellikle yönlendirme için kullanılır)
$_SESSION : Oturum değişkenlerini tutan süper global dizi
$_POST : Form verilerini tutan süper global dizi
$_SERVER : Sunucu ve çalışma ortamı bilgilerini tutan süper global dizi
*/
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Güncelle</title>
</head>
<body>
    <!-- Şifre güncelleme formu -->
    <form method="POST">
        <label for="yeni_parola">Yeni Şifre:</label>
        <input type="password" name="yeni_parola" id="yeni_parola" required>
        <button type="submit">Şifreyi Güncelle</button>
    </form>
</body>
</html>