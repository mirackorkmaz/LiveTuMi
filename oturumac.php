<?php
include("baglanti.php"); // Veritabanı bağlantı dosyası dahil ediliyor

// Form verilerini güvenli şekilde al
$mailadresi = mysqli_real_escape_string($baglanti, $_POST["mailadresi"]);
$parola = $_POST["parola"];

// Yönetici girişi kontrolü
if ($mailadresi === "admin@admin.com" && $parola === "admin") {
    session_start();
    $_SESSION["id"] = "admin"; // Yönetici için özel bir oturum değeri
    echo "<center><br>Yönetici girişi başarılı! Yönlendiriliyorsunuz...</center>";
    header("Refresh: 4; url=yonetici.php");
    exit;
}

// Kullanıcı bilgilerini veritabanından sorgula
$sorgu = mysqli_query($baglanti, "SELECT id, status, ilk_giris FROM kullanicilar WHERE mailadresi='$mailadresi' AND parola='$parola'");

// Kullanıcı bulunamadıysa hata ver
if (mysqli_num_rows($sorgu) == 0) {
    echo "<center><br>Giriş başarısız! Lütfen bilgilerinizi kontrol edin.</center>";
    header("Refresh: 4; url=girisformu.php");
    exit;
} else {
    // Kullanıcı bilgilerini al
    $sorgusonucu = mysqli_fetch_assoc($sorgu);
    $id = $sorgusonucu["id"];
    $status = $sorgusonucu["status"];
    $ilk_giris = $sorgusonucu["ilk_giris"];

    // Oturum başlat ve kullanıcı ID'sini kaydet
    session_start();
    $_SESSION["id"] = $id;

    // İlk giriş kontrolü
    if ($ilk_giris == 1) {
        // İlk giriş yapan kullanıcının hesap durumunu kontrol et
        if ($status !== "onaylandi") {
            echo "<center><br>Yönetici onayını beklemeniz gerekmektedir.</center>";
            header("Refresh: 4; url=girisformu.php");
            exit;
        }
        // İlk giriş yapan onaylı kullanıcıyı şifre değiştirme sayfasına yönlendir
        echo "<center><br>İlk girişinizde şifrenizi güncellemeniz gerekiyor. Yönlendiriliyorsunuz...</center>";
        header("Refresh: 4; url=sifreguncelle.php");
        exit;
    }

    // Kullanıcının hesap durumuna göre yönlendirme yap
    if ($status === "onaylandi") {
        echo "<center><br>Giriş başarılı! Yönlendiriliyorsunuz...</center>";
        header("Refresh: 4; url=index.php");
        exit;
    } elseif ($status === "reddedildi") {
        // Hesabı reddedilen kullanıcı için mesaj
        echo "<center><br>Kaydınız reddedildi. Sisteme giriş yapamazsınız.</center>";
        header("Refresh: 4; url=kayitformu.php");
        exit;
    } elseif ($status === "beklemede") {
        // Hesabı onay bekleyen kullanıcı için mesaj
        echo "<center><br>Hala yönetici onay aşamasındasınız, en kısa sürede kaydınız değerlendirilecektir.</center>";
        header("Refresh: 4; url=girisformu.php");
        exit;
    }
}
