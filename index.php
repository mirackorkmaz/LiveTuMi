<?php
session_start(); // Oturum başlatılıyor
if (isset($_SESSION["id"])) { // Oturum kontrolü yapılıyor
    include("baglanti.php"); // Veritabanı bağlantı dosyası dahil ediliyor
    include("hava_durumu.php"); // Hava durumu fonksiyonları dahil ediliyor
    $id = $_SESSION["id"]; // Oturum id'si alınıyor

    // Eğer oturumdaki id "admin" ise yönetici sayfasına yönlendir
    if ($id === "admin") {
        echo "<center><br>Yönetici, yönetici sayfasında kalmalıdır.</center>";
        header("Refresh: 3; url=yonetici.php");
        exit;
    }

    // Kullanıcı bilgilerini veritabanından çek
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

    // URL'den tür parametresini al
    $tur = isset($_GET['tur']) ? $_GET['tur'] : null;

    // Etkinlikleri tür filtresine göre veya tümünü listele
    if ($tur) {
        $etkinlik_sorgu = mysqli_query($baglanti, "SELECT * FROM etkinlikler WHERE tur='$tur' ORDER BY tarih ASC");
    } else {
        $etkinlik_sorgu = mysqli_query($baglanti, "SELECT * FROM etkinlikler ORDER BY tarih ASC");
    }

    // Etkinlik türlerini benzersiz olarak listele
    $tur_sorgu = mysqli_query($baglanti, "SELECT DISTINCT tur FROM etkinlikler");

    // Kullanıcının sepetindeki ürünleri ve detaylarını çek
    $sepet_sorgu = mysqli_query($baglanti, "SELECT etkinlikler.id, etkinlikler.baslik, etkinlikler.fiyat, etkinlikler.foto, etkinlikler.stok, sepet.adet 
        FROM sepet 
        INNER JOIN etkinlikler ON sepet.etkinlik_id = etkinlikler.id 
        WHERE sepet.kullanici_id='$id'");
    $toplam_fiyat = 0;

    // OpenWeatherMap API'den hava durumu verilerini al
    $weatherData = getWeatherData();
    $temperature = $weatherData['main']['temp'];
    $isEventPossible = isEventPossible($temperature);
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
        // Sepet açma/kapama işlevi
        function toggleSepet() {
            const sepet = document.querySelector('.sepet-container');
            sepet.classList.toggle('acik');
        }

        // Sepet dışına tıklandığında sepeti kapat
        document.addEventListener('click', function(event) {
            const sepet = document.querySelector('.sepet-container');
            const sepetButton = document.querySelector('.sepet-ac-button');

            // Eğer tıklanan yer sepetin içi veya sepet açma butonu değilse, kapat
            if (!sepet.contains(event.target) && !sepetButton.contains(event.target)) {
                sepet.classList.remove('acik');
            }
        });

        // Ödeme yöntemi seçildiğinde satın al butonunu aktif et
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="odeme_yontemi"]');
            const satinAlButton = document.querySelector('.satinal-button');
            const hiddenInput = document.getElementById('secilen_odeme_yontemi');
            const satinAlForm = document.getElementById('satinAlForm');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    satinAlButton.disabled = false;
                    hiddenInput.value = this.value;
                });
            });
        });

        // Otomatik slider fonksiyonu
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('autoSlider');
            const slides = slider.getElementsByClassName('slide');
            const dots = document.getElementsByClassName('dot');
            let currentIndex = 0;

            // Tüm slaytları gizle ve ilk slaytı göster
            function initSlider() {
                for (let i = 0; i < slides.length; i++) {
                    slides[i].style.display = 'none';
                }
                slides[0].style.display = 'block';
                updateDots();
            }

            // Sonraki slayta geç
            function nextSlide() {
                slides[currentIndex].style.display = 'none';
                currentIndex = (currentIndex + 1) % slides.length;
                slides[currentIndex].style.display = 'block';
                updateDots();
            }

            // Dot navigasyonunu güncelle
            function updateDots() {
                for (let i = 0; i < dots.length; i++) {
                    dots[i].classList.remove('active');
                }
                dots[currentIndex].classList.add('active');
            }

            // Slider'ı başlat
            initSlider();

            // Her 7 saniyede bir sonraki slayta geç
            setInterval(nextSlide, 7000);
        });
    </script>

    <body class="index">
        <div class="header">
            <img src="imgs/logo.png" alt="Logo" width="50">
            <h1>Hoş Geldin, <?php echo $ad . " " . $soyad; ?></h1>
            <div class="weather-info">
                <span>Hava durumu: <?php echo round($temperature, 1); ?>°C</span>
            </div>
            <div class="header-buttons">
                <a href="cikis.php">Çıkış Yap</a>
                <button class="sepet-ac-button" onclick="toggleSepet()">🛒</button>
            </div>
        </div>

        <!-- Slider bölümü -->
        <div class="slider-container">
            <div class="slider" id="autoSlider">
                <?php
                $slider_sorgu = mysqli_query($baglanti, "SELECT * FROM slider_fotograflar ORDER BY sira ASC");
                while ($foto = mysqli_fetch_assoc($slider_sorgu)) {
                    echo "<div class='slide'>";
                    echo "<img src='" . $foto['foto_yolu'] . "' alt='Slider Image'>";
                    echo "</div>";
                }
                ?>
            </div>
            <!-- Dot navigasyonu slider-container içinde olmalı -->
            <div class="dot-nav">
                <?php
                mysqli_data_seek($slider_sorgu, 0);
                while ($foto = mysqli_fetch_assoc($slider_sorgu)) {
                    echo "<span class='dot'></span>";
                }
                ?>
            </div>
        </div>

        <!-- Etkinlik türü filtreleme butonları -->
        <div class="tur-buttons">
            <a href="index.php">Tüm Etkinlikler</a>
            <?php
            while ($tur_row = mysqli_fetch_assoc($tur_sorgu)) {
                echo "<a href='index.php?tur=" . urlencode($tur_row['tur']) . "'>" . $tur_row['tur'] . "</a>";
            }
            ?>
        </div>

        <!-- Ana içerik bölümü -->
        <div class="main">
            <!-- Etkinliklerin listelendiği bölüm -->
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
                        echo "<p><strong>Gerçekleşme Durumu:</strong> ";
                        if (!$isEventPossible) {
                            echo "<span style='color: red;'>Hava Koşulları Nedeniyle İptal Edilebilir</span>";
                        } else {
                            echo "<span style='color: green;'>Planlandığı Gibi</span>";
                        }
                        echo "</p>";
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

            <!-- Sepet bölümü -->
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
                    // Sepet footer kısmını değiştir
                    echo "<div class='sepet-footer'>";
                    echo "<div class='odeme-secenekleri'>";
                    echo "<h4>Ödeme Yöntemi Seçin:</h4>";
                    echo "<label><input type='radio' name='odeme_yontemi' value='kredi_karti'> Kredi Kartı</label>";
                    echo "<label><input type='radio' name='odeme_yontemi' value='havale'> Havale/EFT</label>";
                    echo "<label><input type='radio' name='odeme_yontemi' value='kapida'> Kapıda Ödeme</label>";
                    echo "</div>";
                    echo "<div class='satin-alma-satiri'>";
                    echo "<p class='toplam-tutar'>Toplam Tutar: <span>" . $toplam_fiyat . " TL</span></p>";
                    echo "<form action='satinal.php' method='POST' id='satinAlForm'>";
                    echo "<input type='hidden' name='odeme_yontemi' id='secilen_odeme_yontemi'>";
                    echo "<button type='submit' class='satinal-button' disabled>Satın Al</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p>Sepetiniz boş.</p>";
                }
                ?>
            </div>
        </div>
        <footer class="site-footer">
            <p>Bu sayfa Tuana Akyazı ve Miraç Korkmaz tarafından İnternet Programcılığı dersi kapsamında hazırlanmıştır.</p>
        </footer>
    </body>

    </html>
<?php
} else {
    // Oturum açılmamışsa giriş sayfasına yönlendir
    header("Location: girisformu.php");
    exit;
}
?>