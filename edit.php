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
    <style>
        .modal-content {
            border-radius: 1rem;
        }
    </style>
</head>
<body class="bg-light">

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
