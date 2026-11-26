# Dokumentasi Endpoint - Acara 19 (Postman dan API Authentication)

Base URL: `{{base_url}}` = `http://localhost/tugas%20acara_cya/Acara_19/public`

## Endpoint baru
| Method | Endpoint | Fungsi | Status |
|---|---|---|---|
| POST | /register | Registrasi pengguna (password di-hash) | 201, 400, 409, 422 |

**Contoh request**
```json
{ "username": "cintya", "email": "cintya@gmail.com", "password": "rahasia123" }
```
**Contoh response berhasil (201)**
```json
{ "success": true, "message": "Registrasi berhasil", "data": { "id": "1" }, "errors": [], "timestamp": "..." }
```
**Contoh response gagal (422, email tidak valid - Tugas Mandiri 1)**
```json
{ "success": false, "message": "Format email tidak valid", "data": null, "errors": { "email": "Format email tidak valid" } }
```
Endpoint mahasiswa dan buku tetap seperti Acara 18.
