<?php
require_once __DIR__ . '/../config/config.php';

class Reports {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // Dashboard counts
    public function getActiveEnrollments() {
        $sql = "SELECT COUNT(*) AS active_enrollments FROM enrollments WHERE enroll_status='Active'";
        return $this->conn->query($sql)->fetch()['active_enrollments'];
    }

    public function getTotalFeeReceivable() {
        $sql = "SELECT SUM(course_fee) AS total_fee_receivable FROM enrollments WHERE enroll_status='Inactive'";
        return $this->conn->query($sql)->fetch()['total_fee_receivable'];
    }

    public function getTotalFeeReceived() {
        $sql = "SELECT SUM(amount_paid) AS total_fee_received FROM fees WHERE amount_paid > 0";
        return $this->conn->query($sql)->fetch()['total_fee_received'];
    }

    public function getPendingFee() {
        return $this->getTotalFeeReceivable() - $this->getTotalFeeReceived();
    }
}