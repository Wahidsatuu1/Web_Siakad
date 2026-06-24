<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| Jika aplikasi dalam mode maintenance melalui perintah "down", kita akan
| memuat file ini sehingga konten yang sudah di-render bisa ditampilkan
| sebagai ganti dari memulai framework, yang dapat menyebabkan exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer menyediakan class loader yang mudah dan dibuat secara otomatis
| untuk aplikasi ini. Kita hanya perlu menggunakannya! Kita akan
| me-require-nya di sini agar tidak perlu memuat kelas-kelas kita secara manual.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Setelah kita memiliki aplikasi, kita bisa menangani permintaan yang masuk
| menggunakan kernel HTTP aplikasi. Lalu, kita akan mengirimkan respons
| kembali ke browser klien ini, memungkinkan mereka menikmati aplikasi kita.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);