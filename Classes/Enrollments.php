<?php
require_once __DIR__ . '/../config/config.php';

class Enrollments {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // enroll student in a course
    public function enrollStudent($student_id, $course_id, $enrollment_status = 'Active') {
    
    // check duplicate
    
    $check = $this->conn->prepare("
        SELECT enrollment_id 
        FROM enrollments 
        WHERE student_id = :sid AND course_id = :cid
    ");
    
    $check->execute([':sid' => $student_id, ':cid' => $course_id]);

    if ($check->rowCount() > 0) {
        return ['success' => false, 'message' => '⚠ Already enrolled in this course'];
    }

    // rnroll student
    $stmt = $this->conn->prepare("
        INSERT INTO enrollments (student_id, course_id, enrollment_status) 
        VALUES (:sid, :cid, :enrollment_status)
    ");
    $stmt->execute([
        ':sid' => $student_id,
        ':cid' => $course_id,
        ':enrollment_status' => $enrollment_status
    ]);

    return ['success' => true, 'message' => '✅ Enrollment successful'];
}

    // get all courses dropdown
    public function getAllCourses() {
        $stmt = $this->conn->query("SELECT course_id, course_name FROM courses ORDER BY course_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // get student enrollments
    public function getEnrollmentsByStudent($student_id) {
    $stmt = $this->conn->prepare("
        SELECT e.enrollment_id, e.enrollment_status, e.enrollment_date,
               c.course_id, c.course_name, c.course_fee
        FROM enrollments e
        JOIN courses c ON e.course_id = c.course_id
        WHERE e.student_id = :sid
    ");
    $stmt->execute([':sid' => $student_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

  // get All enrollments
    public function getTotalEnrollments() {
    $sql = "SELECT COUNT(enrollment_id) FROM enrollments";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// update enrollment status
public function updateEnrollmentStatus($enrollment_id, $new_status) {
    $stmt = $this->conn->prepare("
        UPDATE enrollments 
        SET enrollment_status = :status 
        WHERE enrollment_id = :eid
    ");
    return $stmt->execute([
        ':status' => $new_status,
        ':eid'    => $enrollment_id
    ]);
}

}