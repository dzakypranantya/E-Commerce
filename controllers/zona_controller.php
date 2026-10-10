<?php
declare(strict_types=1);

final class zona_controller
{
	private PDO $database;

	private const NOTICE_MESSAGES = [
		'created' => 'Layanan berhasil ditambahkan.',
		'updated' => 'Layanan berhasil diperbarui.',
		'deleted' => 'Layanan berhasil dihapus.',
		'not_found' => 'Layanan tidak ditemukan.',
	];

	public function __construct(PDO $database)
	{
		$this->database = $database;
	}

	public function all(string $search = ''): array
	{
		$columns = 'id, nama_ekspedisi, jenis_layanan, zonasi, tarif, estimasi, status';
		if ($search === '') {
			return $this->database->query("SELECT {$columns} FROM layanan ORDER BY id DESC")->fetchAll();
		}

		$query = $this->database->prepare(
			" SELECT {$columns} FROM layanan
			  WHERE nama_ekspedisi LIKE ? OR jenis_layanan LIKE ? OR zonasi LIKE ?
			  ORDER BY id DESC"
		);
		$term = '%' . $search . '%';
		$query->execute([$term, $term, $term]);

		return $query->fetchAll();
	}

	public function showNotice(string $status): void
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
		$_SESSION['notice'] = self::NOTICE_MESSAGES[$status] ?? '';
		header('Location: index.php');
		exit;
	}

	public function handleWeb(): void
	{
		session_start();

		$notice = (string) ($_SESSION['notice'] ?? '');
		unset($_SESSION['notice']);
		$isForm = false;
		$search = '';
		if (isset($_GET['search'])) {
			$search = trim((string) $_GET['search']);
		}
		$services = $this->all($search);
		$escape = function ($value) {
			return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
		};

		require __DIR__ . '/../src/layanan.php';
	}
}