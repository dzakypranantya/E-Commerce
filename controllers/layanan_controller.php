<?php
declare(strict_types=1);

final class layanan_controller
{
	private const JENIS_OPTIONS = ['Reguler', 'Express', 'Cargo'];
	private const ZONA_OPTIONS = ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'];
	private const ESTIMASI_OPTIONS = ['1 Hari', '2-3 Hari', '3-4 Hari', '3-5 Hari', '4-5 Hari', '5-7 Hari', '7-14 Hari'];
	private const STATUS_OPTIONS = ['ON', 'OFF'];
///
	public function __construct(private PDO $database)
	{
	}

	public function find(int $id): ?array
	{
		$query = $this->database->prepare(
			'SELECT id, nama_ekspedisi, jenis_layanan, zonasi, tarif, estimasi, status FROM layanan WHERE id = ?'
		);
		$query->execute([$id]);
		$record = $query->fetch();

		return $record ?: null;
	}

	public function create(array $input): int
	{
		$record = $this->validate($input);
		$query = $this->database->prepare(
			'INSERT INTO layanan (nama_ekspedisi, jenis_layanan, zonasi, tarif, estimasi, status) VALUES (?, ?, ?, ?, ?, ?)'
		);
		$query->execute(array_values($record));

		return (int) $this->database->lastInsertId();
	}

	public function update(int $id, array $input): bool
	{
		$currentRecord = $id > 0 ? $this->find($id) : null;
		if ($currentRecord === null) {
			return false;
		}

		$record = $this->validate(array_merge($currentRecord, $input));
		$query = $this->database->prepare(
			'UPDATE layanan SET nama_ekspedisi = ?, jenis_layanan = ?, zonasi = ?, tarif = ?, estimasi = ?, status = ? WHERE id = ?'
		);
		$query->execute([...array_values($record), $id]);

		return true;
	}

	public function delete(int $id): bool
	{
		$query = $this->database->prepare('DELETE FROM layanan WHERE id = ?');
		$query->execute([$id]);

		return $query->rowCount() > 0;
	}

	public function handleWeb(): ?string
	{
		session_start();

		$action = $_GET['action'] ?? 'list';
		$error = '';
		$record = [
			'nama_ekspedisi' => '',
			'jenis_layanan' => '',
			'zonasi' => '',
			'tarif' => '',
			'estimasi' => '',
			'status' => 'ON',
		];

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$operation = (string) ($_POST['operation'] ?? '');
			$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

			try {
				if ($operation === 'delete') {
					return $id && $this->delete((int) $id) ? 'deleted' : 'not_found';
				}

				if (in_array($operation, ['create', 'update'], true)) {
					$record = $_POST;
					if ($operation === 'update' && $id) {
						return $this->update((int) $id, $_POST) ? 'updated' : 'not_found';
					}
					if ($operation === 'create') {
						$this->create($_POST);
						return 'created';
					}
				}
			} catch (InvalidArgumentException $exception) {
				$error = $exception->getMessage();
				$action = $operation === 'update' ? 'edit' : 'create';
			}
		}

