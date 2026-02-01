
<?php
require_once __DIR__ . '/../config/config.php';

class Students {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // view all students
    public function displayStudents() {
        try {
            $sql = "SELECT * FROM students ORDER BY student_id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $err) {
            echo 'Failed to fetch records. ' . $err->getMessage();
            return [];
        }
    }

    // view single student by ID
    public function getStudentById($id) {
        try {
            $sql = "SELECT * FROM students WHERE student_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $err) {
            echo 'Failed to fetch student. ' . $err->getMessage();
            return null;
        }
    }

    // add new student
    public function addStudent($student_name, $father_name, $gender, $date_of_birth, $phone, $address) {
        try {
            $sql = "INSERT INTO students (student_name, father_name, gender, date_of_birth, phone, address)
                    VALUES (:student_name, :father_name, :gender, :date_of_birth, :phone, :address)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':student_name', $student_name);
            $stmt->bindParam(':father_name', $father_name);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':date_of_birth', $date_of_birth);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            return $stmt->execute();
        } catch (PDOException $err) {
            echo 'Failed to add student. ' . $err->getMessage();
            return false;
        }
    }

    // edit student details
    public function updateStudent($id, $student_name, $father_name, $gender, $date_of_birth, $phone, $address) {
        try {
            $sql = "UPDATE students 
                    SET student_name = :student_name, father_name = :father_name, gender = :gender,
                        date_of_birth = :date_of_birth, phone = :phone, address = :address
                    WHERE student_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':student_name', $student_name);
            $stmt->bindParam(':father_name', $father_name);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':date_of_birth', $date_of_birth);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            return $stmt->execute();
        } catch (PDOException $err) {
            echo 'Failed to update student. ' . $err->getMessage();
            return false;
        }
    }

    // remove student
    public function deleteStudent($id) {
        try {
            $sql = "DELETE FROM students WHERE student_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $err) {
            echo 'Failed to delete student. ' . $err->getMessage();
            return false;
        }
    }
}
?>



