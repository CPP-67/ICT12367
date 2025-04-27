<?php
session_start();
include 'connectDB.php';

// เช็คการเข้าสู่ระบบ
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบว่าได้ส่ง emID มาแล้วหรือไม่
if (isset($_GET['emID'])) {
    $emID = $_GET['emID'];

    // ดึงข้อมูลพนักงานจากฐานข้อมูล
    $sql = "SELECT * FROM employees WHERE emID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $emID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Employee not found!";
        exit();
    }
} else {
    echo "Invalid request!";
    exit();
}

// อัปเดตข้อมูลพนักงาน
if (isset($_POST['edit_employee'])) {
    $fristname = $_POST['fristname'];
    $surname = $_POST['surname'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $phone = $_POST['phone'];

    $sql = "UPDATE employees SET fristname = ?, surname = ?, position = ?, salary = ?, phone = ? WHERE emID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $fristname, $surname, $position, $salary, $phone, $emID);
    $stmt->execute();

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />




    <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="description" content="">
  <meta name="keywords" content="">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

    <style>
        .modal-content {
            border-radius: 1rem;
        }
    </style>
</head>
<body class="bg-light">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">EMPLOYEES</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Home</a></li></link>
          <li><a href="#team">Team</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>


  <section id="hero" class="hero section dark-background">
      <img src="assets/img/hero-bg-2.jpg" alt="" class="hero-bg">

      <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100">
        <div class="col-12 col-md-6 col-lg-4 mx-auto">
        <div class="container mt-5">
    <h2 class="fw-bold mb-4 text-center">Edit Employee</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="fristname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="fristname" name="fristname" value="<?php echo $row['fristname']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $row['surname']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">Position</label>
            <input type="text" class="form-control" id="position" name="position" value="<?php echo $row['position']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="salary" class="form-label">Salary</label>
            <input type="number" class="form-control" id="salary" name="salary" value="<?php echo $row['salary']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="edit_employee">Save Changes</button>
    </form>
</div>
        </div>
    </div>
</div>

      <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
        <defs>
          <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
        </defs>
        <g class="wave1">
          <use xlink:href="#wave-path" x="50" y="3"></use>
        </g>
        <g class="wave2">
          <use xlink:href="#wave-path" x="50" y="0"></use>
        </g>
        <g class="wave3">
          <use xlink:href="#wave-path" x="50" y="9"></use>
        </g>
      </svg>

    </section><!-- /Hero Section -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
