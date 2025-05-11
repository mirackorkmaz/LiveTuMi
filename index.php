<?php
session_start();
if (isset($_SESSION["id"])) {
    include("baglanti.php");
    $id = $_SESSION["id"];

    // Eğer oturumdaki id "admin" ise yönetici sayfasına yönlendir
    if ($id === "admin") {
        echo "<center><br>Yönetici, yönetici sayfasında kalmalıdır.</center>";
        header("Refresh: 3; url=yonetici.php");
        exit;
    }

    $sorgu = mysqli_query($baglanti, "SELECT * FROM kullanicilar WHERE id='$id'");

    // Kullanıcı sorgusunun sonucunu kontrol et
    if ($sorgu && mysqli_num_rows($sorgu) > 0) {
        $kullanici = mysqli_fetch_assoc($sorgu);
        $ad = $kullanici["ad"];
        $soyad = $kullanici["soyad"];
    } else {
        echo "<center><br>Kullanıcı bulunamadı. Lütfen tekrar giriş yapın.</center>";
        session_destroy();
        header("Refresh: 3; url=girisformu.php");
        exit;
    }

    // Tür filtresi kontrolü
    $tur = isset($_GET['tur']) ? $_GET['tur'] : null;

    // Etkinlikleri tür filtresine göre çek
    if ($tur) {
        $etkinlik_sorgu = mysqli_query($baglanti, "SELECT * FROM etkinlikler WHERE tur='$tur' ORDER BY tarih ASC");
    } else {
        $etkinlik_sorgu = mysqli_query($baglanti, "SELECT * FROM etkinlikler ORDER BY tarih ASC");
    }

    // Tüm türleri çek
    $tur_sorgu = mysqli_query($baglanti, "SELECT DISTINCT tur FROM etkinlikler");

    // Sepet sorgusu
    $sepet_sorgu = mysqli_query($baglanti, "SELECT etkinlikler.id, etkinlikler.baslik, etkinlikler.fiyat, etkinlikler.foto, etkinlikler.stok, sepet.adet 
        FROM sepet 
        INNER JOIN etkinlikler ON sepet.etkinlik_id = etkinlikler.id 
        WHERE sepet.kullanici_id='$id'");
    $toplam_fiyat = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiveTuMi | Anasayfa</title>
    <link rel="icon" href="imgs/favicon.png" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(to bottom,rgb(7, 7, 7),rgb(48, 2, 2),rgb(24, 1, 1), #1a1a1a);            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
            padding: 10px 20px;
            background-color:rgb(7,7,7);
        }

        .header h1 {
            color: rgb(170, 168, 168);;
            margin: 0;
            padding: 0;
            font-size: 18px;
        }

        .header a {
            text-decoration: none;
            color:rgb(170, 168, 168);
            font-weight: bold;
        }

        .tur-buttons {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            gap: 10px;
        }

        .tur-buttons a {
            padding: 10px 20px;
            background-color:rgb(172, 68, 68);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
        }

        .tur-buttons a:hover {
            background-color: #0056b3;
        }

        .main {
            display: flex;
            flex: 1;
        }

        .etkinlikler-container {
            flex: 3;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .etkinlik {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            width: 300px;
            background-color: #f9f9f9;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .etkinlik-foto {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .etkinlik h3 {
            margin: 10px 0;
            color: #333;
        }

        .etkinlik p {
            margin: 5px 0;
            color: #555;
        }

        .sepeteekle {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .sepeteekle:hover {
            background-color: #218838;
        }

        .stok-tukendi {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }

        .sepet-container {
            flex: 1;
            padding: 20px;
            background-color:rgb(48, 3, 3);
            height: 100vh;
            overflow-y: auto;
        }

        .sepet-container h2 {
            text-align: center;
            color: #333;
        }

        .sepet-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .sepet-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .sepet-item p {
            margin: 0;
            flex: 1;
            color: #555;
        }

        .adet-buttons {
            display: flex;
            align-items: center;
        }

        .adet-buttons form {
            margin: 0 5px;
        }

        .adet-buttons button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .adet-buttons button:hover {
            background-color: #0056b3;
        }

        .toplam-fiyat {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        .sepet-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .toplam-tutar {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .toplam-tutar span {
            color: #28a745;
        }

        .satinal-button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }

        .satinal-button:hover {
            background-color: #218838;
        }
        .slider-container {
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(112, 4, 4, 0.3);
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* Internet Explorer ve Edge */
        }

        .slider-container::-webkit-scrollbar {
            display: none; /* Chrome, Safari için */
        }


        .slider {
            display: flex;
            scroll-behavior: smooth;
            width: 100%;
        }

        .slide {
            flex: none;
            width: 100%;
            height: 400px;
            scroll-snap-align: start;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .dot-nav {
            text-align: center;
            margin-top: 10px;
        }

        .dot {
            display: inline-block;
            width: 14px;
            height: 14px;
            margin: 0 5px;
            background-color: #aaa;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .dot:hover,
        .dot:focus {
            background-color: #c00; /* bordo tonu */
        }

    </style>
</head>

<body>
    <div class="header">
        <h1>Hoş Geldin, <?php echo $ad . " " . $soyad; ?></h1>
        <a href="cikis.php">Çıkış Yap</a>
    </div>

    <div class="slider-container">
        <div class="slider">
            <div class="slide" id="slide1"><img src="imgs/ts.JPG" alt="Image 1"></div>
            <div class="slide" id="slide2"><img src="image2.jpg" alt="Image 2"></div>
            <div class="slide" id="slide3"><img src="image3.jpg" alt="Image 3"></div>
        </div>
    </div>

    <div class="dot-nav">
        <a href="#slide1" class="dot"></a>
        <a href="#slide2" class="dot"></a>
        <a href="#slide3" class="dot"></a>
    </div>
    <!-- Tür Butonları -->
    <div class="tur-buttons">
        <a href="index.php">Tüm Etkinlikler</a>
        <?php
        while ($tur_row = mysqli_fetch_assoc($tur_sorgu)) {
            echo "<a href='index.php?tur=" . urlencode($tur_row['tur']) . "'>" . $tur_row['tur'] . "</a>";
        }
        ?>
    </div>
    <div class="main">
        <div class="etkinlikler-container">
            <?php
            if (mysqli_num_rows($etkinlik_sorgu) > 0) {
                while ($etkinlik = mysqli_fetch_assoc($etkinlik_sorgu)) {
                    echo "<div class='etkinlik'>";
                    echo "<img src='" . $etkinlik['foto'] . "' alt='" . $etkinlik['baslik'] . "' class='etkinlik-foto'>";
                    echo "<h3>" . $etkinlik['baslik'] . "</h3>";
                    echo "<p>" . $etkinlik['aciklama'] . "</p>";
                    echo "<p><strong>Tarih:</strong> " . $etkinlik['tarih'] . "</p>";
                    echo "<p><strong>Tür:</strong> " . $etkinlik['tur'] . "</p>";
                    echo "<p><strong>Fiyat:</strong> " . $etkinlik['fiyat'] . " TL</p>";
                    echo "<p><strong>Stok:</strong> " . $etkinlik['stok'] . "</p>";
                    if ($etkinlik['stok'] > 0) {
                        echo "<a href='sepeteekle.php?etkinlik_id=" . $etkinlik['id'] . "' class='sepeteekle'>Sepete Ekle</a>";
                    } else {
                        echo "<span class='stok-tukendi'>Stoklar Tükendi</span>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>Seçilen türde etkinlik bulunmamaktadır.</p>";
            }
            ?>
        </div>

        <div class="sepet-container">
            <h2>Sepetiniz</h2>
            <?php
            if (mysqli_num_rows($sepet_sorgu) > 0) {
                while ($sepet = mysqli_fetch_assoc($sepet_sorgu)) {
                    $toplam_fiyat += $sepet["fiyat"] * $sepet["adet"];
                    echo "<div class='sepet-item'>";
                    echo "<img src='" . $sepet['foto'] . "' alt='" . $sepet['baslik'] . "'>";
                    echo "<p>" . $sepet["baslik"] . " - " . ($sepet["fiyat"] * $sepet["adet"]) . " TL</p>";
                    echo "<div class='adet-buttons'>";
                    if ($sepet["adet"] < $sepet["stok"]) {
                        echo "<form action='sepeteekle.php' method='GET'>
                            <input type='hidden' name='etkinlik_id' value='" . $sepet["id"] . "'>
                            <button type='submit'>+</button>
                          </form>";
                    }
                    echo "<form action='sepettencikar.php' method='GET'>
                        <input type='hidden' name='etkinlik_id' value='" . $sepet["id"] . "'>
                        <button type='submit'>-</button>
                      </form>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "<div class='sepet-footer'>";
                echo "<p class='toplam-tutar'>Toplam Tutar: <span>" . $toplam_fiyat . " TL</span></p>";
                echo "<form action='satinal.php' method='POST'>
                    <button type='submit' class='satinal-button'>Satın All</button>
                  </form>";
                echo "</div>";
            } else {
                echo "<p>Sepetiniz boş.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php
} else {
    header("Location: girisformu.php");
    exit;
}
?>