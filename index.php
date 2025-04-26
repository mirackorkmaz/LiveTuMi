<?php
include("baglanti.php");
include("kayit.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>LiveTuMi</title>
    <script>
        function openTab(tabName, element) {
            var forms = document.getElementsByClassName("form");
            var tabs = document.getElementsByClassName("tab");
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = "none";
            }
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].style.fontWeight = "normal";
            }
            document.getElementById(tabName).style.display = "block";
            element.style.fontWeight = "bold";
        }
    </script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body style="background-image: url(&quot;../imgs/cinema.png&quot;)"> 
    <div class="tab-container">
        <button class="tab" onclick="openTab('giris', this)">Giriş Yap</button>
        <button class="tab" onclick="openTab('uye', this)">Üye Ol</button>
    </div>

    <!-- Giriş Yapma Formu -->
    <div id="giris" class="form" style="display: block;">
        <form action="" method="post">
            <h3>Üye Girişi</h3>
            <table>
                <tr>
                    <td><label for="girisEposta">Kullanıcı Adı :</label></td>
                    <td><input type="email" name="girisEposta" id="girisEposta" placeholder="tuanaakyazi@hotmail.com" required></td>
                </tr>
                <tr>
                    <td><label for="sifre">Şifre :</label></td>
                    <td><input type="password" name="sifre" id="sifre" placeholder="*****" maxlength="10" required></td>
                </tr>
                <tr>
                    <td></td> <!-- butonu hizzalamak icin bos -->
                    <td><button type="submit">Giriş yap</button></td>
                </tr>
            </table>
        </form>
    </div>
    <!-- Üye Olma Formu -->
    <div id="uye" class="form" style="display: none;">
        <form action ="",method ="post">
            <h3>Üye Ol</h3>
            <table>
                <tr>
                    <td><label for="isim">Ad :</label></td>
                    <td><input type="text" name="isim" id="isim" placeholder="Ad" required></td>
                </tr>
                <tr>
                    <td><label for="soyad">Soyad :</label></td>
                    <td><input type="text" name="soyad" id="soyad" placeholder="Soyad" required></td>
                </tr>
                <tr>
                    <td><label for="dogum">Doğum Günü :</label></td>
                    <td><input type="date" name="dogum" id="dogum" required></td>
                </tr>
                <tr>
                    <td><label for="eposta">Kullanıcı Adı :</label></td>
                    <td><input type="email" name="eposta" id="eposta" placeholder="tuanaakyazi@hotmail.com" required></td>
                </tr>
                <tr>
                    <td><label for="sifre1">Şifre :</label></td>
                    <td><input type="password" name="sifre1" id="sifre1" placeholder="*****" required></td>
                </tr>
                <tr>
                    <td><label for="sifre2">Şifre Tekrar :</label></td>
                    <td><input type="password" name="sifre2" id="sifre2" placeholder="*****" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" name="kaydet">Üye Ol</button></td>
                </tr>
            </table>
        </form>
    </div>

</body>
</html>