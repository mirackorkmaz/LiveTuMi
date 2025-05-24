<?php
session_start(); // Oturum başlatılıyor
include("baglanti.php"); // Veritabanı bağlantısı dahil ediliyor

// Kullanıcının oturum kontrolü
if (isset($_SESSION["id"])) {
    $kullanici_id = $_SESSION["id"]; // Oturumdaki kullanıcı ID'sini al

    // Ödeme yöntemini al ve varsayılan değer ata
    $odeme_yontemi = isset($_POST['odeme_yontemi']) ? $_POST['odeme_yontemi'] : '';
    $odeme_metni = '';
    
    // Seçilen ödeme yöntemine göre mesaj metnini belirle
    switch($odeme_yontemi) {
        case 'kredi_karti':
            $odeme_metni = 'Kredi kartı ile';
            break;
        case 'havale':
            $odeme_metni = 'Havale/EFT ile';
            break;
        case 'kapida':
            $odeme_metni = 'Kapıda ödeme ile';
            break;
        default:
            $odeme_metni = '';
    }
    
    // Sepetteki ürünleri al ve stokları güncelle
    $sepet_sorgu = mysqli_query($baglanti, "SELECT etkinlik_id, adet FROM sepet WHERE kullanici_id='$kullanici_id'");
    while ($sepet = mysqli_fetch_assoc($sepet_sorgu)) {
        $etkinlik_id = $sepet["etkinlik_id"];
        $adet = $sepet["adet"];
        // Her etkinliğin stok miktarını sepetteki adet kadar azalt
        mysqli_query($baglanti, "UPDATE etkinlikler SET stok = stok - $adet WHERE id='$etkinlik_id'");
    }

    // Satın alma işlemi tamamlandığı için sepeti temizle
    mysqli_query($baglanti, "DELETE FROM sepet WHERE kullanici_id='$kullanici_id'");

    // Başarılı işlem mesajını göster ve ana sayfaya yönlendir
    echo "<p>$odeme_metni satın alma işleminiz başarıyla gerçekleştirildi! Stoklar güncellendi.</p>";
    header("Refresh: 3; url=index.php");
    exit;
} else {
    // Oturum yoksa giriş sayfasına yönlendir
    header("Location: girisformu.php");
    exit;
}
?>