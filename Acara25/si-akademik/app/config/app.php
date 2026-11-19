<?php
/**
 * Konfigurasi aplikasi.
 *
 * APP_ENV menentukan mode aplikasi:
 *   - 'development' : response JSON diformat rapi (JSON_PRETTY_PRINT) dan
 *                     detail error server ikut ditampilkan untuk debugging.
 *   - 'production'  : response JSON dipadatkan (hemat bandwidth) dan detail
 *                     error internal disembunyikan dari client.
 *
 * Nilai dapat diganti lewat environment variable APP_ENV (mis. pada server
 * produksi di acara 28 & 30) tanpa mengubah kode. Bila tidak diset, mode
 * bawaan adalah 'development'.
 */
if (!defined('APP_ENV')) {
    define('APP_ENV', getenv('APP_ENV') ?: 'development');
}
