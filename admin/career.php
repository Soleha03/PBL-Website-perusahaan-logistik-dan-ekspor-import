<?php
session_start();
require 'koneksi.php'; // sesuaikan path kalau perlu

// --- CHECK LOGIN ---
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$username = $_SESSION['username'];

// --- AMBIL DATA ADMIN DARI DB ---
$sql = "SELECT * FROM admin WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result || mysqli_num_rows($result) === 0) {
    echo "Data admin tidak ditemukan.";
    exit();
}
$admin = mysqli_fetch_assoc($result);

// --- PROSES UPDATE JIKA POST ---
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama'] ?? $admin['nama']);
    $email = trim($_POST['email'] ?? $admin['email']);
    $wa    = trim($_POST['wa'] ?? $admin['wa'] ?? '');

    $newImage = $admin['gambar'] ?? null;

    // Upload file jika ada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg','jpeg'];
            $size = $_FILES['foto']['size'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if ($size <= 500000 && in_array($ext, $allowed)) {
                // Buat folder img kalau belum ada
                if (!is_dir('img')) {
                    mkdir('img', 0755, true);
                }
                $newName = 'img_' . time() . '.' . $ext;
                $target = 'img/' . $newName;
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    // hapus foto lama jika ada dan bukan default
                    if (!empty($admin['gambar']) && file_exists('img/' . $admin['gambar'])) {
                        @unlink('img/' . $admin['gambar']);
                    }
                    $newImage = $newName;
                } else {
                    $error = 'Gagal menyimpan file foto.';
                }
            } else {
                $error = 'Foto harus berformat JPG/JPEG dan ukuran maksimum 500KB.';
            }
        } else {
            $error = 'Terjadi error saat upload (kode: '.$_FILES['foto']['error'].').';
        }
    }

    // Jika tidak ada error -> update DB
    if (empty($error)) {
        if (!empty($newImage) && $newImage !== ($admin['gambar'] ?? null)) {
            $sqlUpd = "UPDATE admin SET nama=?, email=?, wa=?, gambar=? WHERE username=?";
            $stmt = mysqli_prepare($koneksi, $sqlUpd);
            mysqli_stmt_bind_param($stmt, "sssss", $nama, $email, $wa, $newImage, $username);
        } else {
            $sqlUpd = "UPDATE admin SET nama=?, email=?, wa=? WHERE username=?";
            $stmt = mysqli_prepare($koneksi, $sqlUpd);
            mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $wa, $username);
        }

        if (mysqli_stmt_execute($stmt)) {
            // update session nama agar topbar langsung berubah
            $_SESSION['nama'] = $nama;
            header('Location: profile.php?success=1');
            exit();
        } else {
            $error = 'Gagal update ke database: ' . mysqli_error($koneksi);
        }
    }
}

