<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Classes/Enrollments.php';
require_once __DIR__ . '/../Classes/Fees.php';

$db = new Database();
$conn = $db->conn;
$enrollObj = new Enrollments();
$feeObj = new Fees();

// student_id from URL
$student_id = $_GET['student_id'] ?? null;
if (!$student_id) die("Student not found");

// details
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = :sid");
$stmt->execute([':sid' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// enrolled courses with course_id and course_fee
$enrolledCourses = $enrollObj->getEnrollmentsByStudent($student_id);
?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="main-content">
  <div class="card rounded border mt-0">
    <div class="card-header bg-secondary text-white">
      <h4>Student Report</h4>
    </div>
    <div class="card-body">

      <!-- personal information -->
      <h5>1. Personal Information</h5>
      <table class="table table-bordered">
        <tr><th>ID</th><td><?= htmlspecialchars($student['student_id']); ?></td></tr>
        <tr><th>Name</th><td><?= htmlspecialchars($student['student_name']); ?></td></tr>
        <tr><th>Father Name</th><td><?= htmlspecialchars($student['father_name']); ?></td></tr>
        <tr><th>Gender</th><td><?= htmlspecialchars($student['gender']); ?></td></tr>
        <tr><th>Date of Birth</th><td><?= htmlspecialchars($student['date_of_birth']); ?></td></tr>
        <tr><th>Phone</th><td><?= htmlspecialchars($student['phone']); ?></td></tr>
        <tr><th>Address</th><td><?= htmlspecialchars($student['address']); ?></td></tr>
      </table>

      <!-- enrolled courses -->
      <h5 class="mt-4">2. Enrolled Courses</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Course</th>
            <th>Status</th>
            <th>Enrollment Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($enrolledCourses): ?>
            <?php foreach ($enrolledCourses as $course): ?>
              <tr>
                <td><?= htmlspecialchars($course['course_name']); ?></td>
                <td>
                  <span class="badge 
                    <?= $course['enrollment_status'] === 'Completed' ? 'bg-primary' : 
                       ($course['enrollment_status'] === 'Withdrawn' ? 'bg-danger' : 'bg-success'); ?>">
                    <?= htmlspecialchars($course['enrollment_status']); ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($course['enrollment_date']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="3">No courses enrolled.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- fee records -->
      <h5 class="mt-4">3. Fee Records (Course‑wise)</h5>
      <?php if ($enrolledCourses): ?>
        <?php foreach ($enrolledCourses as $course): ?>
          <h6 class="mt-4">
            <?= htmlspecialchars($course['course_name']); ?>
            <span class="badge 
              <?= $course['enrollment_status'] === 'Completed' ? 'bg-primary' : 
                 ($course['enrollment_status'] === 'Withdrawn' ? 'bg-danger' : 'bg-success'); ?>">
              <?= htmlspecialchars($course['enrollment_status']); ?>
            </span>
          </h6>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $fees = $feeObj->getFeesByEnrollment($course['enrollment_id']);
              $totalPaid = 0;
              if ($fees) {
                  foreach ($fees as $fee) {
                      $totalPaid += $fee['amount_paid'];
                      echo "<tr>
                              <td>" . htmlspecialchars($fee['amount_paid']) . "</td>
                              <td>" . htmlspecialchars($fee['payment_date']) . "</td>
                              <td>" . htmlspecialchars($fee['notes']) . "</td>
                            </tr>";
                  }
              } else {
                  echo "<tr><td colspan='3'>No fees recorded yet.</td></tr>";
              }

              $courseFee = $course['course_fee'];
              $balance = $courseFee - $totalPaid;
              ?>
            </tbody>
          </table>
          <div class="mb-3">
            <strong>Total Payable Fee:</strong> <span class="text-dark"><?= number_format($courseFee, 2); ?></span><br>
            <strong>Total Paid:</strong> <span class="text-success"><?= number_format($totalPaid, 2); ?></span><br>
            <strong>Balance:</strong> 
            <span class="<?= $balance > 0 ? 'text-danger' : 'text-success'; ?>">
              <?= number_format($balance, 2); ?>
            </span>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No fee records available.</p>
      <?php endif; ?>
      <div class="bg-white border p-2 text-center">
  
  <button class="btn btn-primary" onclick="window.print()">Print Report</button>

  <button class="btn btn-secondary mx-2" onclick="window.location.href='/academy_system02/index.php'">
    Cancel
  </button>
</div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>