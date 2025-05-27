<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Tutor.php';

session_start();

$tutors = Tutor::getAllTutors();
$currentUserId = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <title>Lista korepetytorów</title>
</head>
<body>
<div class="container">
    <h2>Korepetytorzy</h2>

    <ul id="tutors">
        <?php foreach ($tutors as $tutor): ?>
            <li class="tutor-card">
                <h3><?= htmlspecialchars($tutor->name) ?></h3>
                <p><strong>Przedmioty:</strong> <?= htmlspecialchars(implode(', ', $tutor->subjects)) ?></p>
                <p><strong>Stawka:</strong> <?= htmlspecialchars($tutor->hourly_rate) ?> zł/h</p>
                <p><strong>Opis:</strong> <?= htmlspecialchars($tutor->bio ?? 'Brak opisu.') ?></p>
                <?php if (!$currentUserId || $currentUserId != $tutor->id): ?>
                    <a href="booking.php?tutor_id=<?= $tutor->id ?>" class="btn">Zarezerwuj</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
