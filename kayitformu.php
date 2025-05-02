<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imgs/favicon.png" type="image/x-icon">
    <title>Kullanici Kaydi | LiveTuMi</title>
    <style>
        body {
            background-image: url('imgs/cinema.png');
            background-size: 100% 100%; /* Resmi sayfanın genişliğine ve yüksekliğine sığdır */
            background-repeat: no-repeat; /* Resmin tekrar etmesini engelle */
            background-position: center; /* Resmi ortala */
            height: 100vh; /* Sayfanın yüksekliğini tam olarak kapla */
            margin: 0; /* Tarayıcı varsayılan boşluklarını kaldır */
            display: flex; /* Flexbox kullanarak içeriği ortala */
            justify-content: center; /* Yatayda ortala */
            align-items: center; /* Dikeyde ortala */
            color: white; /* Yazı rengi beyaz */
            font-family: Arial, sans-serif; /* Yazı tipi */
            text-align: center; /* Metni ortala */
            font-size: 18px; /* Yazı boyutu */
            line-height: 1.5; /* Satır yüksekliği */
            padding: 20px; /* İçerik çevresine boşluk ekle */
            box-sizing: border-box; /* İçerik ve kenar boşluklarını kapsayıcıya dahil et */
            overflow: hidden; /* Taşan içeriği gizle */
            position: relative; /* Konumlandırma için gerekli */
            z-index: 1; /* Diğer öğelerin üstünde görünmesi için */
            filter: blur(0px); /* Arka plan bulanıklığını kaldır */
            transition: filter 0.3s ease; /* Geçiş efekti ekle */
        }

        .tablo-kapsayici {
            background-color: rgba(0, 0, 0, 0.6); /* Beyaz arka plan ve %80 opasite */
            color: white; /* Yazı rengi beyaz */
            font-family: Arial, sans-serif; /* Yazı tipi */
            border-radius: 10px; /* Köşeleri yuvarlat */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Hafif gölge efekti */
            padding: 15px; /* Tablo çevresine boşluk ekle */
            display: inline-block; /* İçeriği ortalamak için */
        }

        table {
            border-spacing: 0; /* Hücreler arasındaki boşlukları kaldır */
            width: 100%; /* Tablo genişliğini %100 yap */
            max-width: 600px; /* Maksimum genişlik ayarla */
            margin: 0 auto; /* Ortala */
            border-collapse: collapse; /* Kenarları birleştir */
        }

        td {
            padding: 10px; /* Hücrelere iç boşluk ekle */
            text-align: left; /* Metni sola hizala */
            color: white; /* Yazı rengi beyaz */
            font-size: 18px; /* Yazı boyutu */
            font-family: Arial, sans-serif; /* Yazı tipi */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* Alt kenar çizgisi */
        }
    </style>
</head>
<body>
    <center>
        <i><b style="font-size: 24px; color: rgba(255, 255, 255, 0.8);">Kullanici Kayit Formu</b></i>
        <br><br>
        <div class="tablo-kapsayici">
            <table>
                <form action="kullanicikaydi.php" method="POST">
                    <tr>
                        <td>Ad: </td>
                        <td><input type="text" name="ad" required></td>
                    </tr>
                    <tr>
                        <td>Soyad: </td>
                        <td><input type="text" name="soyad" required></td>
                    </tr>
                    <tr>
                        <td>E-Posta: </td>
                        <td><input type="email" name="mailadresi" required></td>
                    </tr>
                    <tr>
                        <td>Doğum Tarihi: </td>
                        <td><input type="date" name="dogumtarihi" required></td>
                    </tr>
                    <tr>
                        <td>Parola: </td>
                        <td><input type="password" name="parola" required></td>
                    </tr>
                    <tr>
                        <td>Parola Tekrari: </td>
                        <td><input type="password" name="parolatekrari" required></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                        <input type="submit" value="Kayit Ol" name="kayitol">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <a href="girisformu.php" style="text-decoration: none; color: white;">Zaten Hesabim Var</a>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </center>
</body>
</html>