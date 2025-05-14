<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imgs/favicon.png" type="image/x-icon">
    <title>Kullanici Girisi | LiveTuMi</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="giris">
    <center>
        <i><b class="kullanici-giris-formu" >Kullanici Giriş Formu</b></i>
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
                        <td colspan="2" >
                            <div class="giris-yap">       
                            <input type="submit" value="Giris Yap" name="girisyap">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <a href="kayitformu.php" class="yeni-hesap-link" style="text-decoration: none; color: white;">Yeni Hesap Oluştur</a>
                        </td>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </center>
</body>

</html>