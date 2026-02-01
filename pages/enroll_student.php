<?php
session_start();
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Classes/Enrollments.php';
define('BASE_URL', '/academy_system02/');

$db = new Database();
$conn = $db->conn;
$enrollObj = new Enrollments();

// student_id from URL
$student_id = $_GET['student_id'] ?? null;
if (!$student_id) die("Student not found");

// student datails
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = :sid");
$stmt->execute([':sid' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// all courses
$courses = $enrollObj->getAllCourses();

// new enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'] ?? null;
    $status    = $_POST['enrollment_status'] ?? 'Active';

    if ($course_id) {
        $result = $enrollObj->enrollStudent($student_id, $course_id, $status);
        $_SESSION['message'] = $result['message'];
        header("Location: enroll_student.php?student_id=$student_id");
        exit;
    }
}

// status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_enrollment_id'])) {
    $enrollment_id = $_POST['update_enrollment_id'];
    $new_status    = $_POST['new_status'];

    if ($enrollment_id && $new_status) {
        $enrollObj->updateEnrollmentStatus($enrollment_id, $new_status);
        $_SESSION['message'] = "✅ Enrollment status updated!";
        header("Location: enroll_student.php?student_id=$student_id");
        exit;
    }
}
?>

<div class="main-content bg-light">
  <div class="card mt-0 rounded border bg-white p-4">
    <div class="card-header bg-primary text-white">
      <h4>Enroll Student</h4>
    </div>
    <div class="card-body">

      <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($_SESSION['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
      <?php endif; ?>

      <h5 class="mb-3">Student Information</h5>
      <p><strong>Name:</strong> <?= htmlspecialchars($student['student_name']); ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($student['phone']); ?></p>

      <!-- enrollment Form -->
      <form method="POST">
        <div class="mb-3">
          <label for="course_id" class="form-label">Select Course</label>
          <select name="course_id" id="course_id" class="form-select" required>
            <option value="">-- Choose a course --</option>
            <?php foreach ($courses as $course): ?>
              <option value="<?= $course['course_id']; ?>">
                <?= htmlspecialchars($course['course_name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="enrollment_status" class="form-label">Enrollment Status</label>
          <select name="enrollment_status" id="enrollment_status" class="form-select" required>
            <option value="Active">Active</option>
            <option value="Completed">Completed</option>
            <option value="Withdrawn">Withdrawn</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success">Enroll Student</button>
        <a href="../index.php" class="btn btn-secondary">Cancel</a>
      </form>

      <hr>
      <h5>Already Enrolled Courses</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Course</th>
            <th>Status</th>
            <th>Enrollment Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $enrolledCourses = $enrollObj->getEnrollmentsByStudent($student_id);
          if ($enrolledCourses) {
              foreach ($enrolledCourses as $enrolled) {
                  echo "<tr>
                          <td>" . htmlspecialchars($enrolled['course_name']) . "</td>
                          <td>" . htmlspecialchars($enrolled['enrollment_status']) . "</td>
                          <td>" . htmlspecialchars($enrolled['enrollment_date']) . "</td>
                          <td>
                            <form method='POST' style='display:inline-block;'>
                              <input type='hidden' name='update_enrollment_id' value='" . $enrolled['enrollment_id'] . "'>
                              <select name='new_status' class='form-select form-select-sm d-inline-block' style='width:auto;'>
                                <option value='Active'" . ($enrolled['enrollment_status']=='Active'?' selected':'') . ">Active</option>
                                <option value='Completed'" . ($enrolled['enrollment_status']=='Completed'?' selected':'') . ">Completed</option>
                                <option value='Withdrawn'" . ($enrolled['enrollment_status']=='Withdrawn'?' selected':'') . ">Withdrawn</option>
                              </select>
                              <button type='submit' class='btn btn-sm btn-primary'>Update</button>
                            </form>
                          </td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='4'>No courses enrolled yet.</td></tr>";
          }
          ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>