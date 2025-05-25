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
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon">
    <title>Şifre Güncelle | LiveTuMi</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="giris">
    <div class="preload-images">
        <img src="imgs/form_bg/bg_1.jpg" alt="bg_1">
        <img src="imgs/form_bg/bg_2.jpg" alt="bg_2">
        <img src="imgs/form_bg/bg_3.jpg" alt="bg_3">
        <img src="imgs/form_bg/bg_4.jpg" alt="bg_4">
        <img src="imgs/form_bg/bg_5.jpg" alt="bg_5">
        <img src="imgs/form_bg/bg_6.jpg" alt="bg_6">
        <img src="imgs/form_bg/bg_7.jpg" alt="bg_7">
        <img src="imgs/form_bg/bg_8.jpg" alt="bg_8">
    </div>

    <script>
        // Arka plan resimlerini sırayla değiştiren JavaScript kodu
        const images = [
            "imgs/form_bg/bg_1.jpg",
            "imgs/form_bg/bg_2.jpg",
            "imgs/form_bg/bg_3.jpg",
            "imgs/form_bg/bg_4.jpg",
            "imgs/form_bg/bg_5.jpg",
            "imgs/form_bg/bg_6.jpg",
            "imgs/form_bg/bg_7.jpg",
            "imgs/form_bg/bg_8.jpg"
        ];
        let currentIndex = 0;

        function changeBackground() {
            document.body.style.backgroundImage = `url('${images[currentIndex]}')`;
            currentIndex = (currentIndex + 1) % images.length;
        }

        setInterval(changeBackground, 5000);
        changeBackground();
    </script>
    <center>
        <i><b><img src="imgs/logo.png" alt="LiveTuMi Logo" width="100"></b></i>
        <br><br>
        <div class="tablo-kapsayici">
            <table>
                <form method="POST">
                    <tr>
                        <td colspan="2">
                            <div class="etiketli-girdi">
                                <label for="yeni_parola">Yeni Şifre</label>
                                <input type="password" id="yeni_parola" name="yeni_parola" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <div class="giris-yap">
                                <input type="submit" value="Şifreyi Güncelle">
                            </div>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </center>
</body>

</html>