document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('searchInput');
    const list = document.getElementById('tutorList');

    function renderTutors(data) {
        list.innerHTML = '';

        if (data.length === 0) {
            list.innerHTML = '<li>Brak wyników.</li>';
            return;
        }

        data.forEach(tutor => {
            const li = document.createElement('li');
            li.classList.add('tutor-card');

            li.innerHTML = `
    <h3>${tutor.name}</h3>
    <p><strong>Opis:</strong> ${tutor.bio || 'Brak opisu.'}</p>
    <p><strong>Przedmioty:</strong> ${tutor.subjects.join(', ')}</p>
    <p><strong>Stawka:</strong> ${tutor.hourly_rate} zł/h</p>
    <a href="pages/booking.php?tutor_id=${tutor.id}" class="btn">Zarezerwuj</a>
`;

            list.appendChild(li);
        });
    }

    function fetchTutors(query = '') {
        fetch(`/api/tutors.php?search=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(renderTutors)
            .catch(err => {
                list.innerHTML = `<li>Błąd pobierania danych.</li>`;
                console.error('Błąd:', err);
            });
    }

    if (input) {
        input.addEventListener('input', () => {
            fetchTutors(input.value);
        });
        fetchTutors();
    }
});
