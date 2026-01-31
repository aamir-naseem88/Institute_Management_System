<?php
require_once __DIR__ . '/../config/config.php';

class Fees {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // Add a fee payment
    public function addFee($enrollment_id, $amount_paid, $notes = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO fees (enrollment_id, amount_paid, payment_date, notes) 
            VALUES (:eid, :amount, NOW(), :notes)
        ");
        return $stmt->execute([
            ':eid'   => $enrollment_id,
            ':amount'=> $amount_paid,
            ':notes' => $notes
        ]);
    }

    // Get all fees for a student (via enrollments)
    public function getFeesByStudent($student_id) {
        $stmt = $this->conn->prepare("
            SELECT f.fee_id, f.amount_paid, f.payment_date, f.notes,
                   c.course_name
            FROM fees f
            JOIN enrollments e ON f.enrollment_id = e.enrollment_id
            JOIN courses c ON e.course_id = c.course_id
            WHERE e.student_id = :sid
            ORDER BY f.payment_date DESC
        ");
        $stmt->execute([':sid' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get fees by enrollment (specific course enrollment)
    public function getFeesByEnrollment($enrollment_id) {
        $stmt = $this->conn->prepare("
            SELECT fee_id, amount_paid, payment_date, notes
            FROM fees
            WHERE enrollment_id = :eid
            ORDER BY payment_date DESC
        ");
        $stmt->execute([':eid' => $enrollment_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}