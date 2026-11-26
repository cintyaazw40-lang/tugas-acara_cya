// BASE_URL ada di config.js
const username = document.querySelector('#username');
const password = document.querySelector('#password');

document.querySelector('#form-login').addEventListener('submit', async (e) => {
    e.preventDefault();
    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: username.value, password: password.value }),
        });
        const hasil = await res.json();
        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            window.location.href = 'dashboard.html';
        } else {
            alert(hasil.message);
        }
    } catch (err) {
        alert('Tidak dapat terhubung ke server');
    }
});