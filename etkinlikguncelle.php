<?php
include("baglanti.php");
session_start();

// Eğer oturum açılmamışsa veya oturumdaki id "admin" değilse giriş sayfasına yönlendir
if (!isset($_SESSION["id"]) || $_SESSION["id"] !== "admin") {
    echo "<center><br>Bu sayfaya erişim izniniz yok. Lütfen giriş yapın.</center>";
    header("Refresh: 3; url=girisformu.php");
    exit;
}

// Güncellenecek etkinlik ID'sini al
if (!isset($_GET["id"])) {
    echo "<center><br>Etkinlik ID'si belirtilmedi.</center>";
    exit;
}

$etkinlik_id = $_GET["id"];

// Etkinlik bilgilerini çek
$sorgu = mysqli_query($baglanti, "SELECT * FROM etkinlikler WHERE id = '$etkinlik_id'");
if (mysqli_num_rows($sorgu) == 0) {
    echo "<center><br>Etkinlik bulunamadı.</center>";
    exit;
}

$etkinlik = mysqli_fetch_assoc($sorgu);

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["etkinlik_guncelle"])) {
    $baslik = $_POST["baslik"];
    $aciklama = $_POST["aciklama"];
    $tarih = $_POST["tarih"];
    $tur = $_POST["tur"];
    $ilgi_alani = $_POST["ilgi_alani"];
    $fiyat = $_POST["fiyat"];
    $stok = $_POST["stok"];

    // Fotoğraf güncelleme
    if (!empty($_FILES["foto"]["name"])) {
        // `imgs/` dizini yoksa oluştur
        if (!is_dir("imgs")) {
            mkdir("imgs", 0777, true);
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
    } else {
        $foto_yolu = $etkinlik["foto"];
    }

    // Veritabanını güncelle
    $guncelle_sorgu = "UPDATE etkinlikler SET baslik = ?, aciklama = ?, tarih = ?, tur = ?, ilgi_alani = ?, foto = ?, fiyat = ?, stok = ? WHERE id = ?";
    $stmt = $baglanti->prepare($guncelle_sorgu);
    $stmt->bind_param("ssssssdii", $baslik, $aciklama, $tarih, $tur, $ilgi_alani, $foto_yolu, $fiyat, $stok, $etkinlik_id);
    $stmt->execute();
    $stmt->close();

    echo "<p>Etkinlik başarıyla güncellendi!</p>";
    header("Refresh: 2; url=yonetici.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik Güncelle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .form-container label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Etkinlik Güncelle</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="baslik">Başlık:</label>
            <input type="text" name="baslik" id="baslik" value="<?php echo $etkinlik['baslik']; ?>" required>

            <label for="aciklama">Açıklama:</label>
            <textarea name="aciklama" id="aciklama" required><?php echo $etkinlik['aciklama']; ?></textarea>

            <label for="tarih">Tarih:</label>
            <input type="date" name="tarih" id="tarih" value="<?php echo $etkinlik['tarih']; ?>" required>

            <label for="tur">Tür:</label>
            <input type="text" name="tur" id="tur" value="<?php echo $etkinlik['tur']; ?>" required>

            <label for="ilgi_alani">İlgi Alanı:</label>
            <input type="text" name="ilgi_alani" id="ilgi_alani" value="<?php echo $etkinlik['ilgi_alani']; ?>" required>

            <label for="foto">Fotoğraf:</label>
            <input type="file" name="foto" id="foto" accept="image/*">

            <label for="fiyat">Fiyat:</label>
            <input type="number" name="fiyat" id="fiyat" step="0.01" value="<?php echo $etkinlik['fiyat']; ?>" required>

            <label for="stok">Stok:</label>
            <input type="number" name="stok" id="stok" value="<?php echo $etkinlik['stok']; ?>" required>

            <input type="submit" name="etkinlik_guncelle" value="Güncelle">
        </form>
    </div>
</body>

</html>