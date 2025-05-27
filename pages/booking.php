<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Tutor.php';
require_once __DIR__ . '/../classes/Booking.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: /pages/login.php');
    exit;
}

$tutor_id = $_GET['tutor_id'] ?? null;
$success = '';
$error = '';

$pdo = Database::getInstance();

$stmt = $pdo->prepare("
    SELECT u.user_id, u.name, tp.bio, tp.hourly_rate
    FROM Users u
    JOIN TutorProfile tp ON u.user_id = tp.tutor_id
    WHERE u.user_id = :id AND u.role_id = 2
");
$stmt->execute(['id' => $tutor_id]);
$tutor = $stmt->fetch();

if (!$tutor) {
    $error = "Nie znaleziono korepetytora.";
} else {
    $stmt2 = $pdo->prepare("
        SELECT s.subject_id, s.name
        FROM TutorSubjects ts
        JOIN Subjects s ON ts.subject_id = s.subject_id
        WHERE ts.tutor_id = :tid
    ");
    $stmt2->execute(['tid' => $tutor_id]);
    $subjects = $stmt2->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'] ?? null;
    $datetime = $_POST['booking_time'] ?? '';

    if ($subject_id && $datetime) {
        $booking = Booking::create($_SESSION['user_id'], $tutor_id, $subject_id, $datetime);
        if ($booking) {
            $success = "Zarezerwowano lekcję.";
        } else {
            $error = "Nie udało się utworzyć rezerwacji.";
        }
    } else {
        $error = "Wszystkie pola są wymagane.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <title>Rezerwacja lekcji</title>
    <link rel="stylesheet" href="/css/booking.css">
</head>
<body>
<div class="container">
    <h2>Rezerwacja lekcji</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if ($tutor): ?>
       <div class="tutor-card">
           <h3><?= htmlspecialchars($tutor['name']) ?></h3>
           <p><?= nl2br(htmlspecialchars($tutor['bio'])) ?></p>
           <p><strong>Stawka:</strong> <?= $tutor['hourly_rate'] ?> zł/h</p>
       </div>


        <form method="post">
            <label for="subject_id">Przedmiot:</label><br>
            <select name="subject_id" id="subject_id" required>
                <?php foreach ($subjects as $sub): ?>
                    <option value="<?= $sub['subject_id'] ?>">
                        <?= htmlspecialchars($sub['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="date">Data lekcji:</label>
            <input type="date" id="date" required>

            <label for="time">Godzina lekcji:</label>
            <input type="time" id="time" required>

            <input type="hidden" name="booking_time" id="booking_time">


            <button type="submit">Zarezerwuj</button>
        </form>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

<script src="/js/connectDateAndHour.js"></script>
<script src="/js/dateValidation.js"></script>
</html>
