<?php
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/sidebar.php';
require_once __DIR__ . '/Classes/Students.php';
require_once __DIR__ . '/Classes/Enrollments.php';
require_once __DIR__ . '/Classes/Fees.php';

// Create DB connection
$db = new Database();
$conn = $db->conn;

// Dashboard counts

// Active enrollments
$stmtActive = $conn->query("SELECT COUNT(*) AS active_enrollments FROM enrollments WHERE enrollment_status = 'Active'");
$active_enrollments = $stmtActive->fetch()['active_enrollments'];

// Total fee receivable
$stmtReceivable = $conn->query("
    SELECT SUM(c.course_fee) AS total_fee_receivable
    FROM courses c
    INNER JOIN enrollments e ON e.course_id = c.course_id
    WHERE c.course_id = e.course_id
");

// Fetch the result safely
$row = $stmtReceivable->fetch(PDO::FETCH_ASSOC);
$total_fee_receivable = $row['total_fee_receivable'] ?? 0;

// Total fee received (sum of all payments)
$stmtReceived = $conn->query("SELECT SUM(amount_paid) AS total_fee_received FROM fees");
$total_fee_received = $stmtReceived->fetch()['total_fee_received'] ?? 0;

// Pending fee (receivable - received)
$pending_fee = $total_fee_receivable - $total_fee_received;

?>

<div class="main-content bg-light">

<!-- Dashboard counts -->

<div class="row g-3 mb-4">

  <!-- Active Enrollments -->
  <div class="col-md-3">
    <div class="card shadow rounded border-0">
      <div class="card-body text-center">
        <i class="bi bi-person-check text-primary fs-4"></i>
        <h4 class="card-title">Active Enrollments</h4>
        <h2 class="card-text text-primary">
          <?= htmlspecialchars($active_enrollments ?? 0); ?>
        </h2>
        <p class="text-muted">Session 2025-26</p>
      </div>
    </div>
  </div>

  <!-- Total Fee Receivable -->
   <div class="col-md-3">
    <div class="card shadow rounded border-0">
      <div class="card-body text-center">
        <i class="bi bi-wallet2 text-warning fs-4"></i>
        <h4 class="card-title">Receivable Fee</h4>
        <h2 class="card-text text-warning">
          <?= htmlspecialchars($total_fee_receivable ?? 0); ?>
        </h2>
        <p class="text-muted">Session 2025-26</p>
      </div>
    </div>
  </div>

  <!-- Total Fee Received -->
  <div class="col-md-3">
    <div class="card shadow rounded border-0">
      <div class="card-body text-center">
        <i class="bi bi-cash-coin text-success fs-4"></i>
        <h4 class="card-title">Feee Recived</h4>
        <h2 class="card-text text-success">
          <?= htmlspecialchars($total_fee_received ?? 0); ?>
        </h2>
        <p class="text-muted">Session 2025-26</p>
      </div>
    </div>
  </div>

  <!-- Pending Fee -->
  <div class="col-md-3">
    <div class="card shadow rounded border-0">
      <div class="card-body text-center">
        <i class="bi bi-cash-coin text-danger fs-4"></i>
        <h4 class="card-title">Pending Fees</h4>
        <h2 class="card-text text-danger">
          <?= htmlspecialchars($pending_fee ?? 0); ?>
        </h2>
        <p class="text-muted">Session 2025-26</p>
      </div>
    </div>
  </div>

  <!-- Students Table -->
  <div class="card">
    <div class="card-body">
      <h5 class="mb-3">Students</h5>

      <!-- Search Form -->
       <div class="row">
        <div class="col-12 col-md-6 search">

      <form method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2"
               placeholder="Search by ID or Name"
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="btn btn-primary me-2">Search</button>
        <a href="index.php" class="btn btn-secondary">Clear</a>
      </form>

        </div>
         <div class="col-12 col-md-6 button-area text-end">
          <a href="pages/add_student.php" class="btn btn-primary">Add Student</a>
        </div>
       </div>
        <hr>
<?php
// --- SERVER-SIDE VALIDATION ---
$searchError = "";
$students = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search = trim($_GET['search']);

    if ($search === "") {
        $searchError = "Search term cannot be empty.";
    } elseif (ctype_digit($search)) {
        // Numeric ID search
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = :id");
        $stmt->bindParam(':id', $search, PDO::PARAM_INT);
        $stmt->execute();
        $students = $stmt->fetchAll();
    } elseif (preg_match("/^[a-zA-Z\s]+$/", $search)) {
        // Name search
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_name LIKE :name");
        $stmt->bindValue(':name', "%$search%");
        $stmt->execute();
        $students = $stmt->fetchAll();
    } else {
        $searchError = "Search must be either a numeric ID or a valid name.";
    }
}
?>

<!-- Search Form -->
<div class="row">
  <div class="col-12 col-md-6 search">
    <!-- Server-side error message -->
    <?php if (!empty($searchError)): ?>
      <div class="alert alert-danger mt-2"><?= htmlspecialchars($searchError) ?></div>
    <?php endif; ?>
  </div>
</div>

<script>
// --- CLIENT-SIDE VALIDATION ---
function validateSearch() {
    const value = document.getElementById('search').value.trim();

    if (value === "") {
        alert("Please enter a search term.");
        return false;
    }

    const isNumeric = /^\d+$/.test(value);
    const isName = /^[a-zA-Z\s]+$/.test(value);

    if (!isNumeric && !isName) {
        alert("Search must be either a numeric ID or a valid name.");
        return false;
    }

    return true;
}
</script>

<div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Father Name</th>
              <th>Gender</th>
              <th>DOB</th>
              <th>Phone</th>
              <th>Address</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
<?php
// Search logic with PDO
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    if (is_numeric($search)) {
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = :id");
        $stmt->bindParam(':id', $search, PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_name LIKE :name");
        $stmt->bindValue(':name', "%$search%");
    }
    $stmt->execute();
    $students = $stmt->fetchAll();
} else {
    $studentsObj = new Students();
    $students = $studentsObj->displayStudents();
}

// Display rows
foreach ($students as $student) {
    echo "<tr>
        <td>{$student['student_id']}</td>
        <td>{$student['student_name']}</td>
        <td>{$student['father_name']}</td>
        <td>{$student['gender']}</td>
        <td>{$student['date_of_birth']}</td>
        <td>{$student['phone']}</td>
        <td>{$student['address']}</td>
        <td>
            <a href='pages/edit_student.php?student_id={$student['student_id']}' class='btn btn-light btn-sm' data-bs-toggle='tooltip' title='Edit Student'><i class='bi bi-pencil text-primary'></i></a>
            <a href='pages/enroll_student.php?student_id={$student['student_id']}' class='btn btn-light btn-sm' data-bs-toggle='tooltip' title='Enroll Student'><i class='text-success bi bi-journal-plus'></i></a>
            <a href='pages/collect_fee.php?student_id={$student['student_id']}' class='btn btn-light btn-sm' data-bs-toggle='tooltip' title='Collect Fee'><i class='bi bi-cash-stack text-warning'></i></a>
            <a href='pages/report_student.php?student_id={$student['student_id']}' class='btn btn-light btn-sm' data-bs-toggle='tooltip' title='View Student'><i class='bi bi-file-earmark-text text-secondary'></i></a>
            <a href='pages/delete_student.php?student_id={$student['student_id']}' class='btn btn-light btn-sm' data-bs-toggle='tooltip' title='Delete Student'><i class='bi bi-trash text-danger'></i></a>
        </td>
    </tr>";
}
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>