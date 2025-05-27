<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Database.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = User::getById($_SESSION['user_id']);
$error = '';
$success = '';

$pdo = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $newName = trim($_POST['name'] ?? '');
    $newPass = $_POST['password'] ?? '';
    $newPass2 = $_POST['password2'] ?? '';

    if ($newPass && $newPass !== $newPass2) {
        $error = 'Hasła nie są identyczne.';
    } elseif (empty($newName)) {
        $error = 'Imię nie może być puste.';
    } else {
        $ok = $user->update($newName, $newPass ?: null);
        if ($ok) {
            $_SESSION['name'] = $newName;
            $success = 'Dane zaktualizowane.';
        } else {
            $error = 'Wystąpił błąd podczas zapisu.';
        }
    }
}

$bio = '';
$rate = '';
$userSubjects = [];

if ($_SESSION['role'] == 2) {
    $stmt = $pdo->prepare("SELECT bio, hourly_rate FROM TutorProfile WHERE tutor_id = ?");
    $stmt->execute([$user->id]);
    $profile = $stmt->fetch();

    $bio = $profile['bio'] ?? '';
    $rate = $profile['hourly_rate'] ?? '';

    $stmt2 = $pdo->prepare("SELECT subject_id FROM TutorSubjects WHERE tutor_id = ?");
    $stmt2->execute([$user->id]);
    $userSubjects = array_column($stmt2->fetchAll(), 'subject_id');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $bio = trim($_POST['bio'] ?? '');
        $rate = trim($_POST['hourly_rate'] ?? '');
        $selectedSubjects = $_POST['subjects'] ?? [];

        if ($rate === '' || !is_numeric($rate) || $rate <= 0) {
            $error = 'Podaj poprawną stawkę za godzinę (większą niż 0).';
        } elseif (empty($selectedSubjects)) {
            $error = 'Wybierz przynajmniej jeden przedmiot.';
        } else {
            if ($profile) {
                $stmt = $pdo->prepare("UPDATE TutorProfile SET bio = ?, hourly_rate = ? WHERE tutor_id = ?");
                $stmt->execute([$bio, $rate, $user->id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO TutorProfile (tutor_id, bio, hourly_rate) VALUES (?, ?, ?)");
                $stmt->execute([$user->id, $bio, $rate]);
            }

            $pdo->prepare("DELETE FROM TutorSubjects WHERE tutor_id = ?")->execute([$user->id]);
            $stmt = $pdo->prepare("INSERT INTO TutorSubjects (tutor_id, subject_id) VALUES (?, ?)");
            foreach ($selectedSubjects as $sid) {
                $stmt->execute([$user->id, $sid]);
            }

            $success = 'Profil korepetytora zaktualizowany.';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_profile'])) {
        $pdo->prepare("DELETE FROM TutorSubjects WHERE tutor_id = ?")->execute([$user->id]);
        $pdo->prepare("DELETE FROM TutorProfile WHERE tutor_id = ?")->execute([$user->id]);
        $success = 'Twoje ogłoszenie zostało usunięte.';
        $bio = '';
        $rate = '';
        $userSubjects = [];
    }
}

$subjects = $pdo->query("SELECT * FROM Subjects")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Twój profil</title>
    <link rel="stylesheet" href="/css/profile.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container">
    <h2>Twój profil</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <div class="card">
        <h3>Dane użytkownika</h3>
        <form method="post">
            <input type="hidden" name="update_user" value="1">

            <label for="name">Imię i nazwisko:</label>
            <input type="text" name="name" id="name" required value="<?= htmlspecialchars($user->name) ?>">

            <label for="email">Email (nieedytowalny):</label>
            <input type="email" value="<?= htmlspecialchars($user->email) ?>" disabled>

            <label for="password">Nowe hasło (opcjonalnie):</label>
            <input type="password" name="password" id="password">

            <label for="password2">Powtórz nowe hasło:</label>
            <input type="password" name="password2" id="password2">

            <button type="submit">Zapisz dane użytkownika</button>
        </form>
    </div>

    <?php if ($_SESSION['role'] == 2): ?>
        <div class="card">
            <h3>Profil korepetytora</h3>
            <form method="post">
                <input type="hidden" name="update_profile" value="1">

                <label for="bio">Opis:</label>
                <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($bio) ?></textarea>

                <label for="hourly_rate">Stawka (zł/h):</label>
                <input type="number" name="hourly_rate" id="hourly_rate" step="1" value="<?= htmlspecialchars($rate) ?>">

                <label>Przedmioty:</label>
                <div class="subjects-grid">
                    <?php foreach ($subjects as $sub): ?>
                        <label>
                            <input type="checkbox" name="subjects[]" value="<?= $sub['subject_id'] ?>"
                                   <?= in_array($sub['subject_id'], $userSubjects) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($sub['name']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>


                <button type="submit">Zapisz profil korepetytora</button>
            </form>

            <form method="post" onsubmit="return confirm('Czy na pewno chcesz usunąć swoje ogłoszenie?');">
                <input type="hidden" name="delete_profile" value="1">
                <button type="submit" class="delete">Usuń ogłoszenie</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
