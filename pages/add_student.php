<?php
require_once __DIR__ . '/../Classes/Students.php';
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
define('BASE_URL', '/academy_system02/');
$studentsObj = new Students();

$errors = [];

// form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name  = trim($_POST['student_name']);
    $father_name   = trim($_POST['father_name']);
    $gender        = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $phone         = trim($_POST['phone']);
    $address       = trim($_POST['address']);

    // --- servr side validatin ---
    if (!preg_match("/^[a-zA-Z\s]+$/", $student_name)) {
        $errors[] = "Student name must contain only letters and spaces.";
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $father_name)) {
        $errors[] = "Father name must contain only letters and spaces.";
    }
    if (!in_array($gender, ['Male', 'Female'])) {
        $errors[] = "Invalid gender selected.";
    }
    if (empty($date_of_birth)) {
        $errors[] = "Date of birth is required.";
    }
    if (!preg_match("/^\d{10,11}$/", $phone)) {
        $errors[] = "Phone must be 10–11 digits.";
    }
    if (strlen($address) < 5) {
        $errors[] = "Address must be at least 5 characters long.";
    }

    if (empty($errors)) {
        $added = $studentsObj->addStudent(
            $student_name,
            $father_name,
            $gender,
            $date_of_birth,
            $phone,
            $address
        );

        if ($added) {
            echo "<script>alert('Student added successfully!'); window.location.href='" . BASE_URL . "pages/add_student.php';</script>";
        } else {
            echo "<script>alert('Failed to add student.');</script>";
        }
    }
}
?>

<div class="main-content">
  <div class="container mt-0 rounded border bg-white p-4">
    <h3>Add Student</h3>

    <!-- server side errors -->
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateForm();">
      <div class="mb-3">
        <label class="form-label">Student Name</label>
        <input type="text" name="student_name" id="student_name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Father Name</label>
        <input type="text" name="father_name" id="father_name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Gender</label>
        <select name="gender" id="gender" class="form-control" required>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" id="address" class="form-control" required></textarea>
      </div>

      <button type="submit" class="btn btn-success">Add Student</button>
      <a href="<?= BASE_URL . 'index.php' ?>" class="btn btn-secondary">Back</a>
    </form>
  </div>
</div>

<script>
// --- validation ---
function validateForm() {
    const name = document.getElementById('student_name').value.trim();
    const father = document.getElementById('father_name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();

    if (!/^[a-zA-Z\s]+$/.test(name)) {
        alert("Student name must contain only letters and spaces.");
        return false;
    }
    if (!/^[a-zA-Z\s]+$/.test(father)) {
        alert("Father name must contain only letters and spaces.");
        return false;
    }
    if (!/^\d{10,11}$/.test(phone)) {
        alert("Phone must be 10–11 digits.");
        return false;
    }
    if (address.length < 5) {
        alert("Address must be at least 5 characters long.");
        return false;
    }
    return true;
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>