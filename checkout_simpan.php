<?php
session_start();
include 'koneksi.php';

// Pastikan keranjang tidak kosong
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<p>Keranjang kosong. <a href='index.php'>Kembali</a></p>";
    exit;
}

$total = 0;
$produkList = [];

foreach ($_SESSION['cart'] as $id => $qty) {
    $res = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
    if ($row = mysqli_fetch_assoc($res)) {
        $subtotal = (int)$row['harga'] * $qty;
        $total += $subtotal;
        $produkList[] = $row['nama_produk'] . " x$qty (Rp " . number_format($subtotal, 0, ',', '.') . ")";
    }
}

// Diskon otomatis 10% jika total lebih dari 1 juta
$diskon = 0;
if ($total > 1000000) {
    $diskon = 0.1 * $total;
    $total -= $diskon;
}

$produkStr = implode(", ", $produkList);
$tanggal = date('Y-m-d H:i:s');

// Simpan ke database checkout
$query = "INSERT INTO checkout (produk, total, tanggal) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sis", $produkStr, $total, $tanggal);
mysqli_stmt_execute($stmt);

// Kosongkan keranjang
unset($_SESSION['cart']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Sukses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="alert alert-success">
        <h4>✅ Checkout berhasil!</h4>
        <p>Total: <strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong></p>
        <?php if ($diskon > 0): ?>
        <p class="text-success">🎉 Diskon 10% telah diterapkan: Rp <?php echo number_format($diskon, 0, ',', '.'); ?></p>
        <?php endif; ?>
        <p>Produk: <?php echo $produkStr; ?></p>
        <p>Data pembelian telah disimpan.</p>
    </div>
    <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
</body>
</html>
