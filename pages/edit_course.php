<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
require_once __DIR__ . '/../Classes/Courses.php';

// gat id from URL
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    die("Course not found!");
}
$course_id = (int) $_GET['course_id'];

$courseObj = new Courses();
$course = $courseObj->viewSingleCourse($course_id);

if (!$course) {
    die("Course not found!");
}

if (isset($_POST['update_course'])) {
    $update = $courseObj->updateCourse(
        $course_id,
        $_POST['course_name'],
        $_POST['course_duration'],
        $_POST['course_fee']
    );

       if ($update) {
        echo "<script>alert('Course updated successfully.'); window.location.href='courses.php'</script>";
    } else {
        echo "<script>alert('Failed to update course!')</script>";
    }
}
?>
<div class="main-content">
  <div class="container mt-0 rounded border bg-white p-4">
    <h3>Edit Course</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Course Name</label>
        <input type="text" name="course_name" 
               value="<?= htmlspecialchars($course['course_name']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Course Duration</label>
        <input type="text" name="course_duration" 
               value="<?= htmlspecialchars($course['course_duration']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Course Fee</label>
        <input type="number" name="course_fee" 
               value="<?= htmlspecialchars($course['course_fee']) ?>" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success" name="update_course">Update Course</button>
      <a href="./courses.php" class="btn btn-secondary">Back</a>
    </form>
  </div>  
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>