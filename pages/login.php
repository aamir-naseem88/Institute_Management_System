<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../layout/header.php';
define('BASE_URL', '/academy_system02/');

$db = new Database();
$conn = $db->conn;

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        } else {
            $message = 'Invalid username or password.';
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}
?>

<div class="container-fluid" style="background-image: url('/academy_system02/assets/img/institute_bg.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; position: relative; min-height: 100vh; filter: brightness(0.9) blur(0.5px);">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.9) 100%); z-index: 1;"></div>
    <div class="row d-flex justify-content-center align-items-center" style="height: 100vh; position: relative; z-index: 2;">
        <div class="col-md-4 offset-md-3 p-4 m-4" style="background-color: #ffffff; border: 2px solid #A1B5FF; border-radius: 12px; box-shadow: 0 8px 16px rgba(0, 123, 255, 0.2);">
        <div class="logo text-center">
            <img style="width: 200px; height:auto; margin-bottom:-24px;" src="/academy_system02/assets/img/logo.png" alt="Al-Rehman Academy Logo" style="width: 100px; height: auto;">
        </div>    
        <h2 class="text-center text-primary">Login</h2>
            <?php if ($message): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                 <div class="alert alert-info rounded pb-0">
                <p class="fs-small">For testing username = root & password = admin</p>
            </div>
                <div class="form-group mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>