// Jika redirect sukses, ambil ulang data (untuk menampilkan yang baru)
if (isset($_GET['success'])) {
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) $admin = mysqli_fetch_assoc($result);
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Profile - Great Group Indonesia</title>
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">
<style>
    .form-group { margin-bottom: 1rem; }
    .img-preview { width:150px; height:150px; object-fit:cover; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
  <!-- SIDEBAR (sama seperti filemu) -->
  <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- ... brand & links ... (boleh pakai bagian sidebar yang sama) -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
      <div class="sidebar-brand-icon"><img src="img/logog.png" style="width:50px;height:50px;"></div>
      <div class="sidebar-brand-text">Great Group Indonesia</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active"><a class="nav-link" href="index.php"><i class="fa fa-home"></i><span>Home</span></a></li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Company Info -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCompany"
                    aria-expanded="false" aria-controls="collapseCompany">
                    <i class="fas fa-hospital"></i>
                    <span>Company Info</span>
                </a>
                <div id="collapseCompany" class="collapse" aria-labelledby="headingCompany" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="business.php">Business</a>
                        <a class="collapse-item" href="news.php">News</a>
                        <a class="collapse-item" href="career.php">Career</a>
                    </div>
                </div>
            </li>

            <!-- Projects -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProjects"
                    aria-expanded="false" aria-controls="collapseProjects">
                    <i class="fa fa-industry"></i>
                    <span>Projects</span>
                </a>
                <div id="collapseProjects" class="collapse" aria-labelledby="headingProjects" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="industry.php">Industry</a>
                        <a class="collapse-item" href="trading.php">Trading</a>
                        <a class="collapse-item" href="logistic.php">Logistic and Shipping</a>
                    </div>
                </div>
            </li>

            <!-- === Admin === -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Admin</div>

        <!-- Edit Profile -->
        <li class="nav-item">
            <a class="nav-link" href="profile.php">
                <i class="fas fa-user-edit"></i>
                <span>Edit Profile</span>
            </a>
        </li>

        <!-- Tambah Admin -->
        <li class="nav-item">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-users-cog"></i>
                <span>Add Admin</span>
            </a>
        </li>

        <!-- === END SECTION === -->
  </ul>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- TOPBAR -->
      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
        <ul class="navbar-nav ml-auto">
          <div class="topbar-divider d-none d-sm-block"></div>
          <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['nama'] ?? $admin['nama']); ?></span>
              <?php if (!empty($admin['gambar']) && file_exists('img/' . $admin['gambar'])): ?>
                <img class="img-profile rounded-circle" src="<?php echo 'img/' . htmlspecialchars($admin['gambar']); ?>" style="width:40px;height:40px;object-fit:cover;">
              <?php else: ?>
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" style="width:40px;height:40px;object-fit:cover;">
              <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a>
            </div>
          </li>
        </ul>
      </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Career</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Last Education</th>
                                                <th>Experience</th>
                                                <th>Department</th>
                                                <th>CV File</th>
                                                <th>Created At</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        require('koneksi.php');
                                        $sql  = "SELECT * FROM lamaran";
                                        $query  = mysqli_query($koneksi, $sql);
                                        $no=0;
                                        while ($data = mysqli_fetch_array($query)){
                                        $no++;
                                        
                                        // Perbaikan path file
                                        $filename = $data["cv"];
                                        $file_path = "file/" . $filename; 
                                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                        
                                        // Cek apakah file exists
                                        $file_exists = file_exists($file_path);
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo htmlspecialchars($data["nama_lengkap"]); ?></td>
                                                <td><?php echo htmlspecialchars($data["email"]); ?></td>
                                                <td><?php echo htmlspecialchars($data["no_telp"]); ?></td>
                                                <td><?php echo htmlspecialchars($data["pendidikan_terakhir"]); ?></td>
                                                <td><?php echo htmlspecialchars($data["pengalaman"]); ?></td>
                                                <td><?php echo htmlspecialchars($data["jurusan"]); ?></td>
                                                <!-- Kolom CV: tampilkan nama file -->
                                                <td>
                                                    <?php if($file_exists): ?>
                                                        <span class="text-success"><?php echo htmlspecialchars($filename); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-danger"><?php echo htmlspecialchars($filename); ?> (Not Found)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $data["created_at"]; ?></td>
<!-- Kolom Aksi: tombol Preview & Download -->
<td>
<?php if ($file_exists && in_array($ext, ['pdf', 'doc', 'docx'])): ?>
    <?php if ($ext === 'pdf'): ?>
        <a href="file/<?php echo rawurlencode($filename); ?>" 
           target="_blank" 
           class="btn btn-sm btn-primary">
            <i class="fas fa-eye"></i> Preview
        </a>
    <?php endif; ?>
    <a href="file/<?php echo rawurlencode($filename); ?>" 
       download 
       class="btn btn-sm btn-success">
        <i class="fas fa-download"></i> Download
    </a>
<?php else: ?>
    <span class="text-muted">File tidak tersedia</span>
<?php endif; ?>
</td>

                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; 2023. Great Group Indonesia</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <!-- Custom JavaScript for File Operations -->

    <script>
    function previewFile(filename) {
        if (!filename) {
            alert('File tidak tersedia');
            return;
        }

        // encode nama file agar spasi dan karakter khusus aman di URL
        const fileUrl = 'file/' + encodeURIComponent(filename);


        // buka di tab baru
        const newWindow = window.open(fileUrl, '_blank');

        if (!newWindow) {
            alert('Popup diblokir. Izinkan popup untuk menampilkan file.');
        }
    }

    function downloadFile(filename) {
        if (!filename) {
            alert('File tidak tersedia');
            return;
        }

    // encode nama file agar spasi dan karakter khusus aman di URL
    const fileUrl = '../file/' + encodeURIComponent(filename);

    // buat elemen <a> sementara untuk memicu download
    const a = document.createElement('a');
    a.href = fileUrl;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
        
        // Buat element anchor untuk download
        const link = document.createElement('a');
        link.href = fileUrl;
        link.download = filename;
        link.style.display = 'none';
        
        // Tambahkan ke DOM dan klik
        document.body.appendChild(link);
        link.click();
        
        // Hapus dari DOM
        document.body.removeChild(link);
        
        // Feedback ke user
        console.log('Download dimulai untuk file: ' + filename);
    }

    // Alternative download function menggunakan fetch (jika diperlukan)
    function downloadFileWithFetch(filename) {
        const fileUrl = 'file/' + filename;
        
        fetch(fileUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('File tidak ditemukan');
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = filename;
                link.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                alert('Error downloading file: ' + error.message);
            });
    }
    </script>
</body>
</html>