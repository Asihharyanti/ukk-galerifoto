<?php
session_start();
include 'koneksi.php';

if(isset($_POST['tambah'])) {
    $judulfoto = $_POST['judulfoto'];
    $deskripsifoto = $_POST['deskripsifoto'];
    $tanggalunggah = date('Y-m-d');  // Perbaiki format tanggal
    $albumid = $_POST['albumid'];
    $userid = $_SESSION['userid'];
    $foto = $_FILES['lokasifile']['name'];
    $tmp = $_FILES['lokasifile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namafoto = rand().'-'.$foto;

    // Pindahkan file ke lokasi yang ditentukan
    move_uploaded_file($tmp, $lokasi.$namafoto);

    // Perbaiki query SQL untuk menyertakan nama kolom dan nilai-nilainya
    $sql = mysqli_query($koneksi, "INSERT INTO foto (judulfoto, deskripsifoto, tanggalunggah, albumid, userid, lokasifile) VALUES('$judulfoto', '$deskripsifoto', '$tanggalunggah', '$albumid', '$userid', '$namafoto')");

    if ($sql) {
        echo "<script>
        alert('Data berhasil disimpan!');
        location.href='../admin/foto.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

if (isset($_POST['edit'])) {
    $fotoid = $_POST['fotoid'];
    $judulfoto = $_POST['judulfoto'];
    $deskripsifoto = $_POST['deskripsifoto'];

    // Update judul dan deskripsi foto
    $update_sql = "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto' WHERE fotoid='$fotoid'";
    
    if (isset($_FILES['lokasifile']) && $_FILES['lokasifile']['name'] != '') {
        $foto = $_FILES['lokasifile']['name'];
        $tmp = $_FILES['lokasifile']['tmp_name'];
        $lokasi = '../assets/img/';
        $namafoto = rand() . '-' . $foto;

        move_uploaded_file($tmp, $lokasi . $namafoto);

        // Hapus gambar lama jika ada
        $result = mysqli_query($koneksi, "SELECT * FROM foto WHERE fotoid='$fotoid'");
        $data = mysqli_fetch_array($result);
        $old_image = $data['lokasifile'];
        if ($old_image && file_exists($lokasi . $old_image)) {
            unlink($lokasi . $old_image);
        }

        // Update path gambar baru
        $update_sql = "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', lokasifile='$namafoto' WHERE fotoid='$fotoid'";
    }

    $update_query = mysqli_query($koneksi, $update_sql);

    if ($update_query) {
        echo "<script>
        alert('Data berhasil diperbarui!');
        location.href='../admin/foto.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

if (isset($_POST['hapus'])) {
    $fotoid = $_POST['fotoid'];

    // Mengambil data foto untuk menghapus file fisik
    $result = mysqli_query($koneksi, "SELECT lokasifile FROM foto WHERE fotoid='$fotoid'");
    $data = mysqli_fetch_array($result);
    $lokasi = '../assets/img/';
    $old_image = $data['lokasifile'];

    if ($old_image && file_exists($lokasi . $old_image)) {
        unlink($lokasi . $old_image);
    }

    // Hapus data dari database
    $delete_query = mysqli_query($koneksi, "DELETE FROM foto WHERE fotoid='$fotoid'");

    if ($delete_query) {
        echo "<script>
        alert('Data berhasil dihapus!');
        location.href='../admin/foto.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

?>