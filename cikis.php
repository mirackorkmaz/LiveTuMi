<?php
session_start();
session_destroy(); // Oturumu sonlandır
header("Location: girisformu.php"); // Giriş sayfasına yönlendir
exit;
?>