<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Great Group Indonesia</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="admin/img/logog.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">

  <!-- =======================================================
    Theme Name: BizPage
    Theme URL: https://bootstrapmade.com/bizpage-bootstrap-business-template/
    Author: BootstrapMade.com
    License: https://bootstrapmade.com/license/
  ======================================================= -->
</head>

<body>

  <!--==========================
    Header
  ============================-->
  <header id="header">
    <div class="container-fluid">
      <div id="logo" class="pull-left">
        <h1>
          <a href="#intro" class="scrollto"></a>
          <img src="img/logog.png" style="width: 50px; height: 50px;" alt="Logo">
        </h1>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="index.php">Home</a></li>
          <li><a href="index.php #about">About</a></li>
          <li><a href="index.php #Suitainability">Sustainability</a></li>
          <li><a href="index.php #team">Our Business</a></li>
          <li><a href="index.php #news">News</a></li>
          <li><a href="index.php #career">Career</a></li>
          <li><a href="index.php #address">Contact Us</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->

  <!--==========================
    Industry Section
  ============================-->
<section id="industry" style="padding: 60px 0; margin-top: 80px;">
  <div class="container">
    <div class="section-header text-center wow fadeInUp" style="margin-bottom: 40px;">
      <h3 class="section-title">Industry</h3>
      <h1 class="section-title">Welcome To Great Group Indonesia <br>
where we harmonize industry-leading practices with environmental and social responsibility</h1>
      <p class="section-description">"As a key player in the palm oil and derivatives, agricultural, and logistc sectors we are committed to delivering value through innovative products, sustainable solutions, and unparalleled customer service." <br>
" We embrace innovation, sustainability, and customer satisfaction as the pillars of our success."</p>
    </div>

    <div class="row">
      <?php
        require('admin/koneksi.php');
        $sql = "SELECT * FROM industry ORDER BY created_at DESC LIMIT 6";
        $query = mysqli_query($koneksi, $sql);
        while ($data = mysqli_fetch_array($query)) {
      ?>
        <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp">
          <div class="card h-100 shadow-sm border-0">
            <img src="admin/img/<?php echo $data['gambar']; ?>" 
                 class="card-img-top" 
                 alt="News Image" 
                 style="height:200px; object-fit:cover;">
            <div class="card-body text-justify">
              <h5 class="card-title"><?php echo $data["judul"]; ?></h5>
              <p class="card-text"><?php echo substr($data["isi_teks"], 0, 150) . '...'; ?></p>
            </div>
            <div class="card-footer bg-white border-0">
              <small class="text-muted"><?php echo date('M d, Y', strtotime($data['created_at'])); ?></small>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>


  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>Great Group Indonesia</strong>. All Rights Reserved
      </div>
    </div>
  </footer><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/isotope/isotope.pkgd.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/touchSwipe/jquery.touchSwipe.min.js"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

</body>
</html>
