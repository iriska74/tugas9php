<?php
session_start();
include 'koneksi.php';

// Pastikan keranjang ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah kuantitas
if (isset($_GET['tambah'])) {
    $id = $_GET['tambah'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit;
}

// Kurangi kuantitas
if (isset($_GET['kurang'])) {
    $id = $_GET['kurang'];
    if (isset($_SESSION['cart'][$id]) && $_SESSION['cart'][$id] > 1) {
        $_SESSION['cart'][$id]--;
    } else {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// Hapus satu produk dari keranjang
if (isset($_GET['hapus'])) {
    $idHapus = $_GET['hapus'];
    unset($_SESSION['cart'][$idHapus]);
    header("Location: cart.php");
    exit;
}

// Simpan keranjang ke file (simulasi menyimpan)
if (isset($_GET['simpan'])) {
    file_put_contents("keranjang_tersimpan.json", json_encode($_SESSION['cart']));
    header("Location: cart.php?saved=1");
    exit;
}

// Muat kembali keranjang yang tersimpan
if (isset($_GET['muat'])) {
    if (file_exists("keranjang_tersimpan.json")) {
        $_SESSION['cart'] = json_decode(file_get_contents("keranjang_tersimpan.json"), true);
    }
    header("Location: cart.php?loaded=1");
    exit;
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
        }
    </style>
</head>
<body class="container mt-5">

    <h2>🛒 Keranjang Belanja</h2>

    <?php if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0): ?>
        <div class="alert alert-warning">Keranjang kosong. <a href="index.php">Kembali ke produk</a></div>
    <?php else: ?>
        <?php if (isset($_GET['saved'])): ?>
            <div class="alert alert-success">✅ Keranjang berhasil disimpan!</div>
        <?php elseif (isset($_GET['loaded'])): ?>
            <div class="alert alert-success">🛒 Keranjang tersimpan berhasil dimuat!</div>
        <?php endif; ?>

        <ul class="list-group mb-3">
        <?php
            foreach ($_SESSION['cart'] as $id => $qty):
                $res = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
                if ($row = mysqli_fetch_assoc($res)):
                    $subtotal = $row['harga'] * $qty;
                    $total += $subtotal;
        ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="uploads/<?php echo $row['gambar']; ?>" class="product-img" alt="<?php echo $row['nama_produk']; ?>">
                        <div>
                            <strong><?php echo $row['nama_produk']; ?></strong> <br>
                            <small>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?> / item</small>
                            <div class="mt-2">
                                <a href="cart.php?kurang=<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary">−</a>
                                <span class="mx-2 fw-bold"><?php echo $qty; ?></span>
                                <a href="cart.php?tambah=<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary">+</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="d-block">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                        <a href='cart.php?hapus=<?php echo $row['id']; ?>' class='btn btn-sm btn-danger mt-2'>🗑</a>
                    </div>
                </li>
        <?php
                endif;
            endforeach;
        ?>
        </ul>

        <div class="mb-3">
            <h5>Total yang harus dibayar:</h5>
            <div class="alert alert-info">
                <strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
            </div>
        </div>

        <a href="checkout_manual.php" class="btn btn-primary">💳 Checkout</a>
        <a href="cart.php?simpan=1" class="btn btn-success">💾 Simpan</a>
        <a href="cart.php?muat=1" class="btn btn-warning">🔄 Muat Tersimpan</a>
        <a href="hapus_keranjang.php" class="btn btn-danger">🗑 Hapus Semua</a>
        <a href="index.php" class="btn btn-secondary">← Kembali</a>
    <?php endif; ?>

</body>
</html>