		if ($action === 'edit') {
			$id = filter_var($_GET['id'] ?? $_POST['id'] ?? null, FILTER_VALIDATE_INT);
			$savedRecord = $id ? $this->find((int) $id) : null;
			if ($savedRecord === null) {
				return 'not_found';
			}
			$record = $savedRecord;
			if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error !== '') {
				$record = array_merge($savedRecord, $_POST);
			}
		}

		$notice = '';
		$isForm = true;
		$isEdit = $action === 'edit';
		$jenisOptions = self::JENIS_OPTIONS;
		$zonaOptions = self::ZONA_OPTIONS;
		$estimasiOptions = self::ESTIMASI_OPTIONS;
		$statusOptions = self::STATUS_OPTIONS;
		$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

		require __DIR__ . '/../src/layanan.php';
		return null;
	}

	public function handleApi(): never
	{
		header('Content-Type: application/json; charset=utf-8');
		$path = trim((string) ($_SERVER['PATH_INFO'] ?? $_GET['path'] ?? ''), '/');
		$segments = $path === '' ? [] : explode('/', $path);
		if (($segments[0] ?? '') === 'layanan') {
			array_shift($segments);
		}
		$id = isset($segments[0]) && ctype_digit($segments[0]) ? (int) $segments[0] : null;
		$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

		try {
			if ($method === 'GET' && $id === null && $segments === []) {
				$services = (new zona_controller($this->database))->all(trim((string) ($_GET['search'] ?? '')));
				$this->respond(200, ['data' => $services]);
			}

			if ($method === 'GET' && $id !== null) {
				$record = $this->find($id);
				$this->respond($record === null ? 404 : 200, $record === null ? ['message' => 'Data tidak ditemukan.'] : ['data' => $record]);
			}

			if ($method === 'POST' && $id === null && $segments === []) {
				$createdId = $this->create($this->readJson());
				$this->respond(201, ['message' => 'Layanan berhasil ditambahkan.', 'data' => $this->find($createdId)]);
			}

			if ($method === 'PUT' && $id !== null) {
				if (!$this->update($id, $this->readJson())) {
					$this->respond(404, ['message' => 'Data tidak ditemukan.']);
				}
				$this->respond(200, ['message' => 'Layanan berhasil diperbarui.', 'data' => $this->find($id)]);
			}

			if ($method === 'DELETE' && $id !== null) {
				if (!$this->delete($id)) {
					$this->respond(404, ['message' => 'Data tidak ditemukan.']);
				}
				$this->respond(200, ['message' => 'Layanan berhasil dihapus.']);
			}

			$this->respond(404, ['message' => 'Route tidak ditemukan.']);
		} catch (InvalidArgumentException $exception) {
			$this->respond(422, ['message' => $exception->getMessage()]);
		} catch (Throwable $exception) {
			error_log($exception->getMessage());
			$this->respond(500, ['message' => 'Terjadi kesalahan pada server.']);
		}
	}

	private function validate(array $input): array
	{
		$nameInput = $input['nama_ekspedisi'] ?? '';
		$name = mb_strtoupper(trim((string) $nameInput), 'UTF-8');

		$typeInput = $input['jenis_layanan'] ?? '';
		$type = (string) $typeInput;

		$zoneInput = $input['zonasi'] ?? '';
		$zone = (string) $zoneInput;

		$tariffInput = $input['tarif'] ?? null;
		$tariff = filter_var($tariffInput, FILTER_VALIDATE_INT);

		$estimateInput = $input['estimasi'] ?? '';
		$estimate = trim((string) $estimateInput);

		$statusInput = $input['status'] ?? 'ON';
		$status = (string) $statusInput;

		$errorMessage = 'Data layanan, tarif, estimasi, dan status harus valid.';

		if ($name === '') {
			throw new InvalidArgumentException($errorMessage);
		}

		if (preg_match('/[<>\/"]/', $name) === 1) {
			throw new InvalidArgumentException('Nama ekspedisi tidak boleh mengandung karakter <, >, /, atau tanda kutip ganda.');
		}

		if (!in_array($type, self::JENIS_OPTIONS, true)) {
			throw new InvalidArgumentException($errorMessage);
		}

		if (!in_array($zone, self::ZONA_OPTIONS, true)) {
			throw new InvalidArgumentException($errorMessage);
		}

		if ($tariff === false || $tariff < 0) {
			throw new InvalidArgumentException($errorMessage);
		}

		if ($estimate === '') {
			throw new InvalidArgumentException($errorMessage);
		}

		if (!in_array($status, self::STATUS_OPTIONS, true)) {
			throw new InvalidArgumentException($errorMessage);
		}

		return [
			'nama_ekspedisi' => $name,
			'jenis_layanan' => $type,
			'zonasi' => $zone,
			'tarif' => $tariff,
			'estimasi' => $estimate,
			'status' => $status,
		];
	}

	private function readJson(): array
	{
		$input = json_decode((string) file_get_contents('php://input'), true);
		if (!is_array($input)) {
			throw new InvalidArgumentException('Body request harus berupa JSON yang valid.');
		}

		return $input;
	}

	private function respond(int $status, array $body): never
	{
		http_response_code($status);
		echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		exit;
	}
}