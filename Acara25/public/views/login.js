// Acara 25: login dan penyimpanan token + role
const form = document.querySelector('#form-login');
const pesan = document.querySelector('#pesan');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    pesan.className = 'pesan';

    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                username: document.querySelector('#username').value,
                password: document.querySelector('#password').value,
            }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            window.location.href = 'dashboard.html';
        } else {
            pesan.textContent = hasil.message;
            pesan.className = 'pesan error';
        }
    } catch (err) {
        pesan.textContent = 'Tidak dapat terhubung ke server.';
        pesan.className = 'pesan error';
    }
});
