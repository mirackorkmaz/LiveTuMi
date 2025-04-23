<?php

include("baglanti.php");

    if (isset($_POST["kaydet"])) {
        $ad = mysqli_real_escape_string($baglanti, $_POST["isim"]);
        $soyad = mysqli_real_escape_string($baglanti, $_POST["soyad"]);
        $dogum_tarihi = $_POST["dogum"];
        $email = mysqli_real_escape_string($baglanti, $_POST["eposta"]);
        $parola = $_POST["sifre1"];
        $parola_tekrari = $_POST["sifre2"];

        if ($parola != $parola_tekrari) {
            die("Şifreler uyuşmuyor.");
        }

        $ekle_sorgusu = "INSERT INTO kullanicilar (ad, soyad, email, parola, dogum_tarihi) VALUES ('$ad', '$soyad', '$email', '$parola', '$dogum_tarihi')";

        $ekle_sorgusunu_calistir = mysqli_query($baglanti, $ekle_sorgusu);

        if ($ekle_sorgusunu_calistir) {
            echo "Kayit işlemi başarili, yönetici onayi bekleniyor.";
        }else {
            echo "Kayit olma sirasinda bir problem olustu!".mysqli_error($baglanti);
        }
    }
?>