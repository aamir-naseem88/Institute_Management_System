<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
define('BASE_URL', '/academy_system02/');

// connection from your Database class
$db = new Database();
$conn = $db->conn;

?>
<div class="main-container bg-light" style="margin-left:240px;">
    <div class="row">
        <div class="col bg-white p-4 border rounded m-4">

            <!-- report Form -->
            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label for="reportType">Select Report Type:</label>
                    <select name="reportType" id="reportType" class="form-control">
                        <option value="students">All Students</option>
                        <option value="enrollments">Enrollments</option>
                        <option value="courses">Courses & Fees</option>
                        <option value="fees">Fee Report</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="fromDate">From Date:</label>
                    <input type="date" name="fromDate" id="fromDate" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="toDate">To Date:</label>
                    <input type="date" name="toDate" id="toDate" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>

            <?php

            // form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $reportType = $_POST['reportType'];
                $fromDate   = $_POST['fromDate'];
                $toDate     = $_POST['toDate'];

                switch ($reportType) {
                    case 'students':
                        //get all students database
                        $sql = "SELECT student_id, student_name, phone, address 
                                FROM students";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        echo "<h3 class='mt-4'>All Students Report</h3>";
                        echo "<table class='table table-bordered mt-3'><tr><th>ID</th><th>Name</th><th>Phone</th><th>Address</th></tr>";
                        foreach ($rows as $row) {
                            echo "<tr><td>{$row['student_id']}</td><td>{$row['student_name']}</td><td>{$row['phone']}</td><td>{$row['address']}</td></tr>";
                        }
                        echo "</table>";
                        break;

                    case 'enrollments':
                        $sql = "SELECT e.enrollment_id, s.student_name, c.course_name, e.enrollment_date, e.enrollment_status
                                FROM enrollments e
                                INNER JOIN students s ON e.student_id = s.student_id
                                INNER JOIN courses c ON e.course_id = c.course_id
                                WHERE e.enrollment_date BETWEEN :fromDate AND :toDate";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':fromDate', $fromDate);
                        $stmt->bindParam(':toDate', $toDate);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        echo "<h3 class='mt-4'>Enrollments Report ($fromDate to $toDate)</h3>";
                        echo "<table class='table table-bordered mt-3'><tr><th>ID</th><th>Student</th><th>Course</th><th>Date</th><th>Status</th></tr>";
                        foreach ($rows as $row) {
                            echo "<tr><td>{$row['enrollment_id']}</td><td>{$row['student_name']}</td><td>{$row['course_name']}</td><td>{$row['enrollment_date']}</td><td>{$row['enrollment_status']}</td></tr>";
                        }
                        echo "</table>";
                        break;

                    case 'courses':
                        // all courses
                        $sql = "SELECT course_id, course_name, course_duration, course_fee 
                                FROM courses";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        echo "<h3 class='mt-4'>Courses Report</h3>";
                        echo "<table class='table table-bordered mt-3'><tr><th>ID</th><th>Name</th><th>Duration</th><th>Fee</th></tr>";
                        foreach ($rows as $row) {
                            echo "<tr><td>{$row['course_id']}</td><td>{$row['course_name']}</td><td>{$row['course_duration']}</td><td>{$row['course_fee']}</td></tr>";
                        }
                        echo "</table>";
                        break;

                    case 'fees':
                        $sql = "SELECT SUM(c.course_fee) AS total_receivable,
                                       (SELECT SUM(amount_paid) FROM fees WHERE payment_date BETWEEN :fromDate AND :toDate) AS total_received
                                FROM enrollments e
                                INNER JOIN courses c ON e.course_id = c.course_id
                                WHERE e.enrollment_date BETWEEN :fromDate AND :toDate";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':fromDate', $fromDate);
                        $stmt->bindParam(':toDate', $toDate);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        $balance = ($row['total_receivable'] ?? 0) - ($row['total_received'] ?? 0);

                        echo "<h3 class='mt-4'>Fee Report ($fromDate to $toDate)</h3>";
                        echo "<table class='table table-bordered mt-3'>
                                <tr><th>Total Receivable</th><th>Total Received</th><th>Balance</th></tr>
                                <tr><td>{$row['total_receivable']}</td><td>{$row['total_received']}</td><td>{$balance}</td></tr>
                              </table>";
                        break;
                }

                
                echo '<div class="mt-4 d-flex justify-content-end">';
                echo '<button class="btn btn-primary me-2" onclick="window.print()">Print Report</button>';
                echo '<button class="btn btn-secondary" onclick="location.reload()">Cancel</button>';
                echo '</div>';
            }
            ?>

        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>