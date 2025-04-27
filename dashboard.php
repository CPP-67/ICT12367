<?php
session_start();
include 'connectDB.php';

// เช็คการเข้าสู่ระบบ
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// เพิ่มข้อมูลพนักงาน
if (isset($_POST['add_employee'])) {
    $fristname = $_POST['fristname'];
    $surname = $_POST['surname'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $phone = $_POST['phone'];

    // ใช้คำสั่ง SQL เพื่อเพิ่มข้อมูลพนักงานใหม่
    $sql = "INSERT INTO employees (fristname, surname, position, salary, phone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $fristname, $surname, $position, $salary, $phone);
    $stmt->execute();
}

// ลบข้อมูลพนักงาน
if (isset($_GET['delete'])) {
    $emID = $_GET['delete'];
    $sql = "DELETE FROM employees WHERE emID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $emID);
    $stmt->execute();
}

// ดึงข้อมูลพนักงานทั้งหมด
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <style>
        .modal-content {
            border-radius: 1rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="fw-bold mb-4 text-center">Employee Dashboard</h2>

    <!-- เมนูเพิ่มพนักงาน -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add New Employee</button>

    <!-- ตารางข้อมูลพนักงาน -->
    <table class="table table-striped table-bordered shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Surname</th>
                <th>Position</th>
                <th>Salary</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo str_pad($row['emID'], 5, '0', STR_PAD_LEFT); ?></td> <!-- ทำให้ emID เป็น 5 หลัก -->
                    <td><?php echo $row['fristname']; ?></td>
                    <td><?php echo $row['surname']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo number_format($row['salary'], 2); ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        <a href="edit.php?emID=<?php echo $row['emID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?delete=<?php echo $row['emID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal สำหรับเพิ่มพนักงาน -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="fristname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="fristname" name="fristname" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position" name="position" required>
                    </div>
                    <div class="mb-3">
                        <label for="salary" class="form-label">Salary</label>
                        <input type="number" class="form-control" id="salary" name="salary" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <button type="submit" class="btn btn-success" name="add_employee">Add Employee</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
