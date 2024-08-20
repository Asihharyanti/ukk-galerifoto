<?php
session_start();
include '../config/koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda belum Login!');
    location.href='../index.php';
    </script>";
    exit();
}

// Include your connection file
include '../config/koneksi.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $namaalbum = mysqli_real_escape_string($koneksi, $_POST['namaalbum']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    
    $sql = "INSERT INTO albums (namaalbum, deskripsi) VALUES ('$namaalbum', '$deskripsi')";
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
        alert('Album berhasil ditambahkan');
        location.href='album.php';
        </script>";
    } else {
        echo "<script>
        alert('Gagal menambahkan album');
        location.href='tambah_album.php';
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Gallery Foto</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="index.php">Website Gallery Foto</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mt-2" id="navbarNavAltMarkup">
      <div class="navbar-nav me-auto">
        <a href="home.php" class="nav-link">Home</a>
        <a href="album.php" class="nav-link">Album</a>
        <a href="foto.php" class="nav-link">Foto</a>
      </div>
      
      <a href="../config/aksi_logout.php" class="btn btn-outline-danger m-1">Keluar</a>
    </div>
  </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card mt-2">
                <div class="card-header">Tambah Album</div>
                <div class="card-body">
                    <form action="../config/aksi_album.php" method="POST">
                        <label for="namaalbum" class="form-label">Nama Album</label>
                        <input type="text" name="namaalbum" id="namaalbum" class="form-control" required>

                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" required></textarea>

                        <button type="submit" class="btn btn-primary mt-2" name="tambah">Tambah Data</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
          <div class="card mt-2">
            <div class="card-header">Data Album</div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nama Album</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $userid = $_SESSION['userid'];
                  $result = mysqli_query($koneksi, "SELECT * FROM album");


                  while($data = mysqli_fetch_array($result)){
                    ?>
                  
                  
                  
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $data['namaalbum'] ?></td>
                    <td><?php echo $data['deskripsi'] ?></td>
                    <td><?php echo $data['tanggalbuat'] ?></td>
                    <td>
                      <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data['albumid'] ?>">
 Edit
</button>


<div class="modal fade" id="edit<?php echo $data['albumid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../config/aksi_album.php" method="POST">
          <input type="hideen" name="albumid" value="<?php echo $data['albumid']?>">
                        <label for="namaalbum" class="form-label">Nama Album</label>
                        <input type="text" name="namaalbum" id="namaalbum" value="<?php echo $data['namaalbum'] ?>" class="form-control" required>
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" required>
                          <?php echo $data['deskripsi']; ?>
                  </textarea>
        
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit" class="btn btn-primary">
        Edit Data</button>
        </form>
      </div>
    </div>
  </div>
</div>

<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $data['albumid'] ?>">
 Hapus
</button>


<div class="modal fade" id="hapus<?php echo $data['albumid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../config/aksi_album.php" method="POST">
          <input type="hideen" name="albumid" value="<?php echo $data['albumid']?>">
          Apakah anda yakin akan menghapus data <strong> <?php echo $data['namaalbum'] ?></strong> ?
          
        </div>
        <div class="modal-footer">
          <button type="submit" name="hapus" class="btn btn-primary">Hapus Data</button>
                  </form>
        </div>
    </div>
  </div>
</div>

                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>

<footer class="d-flex justify-content-center border-top mt-3 bg-light">
    <p>&copy; UKK Gallery Foto | ashar</p>
</footer>

<script type="text/javascript" src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
