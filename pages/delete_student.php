<?php
require_once __DIR__ . '/../Classes/Students.php';
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
define('BASE_URL', '/academy_system02/');

$studentsObj = new Students();

// get student ID from URL
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    die("No student ID provided.");
}
$student_id = (int) $_GET['student_id'];

// fetch student details
$student = $studentsObj->getStudentById($student_id);
if (!$student) {
    die("Student not found.");
}

// deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleted = $studentsObj->deleteStudent($student_id);

    if ($deleted) {
        echo "<script>alert('Student deleted successfully!'); window.location.href=' ".BASE_URL. "index.php';</script>";
    } else {
        echo "<script>alert('Failed to delete student.')</script>";
    }
}
?>

<div class="main-content">
  <div class="container mt-0 rounded border bg-white p-4">
    <h3>Delete Student</h3>
    <p>Are you sure you want to delete <strong><?= htmlspecialchars($student['student_name']) ?></strong>?</p>

    <form method="POST" class="d-flex gap-2">
      <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Delete Student
      </button>
      <a href="../index.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Cancel
      </a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>