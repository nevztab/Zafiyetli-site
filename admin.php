<?php
// BU DOSYANIN ADINI "admin.php" OLARAK KAYDET

// DİKKAT: Diğer sunucunun (VDS) IP adresini buraya yaz!
$hedef_vds_ip = "78.135.85.204"; 

$hata_mesaji = "";

if ($_POST) {
    $kullanici = $_POST['kullanici_adi'];
    
    // Basit bir SQL Injection Simülasyonu
    // Eğer gelen veride kesme işareti (') veya "OR" mantığı varsa zafiyeti tetikle.
    $zafiyet_tetikleyiciler = ["'", '"', " or ", " OR ", "1=1"];
    $zafiyet_var_mi = false;
    
    foreach($zafiyet_tetikleyiciler as $t) {
        if (strpos($kullanici, $t) !== false) {
            $zafiyet_var_mi = true;
            break;
        }
    }

    if ($zafiyet_var_mi) {
        // --- ZAFİYET TETİKLENDİ! (IP İFŞASI) ---
        // Gerçekçi bir veritabanı bağlantı hatası taklidi yapıyoruz.
        $hata_mesaji = "
        <div style='background-color: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeeba; font-family: monospace; text-align: left;'>
            <strong>CRITICAL ERROR:</strong> Database connection failed.<br><br>
            Technical Details:<br>
            Creating socket to <strong>Host: {$hedef_vds_ip}</strong> on port 3306... Connection refused.<br>
            User: 'root'@'{$hedef_vds_ip}' - Access Denied.<br>
            <br>
            <i>SQL STATE[HY000] [2002] Connection refused in /var/www/html/admin.php on line 45</i>
        </div>
        ";
    } else {
        // Normal hatalı giriş denemesi
        $hata_mesaji = "<div style='color: red;'>Hatalı kullanıcı adı veya şifre.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetim Paneli Giriş</title>
    <style>
        body { background-color: #2c3e50; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; }
        .login-kutusu { background: white; padding: 30px; border-radius: 8px; width: 350px; text-align: center; box-shadow: 0px 0px 15px rgba(0,0,0,0.2); }
        input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 96%; padding: 10px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #2980b9; }
    </style>
</head>
<body>

<div class="login-kutusu">
    <h2>Admin Girişi</h2>
    <?=$hata_mesaji?>
    <form method="POST">
        <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
        <input type="password" name="sifre" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
    </form>
</div>

</body>
</html>