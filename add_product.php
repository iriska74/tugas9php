<?php
include('koneksi.php');

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Buat folder uploads jika belum ada
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Pindahkan gambar ke folder uploads
    move_uploaded_file($tmp, "uploads/" . $gambar);

    // Simpan data ke database
    $sql = "INSERT INTO produk (nama_produk, kategori, deskripsi, harga, gambar)
            VALUES ('$nama', '$kategori', '$deskripsi', '$harga', '$gambar')";

    mysqli_query($conn, $sql);

    echo "<div class='alert alert-success mt-3'>Produk berhasil ditambahkan.</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4">➕ Tambah Produk</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← Kembali ke Beranda</a>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Produk</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Fashion">Fashion</option>
                <option value="Furniture">Furniture</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Produk</label>
            <input type="file" name="gambar" class="form-control" required>
        </div>

        <button type="submit" name="submit" class="btn btn-success">Simpan Produk</button>
    </form>

</body>
</html>
