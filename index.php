<?php
include('koneksi.php');

// Tangkap filter kategori jika ada
$filter = "";
if (isset($_GET['kategori']) && $_GET['kategori'] !== "") {
    $kategori = $_GET['kategori'];
    $filter = "WHERE kategori = '$kategori'";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body class="container mt-5">

    <h2 class="mb-4">🛍️ Daftar Produk</h2>
    <a href="add_product.php" class="btn btn-success mb-3">+ Tambah Produk</a>

    <!-- 🔍 Filter Kategori -->
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="kategori" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Elektronik" <?php if(isset($kategori) && $kategori == 'Elektronik') echo 'selected'; ?>>Elektronik</option>
                    <option value="Fashion" <?php if(isset($kategori) && $kategori == 'Fashion') echo 'selected'; ?>>Fashion</option>
                    <option value="Makanan" <?php if(isset($kategori) && $kategori == 'Makanan') echo 'selected'; ?>>Makanan</option>
                    <option value="Lainnya" <?php if(isset($kategori) && $kategori == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <a href="index.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- 💳 Daftar Produk -->
    <div class="row">
        <?php
        $query = "SELECT * FROM produk $filter ORDER BY id DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="uploads/<?php echo $row['gambar']; ?>" class="card-img-top" alt="<?php echo $row['nama_produk']; ?>">
                    <div class="card-body p-3">
                        <h5 class="card-title"><?php echo $row['nama_produk']; ?></h5>
                        <p class="card-text">
                            <strong>Kategori:</strong> <?php echo $row['kategori']; ?><br>
                            <strong>Harga:</strong> Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                        </p>
                        <div class="d-flex gap-2 mt-3">
<a href="cart.php?tambah=<?php echo $row['id']; ?>" class="btn btn-success btn-sm flex-fill">+ Keranjang</a>

                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm flex-fill">✏️ Edit</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm flex-fill"
                               onclick="return confirm('Yakin ingin menghapus produk ini?')">🗑️ Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<p>Tidak ada produk ditemukan.</p>";
        }
        ?>
    </div>

</body>
</html>
