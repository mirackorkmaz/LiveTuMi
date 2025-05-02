<?php
include("baglanti.php");

$mailadresi = mysqli_real_escape_string($baglanti, $_POST["mailadresi"]);
$parola = $_POST["parola"];

// Eğer kullanıcı adı ve şifre "admin" ise, doğrudan yönlendir
if ($mailadresi === "admin@admin.com" && $parola === "admin") {
    session_start();
    $_SESSION["id"] = "admin"; // Yönetici için özel bir oturum değeri
    echo "<center><br>Yönetici girişi başarili! Yönlendiriliyorsunuz...</center>";
    header("Refresh: 4; url=yonetici.php");
    exit;
}

$sorgu = mysqli_query($baglanti, "SELECT id, status FROM kullanicilar WHERE mailadresi='$mailadresi' AND parola='$parola'");
if (mysqli_num_rows($sorgu) == 0) {
    echo "<center><br>Giriş başarisiz! Lütfen bilgilerinizi kontrol edin.</center>";
    header("Refresh: 4; url=girisformu.php");
    exit;
} else {
    $sorgusonucu = mysqli_fetch_assoc($sorgu);
    $id = $sorgusonucu["id"];
    $status = $sorgusonucu["status"];
    
    session_start();
    $_SESSION["id"] = $id;

    if ($status === "onaylandi") {
        echo "<center><br>Giriş başarili! Yönlendiriliyorsunuz...</center>";
        header("Refresh: 4; url=index.php");
        exit;
    } elseif ($status === "reddedildi") {
        echo "<center><br>Kaydiniz reddedildi. Sisteme giriş yapamazsiniz.</center>";
        header("Refresh: 4; url=kayitformu.php");
        exit;
    } elseif ($status === "beklemede") {
        echo "<center><br>Hala yönetici onay aşamasindasiniz, en kisa sürede kaydiniz değerlendirilecektir.</center>";
        header("Refresh: 4; url=girisformu.php");
        exit;
    }
}
?>