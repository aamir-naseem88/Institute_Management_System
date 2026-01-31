<?php
require_once __DIR__ . '/../config/config.php';

class Courses {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn; // store connection in class property
    }

    // View all courses
    public function viewAllCourses() {
        $sql = "SELECT * FROM courses ORDER BY course_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // View single course by ID
    public function viewSingleCourse($course_id) {
        $sql = "SELECT * FROM courses WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Enter new course
    public function addNewCourse($course_name, $course_duration, $course_fee) {
    $sql = "INSERT INTO courses (course_name, course_duration, course_fee)
            VALUES (:course_name, :course_duration, :course_fee)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':course_name', $course_name);
    $stmt->bindParam(':course_duration', $course_duration);
    $stmt->bindParam(':course_fee', $course_fee);
    return $stmt->execute();
}

    // Update course
    public function updateCourse($course_id, $course_name, $course_duration, $course_fee) {
        $sql = "UPDATE courses 
                SET course_name = :course_name, 
                    course_duration = :course_duration, 
                    course_fee = :course_fee 
                WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_name', $course_name);
        $stmt->bindParam(':course_duration', $course_duration);
        $stmt->bindParam(':course_fee', $course_fee);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete course
    public function deleteCourse($course_id) {
        $sql = "DELETE FROM courses WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
?>