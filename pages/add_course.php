<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
require_once __DIR__ . '/../Classes/Courses.php';

$errors = [];

if (isset($_POST['add_course'])) {
    $course_name     = trim($_POST['course_name']);
    $course_duration = trim($_POST['course_duration']);
    $course_fee      = trim($_POST['course_fee']);

    // ---server sidw validation---
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $course_name)) {
        $errors[] = "Course name must contain only letters, numbers, and spaces.";
    }
    if (strlen($course_duration) < 2) {
        $errors[] = "Course duration must be at least 2 characters (e.g., '3 Months').";
    }
    if (!is_numeric($course_fee) || $course_fee <= 0) {
        $errors[] = "Course fee must be a positive number.";
    }

    if (empty($errors)) {
        $courseObj = new Courses();
        $added = $courseObj->addNewCourse($course_name, $course_duration, $course_fee);

        if ($added) {
            echo "<script>alert('Course added successfully.'); window.location.href='courses.php';</script>";
        } else {
            echo "<script>alert('Failed to add course!');</script>";
        }
    }
}
?>

<div class="main-content">
  <div class="container mt-0 rounded border bg-white p-4">
    <h3>Add Course</h3>

    <!-- server-side errors -->
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateCourseForm();">
      <div class="mb-3">
        <label class="form-label">Course Name</label>
        <input type="text" name="course_name" id="course_name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Course Duration</label>
        <input type="text" name="course_duration" id="course_duration" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Course Fee</label>
        <input type="number" name="course_fee" id="course_fee" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success" name="add_course">Add Course</button>
      <a href="courses.php" class="btn btn-secondary">Back</a>
    </form>
  </div>  
</div>

<script>
// --- client side validation ---
function validateCourseForm() {
    const name = document.getElementById('course_name').value.trim();
    const duration = document.getElementById('course_duration').value.trim();
    const fee = document.getElementById('course_fee').value.trim();

    if (!/^[a-zA-Z0-9\s]+$/.test(name)) {
        alert("Course name must contain only letters, numbers, and spaces.");
        return false;
    }
    if (duration.length < 2) {
        alert("Course duration must be at least 2 characters (e.g., '3 Months').");
        return false;
    }
    if (isNaN(fee) || fee <= 0) {
        alert("Course fee must be a positive number.");
        return false;
    }
    return true;
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>