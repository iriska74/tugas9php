<?php
include('koneksi.php');

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "uploads/" . $gambar);
        $update = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga', gambar='$gambar' WHERE id=$id";
    } else {
        $update = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga' WHERE id=$id";
    }

    mysqli_query($conn, $update);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Edit Produk</h2>
    <a href="index.php" class="btn btn-secondary mb-3">← Kembali</a>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama" value="<?php echo $data['nama_produk']; ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="Elektronik" <?php if($data['kategori'] == 'Elektronik') echo 'selected'; ?>>Elektronik</option>
                <option value="Fashion" <?php if($data['kategori'] == 'Fashion') echo 'selected'; ?>>Fashion</option>
                <option value="Makanan" <?php if($data['kategori'] == 'Makanan') echo 'selected'; ?>>Makanan</option>
                <option value="Lainnya" <?php if($data['kategori'] == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"><?php echo $data['deskripsi']; ?></textarea>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" value="<?php echo $data['harga']; ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Gambar Produk (kosongkan jika tidak diganti)</label>
            <input type="file" name="gambar" class="form-control">
            <img src="uploads/<?php echo $data['gambar']; ?>" width="150" class="mt-2">
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</body>
</html>
