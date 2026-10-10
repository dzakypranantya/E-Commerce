USE `e-commerce`;

CREATE TABLE IF NOT EXISTS layanan (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_ekspedisi VARCHAR(100) NOT NULL,
    jenis_layanan ENUM('Reguler', 'Express', 'Cargo') NOT NULL,
    zonasi ENUM('Zona 1', 'Zona 2', 'Zona 3', 'Zona 4') NOT NULL,
    tarif INT UNSIGNED NOT NULL,
    estimasi VARCHAR(100) NOT NULL,
    status ENUM('ON', 'OFF') NOT NULL DEFAULT 'ON',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @status_column_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'layanan'
      AND COLUMN_NAME = 'status'
);
SET @status_migration = IF(
    @status_column_exists = 0,
    'ALTER TABLE layanan ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT ''ON'' AFTER estimasi',
    'ALTER TABLE layanan MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT ''ON'''
);
PREPARE status_migration_statement FROM @status_migration;
EXECUTE status_migration_statement;
DEALLOCATE PREPARE status_migration_statement;

UPDATE layanan
SET status = CASE WHEN UPPER(status) IN ('ON', 'AKTIF') THEN 'ON' ELSE 'OFF' END;

ALTER TABLE layanan MODIFY COLUMN status ENUM('ON', 'OFF') NOT NULL DEFAULT 'ON';

INSERT INTO layanan (nama_ekspedisi, jenis_layanan, zonasi, tarif, estimasi)
SELECT 'JNE', 'Reguler', 'Zona 1', 9000, '2-3 Hari'
WHERE NOT EXISTS (SELECT 1 FROM layanan);

INSERT INTO layanan (nama_ekspedisi, jenis_layanan, zonasi, tarif, estimasi)
SELECT 'SiCepat', 'Express', 'Zona 3', 25000, '3-5 Hari'
WHERE (SELECT COUNT(*) FROM layanan) = 1;