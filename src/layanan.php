<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $isForm ? ($isEdit ? 'Edit' : 'Tambah') . ' Layanan Ekspedisi' : 'Master Ekspedisi & Zonasi' ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="header">
		<h1><?= $isForm ? ($isEdit ? 'EDIT LAYANAN & ZONASI' : 'TAMBAH LAYANAN & ZONASI') : 'MASTER ADMIN - EKSPEDISI' ?></h1>
		<div class="line"></div>
		<?php if ($isForm): ?>
			<a href="index.php" class="btn btn-primary btn-action">Kembali</a>
		<?php else: ?>
			<a href="index.php?action=create" class="btn btn-primary btn-action">+ Tambah Layanan</a>
		<?php endif; ?>
	</div>

	<div class="main-container-page">
		<?php if ($notice !== ''): ?>
			<div class="notice" role="status"><?= $escape($notice) ?></div>
		<?php endif; ?>

		<?php if ($isForm): ?>
			<div class="card-form form-center">
				<h2><?= $isEdit ? 'Form Edit Layanan' : 'Form Tambah Layanan Baru' ?></h2>
				<?php if ($error !== ''): ?>
					<div class="notice notice-error" role="alert"><?= $escape($error) ?></div>
				<?php endif; ?>
				<form action="index.php<?= $isEdit ? '?action=edit&id=' . (int) $record['id'] : '' ?>" method="POST">
					<input type="hidden" name="operation" value="<?= $isEdit ? 'update' : 'create' ?>">
					<?php if ($isEdit): ?>
						<input type="hidden" name="id" value="<?= (int) $record['id'] ?>">
					<?php endif; ?>
					<div class="form-group">
						<label for="nama_ekspedisi">Nama Ekspedisi/Kurir</label>
						<input class="form-control" type="text" id="nama_ekspedisi" name="nama_ekspedisi" value="<?= $escape($record['nama_ekspedisi'] ?? '') ?>" placeholder="Masukkan Nama Ekspedisi" pattern='[^<>/"]+' title='Karakter &lt;, &gt;, /, dan tanda kutip ganda tidak diperbolehkan.' required oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Nama ekspedisi wajib diisi.' : 'Karakter <, >, /, dan tanda kutip ganda tidak diperbolehkan.')" oninput="this.value = this.value.toLocaleUpperCase('id-ID'); this.setCustomValidity(this.validity.patternMismatch ? 'Karakter <, >, /, dan tanda kutip ganda tidak diperbolehkan.' : '')">
					</div>
					<div class="form-group">
						<label for="jenis_layanan">Jenis Layanan</label>
						<select class="form-select" id="jenis_layanan" name="jenis_layanan" required oninvalid="this.setCustomValidity('Silakan pilih jenis layanan pengiriman.')" onchange="this.setCustomValidity('')">
							<option value="" disabled hidden <?= empty($record['jenis_layanan']) ? 'selected' : '' ?>>Pilih Jenis Layanan</option>
							<?php foreach ($jenisOptions as $option): ?>
								<option value="<?= $escape($option) ?>" <?= ($record['jenis_layanan'] ?? '') === $option ? 'selected' : '' ?>><?= $escape($option === 'Express' ? 'Express' : ($option === 'Cargo' ? 'Cargo' : $option)) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="zonasi">Zonasi Pengiriman</label>
						<select class="form-select" id="zonasi" name="zonasi" required oninvalid="this.setCustomValidity('Silakan pilih zona tujuan pengiriman.')" onchange="this.setCustomValidity('')">
							<option value="" disabled hidden <?= empty($record['zonasi']) ? 'selected' : '' ?>>Pilih Zonasi</option>
							<?php foreach ($zonaOptions as $option): ?>
								<?php $zonaLabel = ['Zona 1' => 'Jabodetabek / Dalam Kota', 'Zona 2' => 'Antar Kota / Pulau Jawa', 'Zona 3' => 'Luar Pulau Jawa', 'Zona 4' => 'Pelosok / Daerah Terpencil'][$option]; ?>
								<option value="<?= $escape($option) ?>" <?= ($record['zonasi'] ?? '') === $option ? 'selected' : '' ?>><?= $escape($option . ' (' . $zonaLabel . ')') ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="tarif">Tarif Dasar (Rp)</label>
						<input class="form-control" type="number" id="tarif" name="tarif" min="0" step="1" value="<?= $escape($record['tarif'] ?? '') ?>" placeholder="Masukkan Tarif Dasar" required oninvalid="this.setCustomValidity('Masukkan tarif dasar layanan.')" oninput="this.setCustomValidity('')">
					</div>
					<div class="form-group">
						<label for="estimasi">Estimasi Pengiriman</label>
						<select class="form-select" id="estimasi" name="estimasi" required>
							<option value="" disabled hidden <?= empty($record['estimasi']) ? 'selected' : '' ?>>Pilih Estimasi Pengiriman</option>
							<?php if (!empty($record['estimasi']) && !in_array($record['estimasi'], $estimasiOptions, true)): ?>
								<option value="<?= $escape($record['estimasi']) ?>" selected><?= $escape($record['estimasi']) ?> (nilai saat ini)</option>
							<?php endif; ?>
							<?php foreach ($estimasiOptions as $option): ?>
								<option value="<?= $escape($option) ?>" <?= ($record['estimasi'] ?? '') === $option ? 'selected' : '' ?>><?= $escape($option) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="status">Status Layanan</label>
						<select class="form-select" id="status" name="status" required>
							<?php foreach ($statusOptions as $option): ?>
								<option value="<?= $escape($option) ?>" <?= ($record['status'] ?? 'ON') === $option ? 'selected' : '' ?>><?= $escape($option === 'ON' ? 'On' : 'Off') ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<button type="submit" class="btn btn-primary btn-submit"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Layanan' ?></button>
				</form>
			</div>
		<?php else: ?>
			<div class="card-table full-width">
				<div class="card-header-flzex">
					<h2>List Zona & Ekspedisi Pengiriman</h2>
					<form action="index.php" method="GET" class="search-form">
						<input type="search" name="search" value="<?= $escape($search) ?>" placeholder="Cari ekspedisi / zona..." class="form-control search-input" aria-label="Cari ekspedisi atau zona">
					</form>
				</div>
				<div class="table-scroll">
					<table class="table table-hover data-table">
						<thead>
							<tr>
								<th>No</th><th>Ekspedisi</th><th>Jenis Layanan</th><th>Zonasi Wilayah</th>
								<th>Tarif Dasar</th><th>Estimasi</th><th>Status</th><th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($services === []): ?>
								<tr><td colspan="8" class="empty-state">Belum ada layanan yang cocok.</td></tr>
							<?php else: ?>
								<?php foreach ($services as $index => $service): ?>
									<tr>
										<td><?= $index + 1 ?></td>
										<td><strong><?= $escape($service['nama_ekspedisi']) ?></strong></td>
										<td><span class="badge <?= $service['jenis_layanan'] === 'Express' ? 'badge-express' : 'badge-reguler' ?>"><?= $escape($service['jenis_layanan']) ?></span></td>
										<td><?= $escape($service['zonasi']) ?></td>
										<td>Rp <?= number_format((int) $service['tarif'], 0, ',', '.') ?></td>
										<td><?= $escape($service['estimasi']) ?></td>
										<td><span class="status-badge <?= $service['status'] === 'ON' ? 'status-active' : 'status-inactive' ?>"><?= $escape($service['status'] === 'ON' ? 'On' : 'Off') ?></span></td>
										<td class="action-cell">
											<a href="index.php?action=edit&amp;id=<?= (int) $service['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
											<form action="index.php" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
												<input type="hidden" name="operation" value="delete">
												<input type="hidden" name="id" value="<?= (int) $service['id'] ?>">
												<button type="submit" class="btn btn-sm btn-delete">Hapus</button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	</div>
</body>
</html>