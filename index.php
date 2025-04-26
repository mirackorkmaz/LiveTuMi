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
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh; /* Ekranın yüksekliği kadar alan aç */
            display: flex; /* Flexbox sistemi */
            flex-direction: column; /* elemanları üst üste sıralamak için */
            justify-content: center; /* dikeyde ortala */
            align-items: center; /* yataya ortala */
            font-family: Arial, sans-serif;
            color: white;
        }
        body::before {
            content: "";
            background-image: url("cinema.png");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            opacity: 0.9; 
            filter: brightness(0.4);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Resim arka planda */
        }
        .tab-container {
            margin-bottom: 0px;
        }
        .tab {
            margin: 0 40px;
            border: none;
            background-color: #444;
            color: white;
            cursor: pointer; /*buton uzerine geldiginde el isareti*/
            border-radius: 15px;
            font-weight: bold;
        }
        .tab:hover {
            background-color: #666;
        }
        .form {
            background-color: rgba(0, 0, 0, 0.6); 
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
            transition: all 0.5s ease; /*degisiklik oldugunda yumusak gecis*/
        }
        td label {
            display: inline-block;
            width: 120px;
            text-align: right;          /* Bitişleri hizalanır (sağa yaslanır) */
            padding-right: 10px;        /* Inputla arasında boşluk bırakır */
        }

        input {
            padding: 5px;
            border-radius: 5px;
            border: none;
            width: 160px;               /* Tüm inputlar aynı genişlikte */
        }
    </style>
</head>
<body>
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