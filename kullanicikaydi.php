<?php
include("baglanti.php"); // Veritabanı bağlantı dosyası dahil ediliyor

// Kayıt ol butonuna tıklandığında işlemleri başlat
if (isset($_POST["kayitol"])) {
    // Formdan veri alma ve veritabanına yapılacak kaydı güvenli hale getirme
    $ad = mysqli_real_escape_string($baglanti, $_POST["ad"]);
    $soyad = mysqli_real_escape_string($baglanti, $_POST["soyad"]);
    $mailadresi = mysqli_real_escape_string($baglanti, $_POST["mailadresi"]);
    $dogumtarihi = mysqli_real_escape_string($baglanti, $_POST["dogumtarihi"]);
    $parola = $_POST["parola"];
    $parolatekrari = $_POST["parolatekrari"];

    // Mail adresinin zaten kayıtlı olup olmadığını kontrol ediyoruz
    $kontrolsorgu = "SELECT * FROM kullanicilar WHERE mailadresi = ?";
    $stmt = $baglanti->prepare($kontrolsorgu);
    $stmt->bind_param("s", $mailadresi); // Mail adresini parametre olarak bağla
    $stmt->execute(); // Sorguyu çalıştır
    $kontrolsonuc = $stmt->get_result(); // Sonucu al

    // Eğer mail adresi zaten kayıtlıysa hata ver ve kayıt formuna yönlendir
    if ($kontrolsonuc->num_rows > 0) {
        echo "<center><br>Bu mail adresi zaten kayitli!</center>";
        header("Refresh: 4; url=kayitformu.php");
        exit;
    } else {
        // Girilen parolaların eşleşip eşleşmediğini kontrol et
        if ($parola === $parolatekrari) {
            // Kullanıcıyı veritabanına ekleme
            $sorgu = "INSERT INTO kullanicilar (ad, soyad, mailadresi, dogumtarihi, parola) VALUES (?, ?, ?, ?, ?)";
            $stmt = $baglanti->prepare($sorgu);
            $stmt->bind_param("sssss", $ad, $soyad, $mailadresi, $dogumtarihi, $parola);

            // Kayıt işlemi başarılıysa bilgi ver ve giriş sayfasına yönlendir
            if ($stmt->execute()) {
                echo "<center><br>Kayit isteği alindi. Yönetici onayi bekliyorsunuz.<br>Giriş sayfasina yönlendiriliyorsunuz.</center>";
                header("Refresh: 4; url=girisformu.php");
                exit;
            } else {
                // Kayıt başarısızsa hata mesajı göster ve kayıt formuna yönlendir
                echo "<center><br>Kayit işlemi başarisiz!</center>" . $baglanti->error;
                header("Refresh: 4; url=kayitformu.php");
                exit;
            }
        } else {
            // Parolalar eşleşmiyorsa hata ver ve kayıt formuna yönlendir
            echo "<center><br>Parolalar eşleşmiyor!</center>";
            header("Refresh: 4; url=kayitformu.php");
            exit;
        }
    }
} else {
    // Form gönderilmemişse hata mesajı göster
    echo "Form gönderilmedi!";
    exit;
}
?>
