<?php
require_once __DIR__ . '/../Classes/Courses.php';
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
define('BASE_URL', '/academy_system02/');

$courseObj = new Courses();

// Step 1: Get course ID from URL
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    die("No course ID provided.");
}
$course_id = (int) $_GET['course_id'];

// Step 2: Fetch course details
$course = $courseObj->viewSingleCourse($course_id);
if (!$course) {
    die("Course not found.");
}

// Step 3: Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleted = $courseObj->deleteCourse($course_id);

    if ($deleted) {
        echo "<script>alert('Course deleted successfully!'); window.location.href='courses.php';</script>";
    } else {
        echo "<script>alert('Failed to delete course.')</script>";
    }
}
?>

<div class="main-content">
  <div class="container mt-0 rounded border bg-white p-4">
    <h3>Delete Course</h3>
    <p>Are you sure you want to delete <strong><?= htmlspecialchars($course['course_name']) ?></strong>?</p>

    <form method="POST" class="d-flex gap-2">
      <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Delete Course
      </button>
      <a href="courses.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Cancel
      </a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>