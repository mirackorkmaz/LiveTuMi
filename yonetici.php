<?php
include("baglanti.php");
session_start();

// Eğer oturum açılmamışsa veya oturumdaki id "admin" değilse giriş sayfasına yönlendir
if (!isset($_SESSION["id"]) || $_SESSION["id"] !== "admin") {
    echo "<center><br>Bu sayfaya erişim izniniz yok. Lütfen giriş yapin.</center>";
    header("Refresh: 3; url=girisformu.php");
    exit;
}

// Yönetici sayfasının içeriği burada devam eder
echo "<center><h1>Hoşgeldiniz, Yönetici!</h1></center>";
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imgs/favicon.png" type="image/x-icon">
    <title>Yönetici Paneli</title>
    <style>
        #yonetici-bolum1,
        #yonetici-bolum2,
        #yonetici-bolum3,
        #yonetici-bolum4 {
            margin: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        #yonetici-bolum3 .kullanici-listesi {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #yonetici-bolum3 .kullanici-listesi.onaylandi {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        #yonetici-bolum3 .kullanici-listesi.reddedildi {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        #yonetici-bolum3 .kullanici-listesi.beklemede {
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        #yonetici-bolum3 .form-kapsayici {
            margin-top: 20px;
        }

        #yonetici-bolum3 .form-submit-container {
            text-align: right;
            margin-top: 20px;
        }

        #yonetici-bolum3 .form-kapsayici input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #yonetici-bolum3 .form-kapsayici input[type="submit"]:hover {
            background-color: #0056b3;
        }

        #cikis-buton {
            margin: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        #cikis-buton:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <section id="yonetici-bolum1">
        <h2>Bölüm 1: Diğer İşlemler</h2>
        <p>Bu alan aşkomun işlemleri için ayrilmiştir.</p>
    </section>
    <section id="yonetici-bolum2">
        <h2>Bölüm 2: Diğer İşlemler</h2>
        <p>Bu alan aşkomun işlemleri için ayrilmiştir.</p>
    </section>
    <section id="yonetici-bolum3">
        <h2>Bölüm 3: Kullanici Yönetimi</h2>
        <?php
        $sorgu = mysqli_query($baglanti, "SELECT id, ad, soyad, mailadresi, dogumtarihi, status FROM kullanicilar");
        $satirsayisi = mysqli_num_rows($sorgu);

        if ($satirsayisi == 0) {
            echo "<p class='kullanici-listesi'>Kullanici bulunamadi.</p>";
            exit;
        }

        echo '<form method="post" action="" class="form-kapsayici">';
        mysqli_data_seek($sorgu, 0);
        while ($sorgusonucu = $sorgu->fetch_assoc()) {
            $id = $sorgusonucu["id"];
            $ad = $sorgusonucu["ad"];
            $soyad = $sorgusonucu["soyad"];
            $mailadresi = $sorgusonucu["mailadresi"];
            $dogumtarihi = $sorgusonucu["dogumtarihi"];
            $status = $sorgusonucu["status"];

            // Duruma göre CSS sınıfı belirle
            $satir_sinifi = "";
            if ($status === "onaylandi") {
                $satir_sinifi = "onaylandi";
            } elseif ($status === "reddedildi") {
                $satir_sinifi = "reddedildi";
            } else {
                $satir_sinifi = "beklemede"; // Beklemede durumu için varsayılan sınıf
            }

            echo "<div class='kullanici-listesi $satir_sinifi'>";
            echo "<span>ID: $id   |   Ad: $ad $soyad   |   Mail: $mailadresi   |   Doğum Tarihi: $dogumtarihi</span>";
            echo "<span>";
            echo "<input type='radio' name='durum_$id' value='onaylandi'> Onayla";
            echo "<input type='radio' name='durum_$id' value='reddedildi'> Reddet";
            echo "</span>";
            echo "</div>";
        }
        echo '<div class="form-submit-container">';
        echo '<input type="submit" name="submit" value="Kaydet">';
        echo '</div>';
        echo '</form>';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            mysqli_data_seek($sorgu, 0);
            while ($sorgusonucu = $sorgu->fetch_assoc()) {
                $id = $sorgusonucu["id"];
                if (isset($_POST["durum_$id"])) {
                    $yeni_deger = $_POST["durum_$id"];
                    $guncelleme_sorgusu = "UPDATE kullanicilar SET status = ? WHERE id = ?";
                    $stmt = $baglanti->prepare($guncelleme_sorgusu);
                    $stmt->bind_param("si", $yeni_deger, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            echo "<center>Durumlar başariyla güncellendi.</center>";
        }
        ?>
    </section>
    <section id="yonetici-bolum4">
        <h2>Bölüm 4: Diğer İşlemler</h2>
        <p>Bu alan aşkomun işlemleri için ayrilmiştir.</p>
    </section>
    <center>
        <a href="cikis.php" id="cikis-buton">Çikiş Yap</a>
    </center>
</body>

</html>