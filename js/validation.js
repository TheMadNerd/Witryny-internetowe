document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    const password = document.getElementById('password');
    const password2 = document.getElementById('password2');

    form.addEventListener('submit', (e) => {
        if (password && password2 && password.value !== password2.value) {
            e.preventDefault();
            alert('Hasła nie są identyczne.');
        }
    });
});
