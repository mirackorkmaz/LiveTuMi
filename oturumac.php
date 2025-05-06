<?php
include("baglanti.php");

$mailadresi = mysqli_real_escape_string($baglanti, $_POST["mailadresi"]);
$parola = $_POST["parola"];

// Eğer kullanıcı adı ve şifre "admin" ise, doğrudan yönlendir
if ($mailadresi === "admin@admin.com" && $parola === "admin") {
    session_start();
    $_SESSION["id"] = "admin"; // Yönetici için özel bir oturum değeri
    echo "<center><br>Yönetici girişi başarılı! Yönlendiriliyorsunuz...</center>";
    header("Refresh: 4; url=yonetici.php");
    exit;
}

$sorgu = mysqli_query($baglanti, "SELECT id, status, ilk_giris FROM kullanicilar WHERE mailadresi='$mailadresi' AND parola='$parola'");
if (mysqli_num_rows($sorgu) == 0) {
    echo "<center><br>Giriş başarısız! Lütfen bilgilerinizi kontrol edin.</center>";
    header("Refresh: 4; url=girisformu.php");
    exit;
} else {
    $sorgusonucu = mysqli_fetch_assoc($sorgu);
    $id = $sorgusonucu["id"];
    $status = $sorgusonucu["status"];
    $ilk_giris = $sorgusonucu["ilk_giris"];
    
    session_start();
    $_SESSION["id"] = $id;

    if ($ilk_giris == 1) {
        // Kullanıcı ilk kez giriş yapıyorsa şifre güncelleme sayfasına yönlendir
        echo "<center><br>İlk girişinizde şifrenizi güncellemeniz gerekiyor. Yönlendiriliyorsunuz...</center>";
        header("Refresh: 4; url=sifreguncelle.php");
        exit;
    }

    if ($status === "onaylandi") {
        echo "<center><br>Giriş başarılı! Yönlendiriliyorsunuz...</center>";
        header("Refresh: 4; url=index.php");
        exit;
    } elseif ($status === "reddedildi") {
        echo "<center><br>Kaydınız reddedildi. Sisteme giriş yapamazsınız.</center>";
        header("Refresh: 4; url=kayitformu.php");
        exit;
    } elseif ($status === "beklemede") {
        echo "<center><br>Hala yönetici onay aşamasındasınız, en kısa sürede kaydınız değerlendirilecektir.</center>";
        header("Refresh: 4; url=girisformu.php");
        exit;
    }
}
?>