<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["id"])) {
    header("Location: girisformu.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $yeni_parola = mysqli_real_escape_string($baglanti, $_POST["yeni_parola"]);
    $id = $_SESSION["id"];

    // Şifreyi güncelle ve ilk_giris değerini sıfırla
    $sorgu = "UPDATE kullanicilar SET parola='$yeni_parola', ilk_giris=0 WHERE id='$id'";
    if (mysqli_query($baglanti, $sorgu)) {
        echo "<center><br>Şifreniz başarıyla güncellendi. Ana sayfaya yönlendiriliyorsunuz...</center>";
        header("Refresh: 4; url=index.php");
        exit;
    } else {
        echo "<center><br>Şifre güncellenirken bir hata oluştu. Lütfen tekrar deneyin.</center>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Güncelle</title>
</head>
<body>
    <form method="POST">
        <label for="yeni_parola">Yeni Şifre:</label>
        <input type="password" name="yeni_parola" id="yeni_parola" required>
        <button type="submit">Şifreyi Güncelle</button>
    </form>
</body>
</html>