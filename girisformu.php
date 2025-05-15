<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon">
    <title>Giriş Yap | LiveTuMi</title>
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
            // Arka plan resmini değiştir
            document.body.style.backgroundImage = `url('${images[currentIndex]}')`;
            currentIndex = (currentIndex + 1) % images.length;
        }

        // Arka planı her 5 saniyede bir değiştir
        setInterval(changeBackground, 5000);

        // Sayfa yüklendiğinde ilk arka planı ayarla
        changeBackground();
    </script>
    <center>
        <img src="imgs/logo.png" alt="LiveTuMi Logo" width="100">
        <br><br>
        <div class="tablo-kapsayici">
            <table>
                <form action="oturumac.php" method="POST">
                    <tr>
                        <td colspan="2">
                            <div class="etiketli-girdi">
                                <label for="mailadresi">E-posta</label>
                                <input type="email" id="mailadresi" name="mailadresi" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="etiketli-girdi">
                                <label for="parola">Parola</label>
                                <input type="password" id="parola" name="parola" required>
                            </div>
                        </td>
                    </tr>
                    <td colspan="2">
                        <div class="giris-yap">
                            <input type="submit" value="Giris Yap" name="girisyap">
                        </div>
                    </td>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <a href="kayitformu.php" class="yeni-hesap-link" style="text-decoration: none; color: white;">Yeni Hesap Oluştur</a>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </center>
</body>

</html>