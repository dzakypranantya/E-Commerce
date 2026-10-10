<?php
declare(strict_types=1);

require_once __DIR__ . '/../controllers/layanan_controller.php';
require_once __DIR__ . '/../controllers/zona_controller.php';

try {
	$controller = new layanan_controller(require __DIR__ . '/../config/database.php');
	$controller->handleApi();
} catch (PDOException $exception) {
	header('Content-Type: application/json; charset=utf-8');
	http_response_code(500);
	echo json_encode(['message' => 'Koneksi MySQL gagal. Periksa layanan MySQL dan konfigurasi database.']);
}