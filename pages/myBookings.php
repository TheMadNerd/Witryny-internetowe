<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

$pdo = Database::getInstance();
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 1) {
    $stmt = $pdo->prepare("
        SELECT u.name AS tutor_name, u.email AS tutor_email, b.booking_time
        FROM Bookings b
        JOIN Users u ON b.tutor_id = u.user_id
        WHERE b.student_id = ?
        ORDER BY b.booking_time DESC
    ");
    $stmt->execute([$userId]);
    $bookings = $stmt->fetchAll();
    $viewTitle = "Twoje lekcje";
} elseif ($role == 2) {
    $stmt = $pdo->prepare("
        SELECT u.name AS student_name, u.email AS student_email, b.booking_time
        FROM Bookings b
        JOIN Users u ON b.student_id = u.user_id
        WHERE b.tutor_id = ?
        ORDER BY b.booking_time DESC
    ");
    $stmt->execute([$userId]);
    $bookings = $stmt->fetchAll();
    $viewTitle = "Twoje rezerwacje od uczniów";
} else {
    $bookings = [];
    $viewTitle = "Brak dostępu do rezerwacji";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <title><?= $viewTitle ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/myBookings.css">
</head>
<body>
<div class="container">
    <h2><?= $viewTitle ?></h2>

    <?php if (count($bookings) === 0): ?>
        <div class="no-bookings">
            <i class="fa-regular fa-calendar-xmark fa-2x"></i>
            <p>Brak rezerwacji.</p>
        </div>
    <?php else: ?>
        <ul class="booking-list">
            <?php foreach ($bookings as $b): ?>
                <li>
                    <?php if ($role == 1): ?>
                        Korepetytor: <strong><?= htmlspecialchars($b['tutor_name']) ?></strong><br>
                        <small><?= htmlspecialchars($b['tutor_email']) ?></small><br>
                    <?php else: ?>
                        Uczeń: <strong><?= htmlspecialchars($b['student_name']) ?></strong><br>
                        <small><?= htmlspecialchars($b['student_email']) ?></small><br>
                    <?php endif; ?>
                    Termin: <?= date('d.m.Y H:i', strtotime($b['booking_time'])) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
