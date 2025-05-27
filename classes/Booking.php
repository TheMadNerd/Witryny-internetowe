<?php

require_once __DIR__ . '/Database.php';

class Booking {
    public $id;
    public $tutor_id;
    public $student_id;
    public $subject_id;
    public $booking_time;
    public $status;

    public function __construct($data) {
        $this->id = $data['booking_id'] ?? null;
        $this->tutor_id = $data['tutor_id'] ?? null;
        $this->student_id = $data['student_id'] ?? null;
        $this->subject_id = $data['subject_id'] ?? null;
        $this->booking_time = $data['booking_time'] ?? null;
        $this->status = $data['status'] ?? 'pending';
    }

    public static function create($student_id, $tutor_id, $subject_id, $booking_time): ?Booking {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("
            INSERT INTO Bookings (student_id, tutor_id, subject_id, booking_time, status)
            VALUES (:student, :tutor, :subject, :time, 'pending')
            RETURNING *
        ");

        $stmt->execute([
            'student' => $student_id,
            'tutor' => $tutor_id,
            'subject' => $subject_id,
            'time' => $booking_time
        ]);

        $row = $stmt->fetch();

        return $row ? new Booking($row) : null;
    }

    public static function getByTutor($tutor_id): array {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT * FROM Bookings WHERE tutor_id = :id ORDER BY booking_time");
        $stmt->execute(['id' => $tutor_id]);

        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Booking($row);
        }

        return $result;
    }

    public static function getByStudent($student_id): array {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT * FROM Bookings WHERE student_id = :id ORDER BY booking_time");
        $stmt->execute(['id' => $student_id]);

        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Booking($row);
        }

        return $result;
    }

    public static function confirm($booking_id, $tutor_id): bool {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("
            UPDATE Bookings
            SET status = 'confirmed'
            WHERE booking_id = :bid AND tutor_id = :tid
        ");

        return $stmt->execute([
            'bid' => $booking_id,
            'tid' => $tutor_id
        ]);
    }

    public static function cancel($booking_id, $user_id): bool {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("
            UPDATE Bookings
            SET status = 'cancelled'
            WHERE booking_id = :bid AND (tutor_id = :uid OR student_id = :uid)
        ");

        return $stmt->execute([
            'bid' => $booking_id,
            'uid' => $user_id
        ]);
    }
}
