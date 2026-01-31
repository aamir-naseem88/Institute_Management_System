<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Classes/Fees.php';
require_once __DIR__ . '/../Classes/Enrollments.php';

$db = new Database();
$conn = $db->conn;
$feeObj = new Fees();
$enrollObj = new Enrollments();

// Get student_id from URL
$student_id = $_GET['student_id'] ?? null;
if (!$student_id) die("Student not found");

// Fetch student info
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = :sid");
$stmt->execute([':sid' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch enrolled courses for dropdown
$enrolledCourses = $enrollObj->getEnrollmentsByStudent($student_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_id = $_POST['enrollment_id'] ?? null;
    $amount_paid   = $_POST['amount_paid'] ?? null;
    $notes         = $_POST['notes'] ?? null;

    if ($enrollment_id && $amount_paid) {
        $feeObj->addFee($enrollment_id, $amount_paid, $notes);
        $_SESSION['message'] = "✅ Fee recorded successfully!";
        header("Location: collect_fee.php?student_id=$student_id");
        exit;
    } else {
        $_SESSION['message'] = "⚠ Please fill all required fields!";
    }
}
?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="main-content mt-0 rounded border bg-white p-4" style="margin-left:240px; padding:20px;">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4>Collect Fee</h4>
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

      <form method="POST">
        <div class="mb-3">
          <label for="enrollment_id" class="form-label">Select Course</label>
          <select name="enrollment_id" id="enrollment_id" class="form-select" required onchange="showCourseName()">
            <option value="">-- Choose a course --</option>
            <?php foreach ($enrolledCourses as $course): ?>
              <option 
                value="<?= htmlspecialchars($course['enrollment_id']); ?>" 
                data-course="<?= htmlspecialchars($course['course_name']); ?>">
                <?= htmlspecialchars($course['course_name']); ?> (<?= htmlspecialchars($course['enrollment_status']); ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Dynamic course name display -->
        <div class="mb-3" id="selectedCourseBox" style="display:none;">
          <p><strong>Selected Course:</strong> <span id="selectedCourseName"></span></p>
        </div>

        <div class="mb-3">
          <label for="amount_paid" class="form-label">Amount Paid</label>
          <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="notes" class="form-label">Notes</label>
          <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Record Fee</button>
        <a href="/academy_system02/index.php" class="btn btn-secondary">Cancel</a>
      </form>

      <hr>
      <h5>Fee History</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Course</th>
            <th>Amount Paid</th>
            <th>Payment Date</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $fees = $feeObj->getFeesByStudent($student_id);
          if ($fees) {
              foreach ($fees as $fee) {
                  echo "<tr>
                          <td>" . htmlspecialchars($fee['course_name']) . "</td>
                          <td>" . htmlspecialchars($fee['amount_paid']) . "</td>
                          <td>" . htmlspecialchars($fee['payment_date']) . "</td>
                          <td>" . htmlspecialchars($fee['notes']) . "</td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='4'>No fees recorded yet.</td></tr>";
          }
          ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<script>
function showCourseName() {
  const select = document.getElementById('enrollment_id');
  const selectedOption = select.options[select.selectedIndex];
  const courseName = selectedOption.getAttribute('data-course');
  if (courseName) {
    document.getElementById('selectedCourseBox').style.display = 'block';
    document.getElementById('selectedCourseName').textContent = courseName;
  } else {
    document.getElementById('selectedCourseBox').style.display = 'none';
  }
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>