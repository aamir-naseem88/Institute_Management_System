<?php
require_once __DIR__ . '/../Classes/Students.php';
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
define('BASE_URL', '/academy_system02/');

$studentsObj = new Students();

// Step 1: Get student ID from URL
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    die("No student ID provided.");
}

$student_id = (int) $_GET['student_id'];

// Step 2: Fetch student details
$student = $studentsObj->getStudentById($student_id);
if (!$student) {
    die("Student not found.");
}

// Step 3: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name  = $_POST['student_name'];
    $father_name   = $_POST['father_name'];
    $gender        = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $phone         = $_POST['phone'];
    $address       = $_POST['address'];

    $updated = $studentsObj->updateStudent(
        $student_id,
        $student_name,
        $father_name,
        $gender,
        $date_of_birth,
        $phone,
        $address
    );

if ($updated) {
    echo "<script>
        alert('Student added successfully!');
        window.location.href='" . BASE_URL . "pages/edit_student.php?student_id=" . $student_id . "';
    </script>";
} else {
    echo "<script>
        alert('Failed to add student.');
    </script>";
}
}
?>

<div class="main-content">
<div class="container mt-0 rounded border bg-white p-4">
    <h3>Edit Student</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="student_name" class="form-control"
                   value="<?= htmlspecialchars($student['student_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Father Name</label>
            <input type="text" name="father_name" class="form-control"
                   value="<?= htmlspecialchars($student['father_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-control" required>
                <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control"
                   value="<?= htmlspecialchars($student['date_of_birth']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control"
                   value="<?= htmlspecialchars($student['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($student['address']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="<?= BASE_URL . 'index.php' ?>" class="btn btn-secondary">Back</a>
    </form>
</div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>