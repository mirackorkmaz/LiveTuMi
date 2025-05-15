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
    <link rel="icon" href="imgs/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
</head>
<script>
//sepet simgesine tıklandığında sepeti aç
function toggleSepet() {
    const sepet = document.querySelector('.sepet-container');
    sepet.classList.toggle('acik');
}

// Sepete tıklanmadıysa kapat
document.addEventListener('click', function (event) {
    const sepet = document.querySelector('.sepet-container');
    const sepetButton = document.querySelector('.sepet-ac-button');

    // Eğer tıklanan yer sepetin içi veya sepet açma butonu değilse, kapat
    if (!sepet.contains(event.target) && !sepetButton.contains(event.target)) {
        sepet.classList.remove('acik');
    }
});
</script>
<body class="index">
    <div class="header">
        <img src="imgs/logo.png" alt="Logo" width="50">
        <h1>Hoş Geldin, <?php echo $ad . " " . $soyad; ?></h1>
        <div class="header-buttons">
            <a href="cikis.php">Çıkış Yap</a>
            <button class="sepet-ac-button" onclick="toggleSepet()">🛒</button>
        </div>
    </div>

    <div class="slider-container">
        <div class="slider">
            <div class="slide" id="slide1"><img src="imgs/reklam_1.png" alt="Image 1"></div>
            <div class="slide" id="slide2"><img src="imgs/indir.jpeg" alt="Image 2"></div>
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