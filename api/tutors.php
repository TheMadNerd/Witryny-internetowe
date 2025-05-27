<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Tutor.php';

session_start();

mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

header('Content-Type: application/json');

$search = $_GET['search'] ?? '';
$currentUserId = $_SESSION['user_id'] ?? null;

$tutors = Tutor::getAllTutors();

$filtered = array_filter($tutors, function($tutor) use ($search, $currentUserId) {
    if ($currentUserId && $tutor->id == $currentUserId) {
        return false;
    }

    $search = strtolower($search);
    $nameMatch = stripos($tutor->name, $search) !== false;

    $subjectMatch = count(array_filter($tutor->subjects, fn($s) => stripos($s, $search) !== false)) > 0;

    return $nameMatch || $subjectMatch;
});

$result = [];

foreach ($filtered as $tutor) {
    $result[] = [
        'id' => $tutor->id,
        'name' => $tutor->name,
        'bio' => $tutor->bio,
        'hourly_rate' => $tutor->hourly_rate,
        'subjects' => $tutor->subjects
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result);

