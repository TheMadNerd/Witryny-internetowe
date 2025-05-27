<?php

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Database.php';

class Tutor extends User {
    public $bio;
    public $hourly_rate;
    public $subjects = [];

    public function __construct($data) {
        parent::__construct($data);
        $this->bio = $data['bio'] ?? '';
        $this->hourly_rate = $data['hourly_rate'] ?? null;
        $this->subjects = $data['subjects'] ?? [];
    }

    public static function getAllTutors(): array {
        $pdo = Database::getInstance();

        $sql = "
            SELECT
                u.user_id, u.name, u.email,
                tp.bio, tp.hourly_rate
            FROM Users u
            JOIN TutorProfile tp ON u.user_id = tp.tutor_id
            WHERE u.role_id = 2
        ";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();

        $subjectsStmt = $pdo->prepare("
            SELECT ts.tutor_id, s.name
            FROM TutorSubjects ts
            JOIN Subjects s ON ts.subject_id = s.subject_id
        ");
        $subjectsStmt->execute();
        $subjectsMap = [];

        foreach ($subjectsStmt->fetchAll() as $row) {
            $tid = $row['tutor_id'];
            if (!isset($subjectsMap[$tid])) {
                $subjectsMap[$tid] = [];
            }
            $subjectsMap[$tid][] = $row['name'];
        }

        $tutors = [];
        foreach ($rows as $row) {
            $row['subjects'] = $subjectsMap[$row['user_id']] ?? [];
            $tutors[] = new Tutor($row);
        }

        return $tutors;
    }
}
