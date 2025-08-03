<?php
session_start();
include 'koneksi.php';

// Pastikan keranjang ada
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<script>alert('Keranjang kosong.'); window.location='index.php';</script>";
    exit;
}

$total = 0;
$produk_list = "";

foreach ($_SESSION['cart'] as $id => $qty) {
    $res = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
    if ($row = mysqli_fetch_assoc($res)) {
        $subtotal = $row['harga'] * $qty;
        $total += $subtotal;
        $produk_list .= "<li>{$row['nama_produk']} (Rp " . number_format($subtotal, 0, ',', '.') . ")</li>";
    }
}

$whatsapp_no = "081312345678";
$whatsapp_message = "Halo, saya sudah transfer sebesar Rp " . number_format($total, 0, ',', '.') . ". Berikut produk yang saya beli:\n" . strip_tags($produk_list);
$wa_link = "https://wa.me/62" . substr($whatsapp_no, 1) . "?text=" . urlencode($whatsapp_message);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Manual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>💳 Checkout Pembayaran Manual</h2>
    
    <div class="alert alert-info">
        <p><strong>Silakan transfer total pembayaran ke rekening berikut:</strong></p>
        <ul class="mb-0">
            <li><strong>🏦 Bank:</strong> BCA</li>
            <li><strong>🔢 No. Rekening:</strong> 12345678</li>
            <li><strong>👤 Atas Nama:</strong> Irwan</li>
            <li><strong>💰 Total:</strong> Rp <?php echo number_format($total, 0, ',', '.'); ?></li>
        </ul>
    </div>

    <h5>📦 Daftar Produk:</h5>
    <ul>
        <?php echo $produk_list; ?>
    </ul>

    <p>Setelah transfer, silakan konfirmasi pembayaran melalui WhatsApp:</p>
    <a href="<?php echo $wa_link; ?>" class="btn btn-success" target="_blank">📱 Konfirmasi via WhatsApp</a>
    <a href="index.php" class="btn btn-secondary">← Kembali ke Beranda</a>

</body>
</html>
