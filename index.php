<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Tutor.php';

session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <title>Strona główna – Korepetytorzy</title>
</head>
<body>
    <div class="container">
        <h1 class='title'><i class="fa-solid fa-eye"></i>Znajdź korepetytora</h1>

        <input type="text" id="searchInput" placeholder="Wpisz nazwisko lub przedmiot..." />

        <ul id="tutorList"></ul>
    </div>

    <script>
    const input = document.getElementById('searchInput');
    const list = document.getElementById('tutorList');

    function fetchTutors(query = '') {
        fetch(`api/tutors.php?search=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '';

                if (data.length === 0) {
                    list.innerHTML = '<li>Brak wyników.</li>';
                    return;
                }

                data.forEach(tutor => {
                    const li = document.createElement('li');
                    li.className = 'tutor-card';

                    li.innerHTML = `
                        <h3>${tutor.name}</h3>
                        <p><strong>Opis:</strong> ${tutor.bio}</p>
                        <p><strong>Przedmioty:</strong> ${tutor.subjects.join(', ')}</p>
                        <p><strong>Stawka:</strong> ${tutor.hourly_rate} zł/h</p>
                        <a href="pages/booking.php?tutor_id=${tutor.id}" class="btn btn-primary">Zarezerwuj</a>
                    `;

                    list.appendChild(li);
                });
            });
    }

    input.addEventListener('input', () => {
        fetchTutors(input.value);
    });
    fetchTutors();
    </script>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
