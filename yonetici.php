<?php
ob_start(); // Çıktı tamponlamayı başlat

include("baglanti.php");
session_start();

// Eğer oturum açılmamışsa veya oturumdaki id "admin" değilse giriş sayfasına yönlendir
if (!isset($_SESSION["id"]) || $_SESSION["id"] !== "admin") {
    echo "<center><br>Bu sayfaya erişim izniniz yok. Lütfen giriş yapın.</center>";
    header("Refresh: 3; url=girisformu.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="imgs/favicon.png" type="image/x-icon">
    <title>Yönetici Paneli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #007bff;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            background-color: #dc3545;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .header a:hover {
            background-color: #c82333;
        }

        section {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            margin-top: 0;
            font-size: 20px;
            color: #333;
        }

        .kullanici-listesi {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kullanici-listesi.onaylandi {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .kullanici-listesi.reddedildi {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .kullanici-listesi.beklemede {
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        .form-kapsayici {
            margin-top: 20px;
        }

        .form-submit-container {
            text-align: right;
            margin-top: 20px;
        }

        .form-kapsayici input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-kapsayici input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .etkinlik-form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            font-size: 14px;
        }

        .etkinlik-form input,
        .etkinlik-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .etkinlik-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .etkinlik-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .etkinlik-tablosu {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .etkinlik-tablosu th,
        .etkinlik-tablosu td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        .etkinlik-tablosu th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .etkinlik-tablosu img {
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Hoşgeldin Yönetici</h1>
        <a href="cikis.php">Çıkış Yap</a>
    </div>

    <section id="yonetici-bolum1">
        <h2>Bölüm 1: Diğer İşlemler</h2>
        <p>Bu alan aşkomun işlemleri için ayrılmıştır.</p>
    </section>

    <section id="yonetici-bolum2">
        <h2>Bölüm 2: Diğer İşlemler</h2>
        <p>Bu alan aşkomun işlemleri için ayrılmıştır.</p>
    </section>

    <section id="yonetici-bolum3">
        <h2>Bölüm 3: Kullanıcı Yönetimi</h2>
        <?php
        $sorgu = mysqli_query($baglanti, "SELECT id, ad, soyad, mailadresi, dogumtarihi, status FROM kullanicilar");
        $satirsayisi = mysqli_num_rows($sorgu);

        if ($satirsayisi == 0) {
            echo "<p class='kullanici-listesi'>Kullanıcı bulunamadı.</p>";
        } else {
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
                    $satir_sinifi = "beklemede";
                }

                echo "<div class='kullanici-listesi $satir_sinifi'>";
                echo "<span>ID: $id | Ad: $ad $soyad | Mail: $mailadresi | Doğum Tarihi: $dogumtarihi</span>";
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
        }

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
            echo "<center>Durumlar başarıyla güncellendi.</center>";
        }
        ?>
    </section>

    <section id="yonetici-bolum4">
        <h2>Bölüm 4: Etkinlik Yönetimi</h2>

        <!-- Etkinlik Ekleme Formu -->
        <h3>Yeni Etkinlik Ekle</h3>
        <form method="post" action="" enctype="multipart/form-data" class="etkinlik-form">
            <label for="baslik">Başlık:</label>
            <input type="text" name="baslik" id="baslik" required>

            <label for="aciklama">Açıklama:</label>
            <textarea name="aciklama" id="aciklama" required></textarea>

            <label for="tarih">Tarih:</label>
            <input type="date" name="tarih" id="tarih" required>

            <label for="tur">Tür:</label>
            <input type="text" name="tur" id="tur" required>

            <label for="ilgi_alani">İlgi Alanı:</label>
            <input type="text" name="ilgi_alani" id="ilgi_alani" required>

            <label for="foto">Fotoğraf:</label>
            <input type="file" name="foto" id="foto" accept="image/*" required>

            <label for="fiyat">Fiyat:</label>
            <input type="number" name="fiyat" id="fiyat" step="0.01" required>

            <label for="stok">Stok:</label>
            <input type="number" name="stok" id="stok" required>

            <input type="submit" name="etkinlik_ekle" value="Etkinlik Ekle">
        </form>

        <?php
        // Etkinlik Ekleme İşlemi
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["etkinlik_ekle"])) {
            $baslik = $_POST["baslik"];
            $aciklama = $_POST["aciklama"];
            $tarih = $_POST["tarih"];
            $tur = $_POST["tur"];
            $ilgi_alani = $_POST["ilgi_alani"];
            $fiyat = $_POST["fiyat"];
            $stok = $_POST["stok"];

            // Fotoğrafı yükleme
            if (!is_dir("imgs")) {
                mkdir("imgs", 0777, true); // `imgs/` dizini yoksa oluştur
            }

            $foto = $_FILES["foto"]["name"];
            $foto_tmp = $_FILES["foto"]["tmp_name"];
            $foto_yolu = "imgs/" . $foto;

            // Dosyayı taşı
            if (move_uploaded_file($foto_tmp, $foto_yolu)) {
                echo "<p>Fotoğraf başarıyla yüklendi.</p>";
            } else {
                echo "<p>Fotoğraf yüklenirken bir hata oluştu.</p>";
                exit;
            }

            // Veritabanına ekleme
            $ekle_sorgu = "INSERT INTO etkinlikler (baslik, aciklama, tarih, tur, ilgi_alani, foto, fiyat, stok) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $baglanti->prepare($ekle_sorgu);
            $stmt->bind_param("ssssssdi", $baslik, $aciklama, $tarih, $tur, $ilgi_alani, $foto_yolu, $fiyat, $stok);
            $stmt->execute();
            $stmt->close();

            echo "<p>Etkinlik başarıyla eklendi!</p>";
        }
        ?>

        <!-- Mevcut Etkinlikler -->
        <h3>Mevcut Etkinlikler</h3>
        <table class="etkinlik-tablosu">
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Açıklama</th>
                <th>Tarih</th>
                <th>Tür</th>
                <th>İlgi Alanı</th>
                <th>Fotoğraf</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>İşlemler</th>
            </tr>
            <?php
            $etkinlik_sorgu = mysqli_query($baglanti, "SELECT * FROM etkinlikler");
            while ($etkinlik = mysqli_fetch_assoc($etkinlik_sorgu)) {
                echo "<tr>";
                echo "<td>" . $etkinlik["id"] . "</td>";
                echo "<td>" . $etkinlik["baslik"] . "</td>";
                echo "<td>" . $etkinlik["aciklama"] . "</td>";
                echo "<td>" . $etkinlik["tarih"] . "</td>";
                echo "<td>" . $etkinlik["tur"] . "</td>";
                echo "<td>" . $etkinlik["ilgi_alani"] . "</td>";
                echo "<td><img src='" . $etkinlik["foto"] . "' width='50'></td>";
                echo "<td>" . $etkinlik["fiyat"] . " TL</td>";
                echo "<td>" . $etkinlik["stok"] . "</td>";
                echo "<td>
                    <form method='post' action='' style='display:inline;'>
                        <input type='hidden' name='sil_id' value='" . $etkinlik["id"] . "'>
                        <input type='submit' name='etkinlik_sil' value='Sil'>
                    </form>
                    <form method='post' action='' style='display:inline;'>
                        <input type='hidden' name='guncelle_id' value='" . $etkinlik["id"] . "'>
                        <input type='submit' name='etkinlik_guncelle' value='Güncelle'>
                    </form>
                  </td>";
                echo "</tr>";
            }
            ?>
        </table>

        <?php
        // Etkinlik Silme İşlemi
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["etkinlik_sil"])) {
            $sil_id = $_POST["sil_id"];
            $sil_sorgu = "DELETE FROM etkinlikler WHERE id = ?";
            $stmt = $baglanti->prepare($sil_sorgu);
            $stmt->bind_param("i", $sil_id);
            $stmt->execute();
            $stmt->close();

            echo "<p>Etkinlik başarıyla silindi!</p>";
            header("Refresh: 2");
        }

        // Etkinlik Güncelleme İşlemi
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["etkinlik_guncelle"])) {
            $guncelle_id = $_POST["guncelle_id"];
            header("Location: etkinlikguncelle.php?id=$guncelle_id");
            exit;
        }
        ?>
    </section>
</body>

</html>