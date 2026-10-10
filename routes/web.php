<?php
declare(strict_types=1);

require_once __DIR__ . '/../controllers/layanan_controller.php';
require_once __DIR__ . '/../controllers/zona_controller.php';

try {
	$database = require __DIR__ . '/../config/database.php';
	$action = (string) ($_GET['action'] ?? 'list');
	$isLayananRequest = $_SERVER['REQUEST_METHOD'] === 'POST' || in_array($action, ['create', 'edit'], true);

	if ($isLayananRequest) {
		$status = (new layanan_controller($database))->handleWeb();
		if ($status !== null) {
			(new zona_controller($database))->showNotice($status);
		}
	} else {
		(new zona_controller($database))->handleWeb();
	}
} catch (PDOException $exception) {
	http_response_code(503);
	exit('Koneksi MySQL gagal. Periksa layanan MySQL dan konfigurasi DB_HOST, DB_NAME, DB_USER, DB_PASSWORD.');
}