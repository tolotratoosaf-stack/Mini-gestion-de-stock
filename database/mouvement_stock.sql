-- CONNEXION ET INSCRIPTION
mysql.exe - u root - p;

-- CREATION DE DATABASE
CREATE DATABASE IF NOT EXISTS Mini_Gestion_de_stock;

-- UTILISATION
USE Mini_Gestion_de_stock;

-- --------------------------------------------------
--      CREATION DU TABLE MOUVEMENT DE STOCK
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS mouvement_stock (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  reference_transaction CHAR(10) NOT NULL UNIQUE,
  -- produit concerné
  id_produit INT UNSIGNED NOT NULL,
  -- à propos du mouvement
  type ENUM ('achat', 'vente', 'ajustement', 'autre') NOT NULL DEFAULT 'achat',
  quantite INT NOT NULL DEFAULT 1,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  motif VARCHAR(255) NOT NULL,
  -- montants
  prix_unitaire DECIMAL(12, 2) NOT NULL,
  prix_total DECIMAL(12, 2) NOT NULL,
  benefice DECIMAL(12, 2),
  -- index de recherche par date
  INDEX idx_date (`date`),
  -- relation vers produit
  CONSTRAINT fk_mouvement_produit FOREIGN KEY (id_produit) REFERENCES produit (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  -- achat/vente : quantité > 0 ; ajustement : quantité non nulle (peut être négative)
  CONSTRAINT ch_quantite_type CHECK (
    (
      type IN ('achat', 'vente')
      AND quantite > 0
    )
    OR (
      type = 'ajustement'
      AND quantite <> 0
    )
    OR type = 'autre'
  )
);

-- ---------------------------------------------
--      ENREGISTREMENT DE STOCK INITIAL
-- ---------------------------------------------
INSERT IGNORE INTO mouvement_stock (
  reference_transaction,
  id_produit,
  type,
  quantite,
  `date`,
  motif,
  prix_unitaire,
  prix_total,
  benefice
)
SELECT
  m.reference_transaction,
  p.id,
  m.type,
  m.quantite,
  m.date_mouvement,
  m.motif,
  m.prix_unitaire,
  m.prix_total,
  m.benefice
FROM
  (
    -- Approvisionnement initial
    SELECT
      'TSH/AC/001' AS reference_transaction,
      'VET-001' AS reference_produit,
      'achat' AS type,
      50 AS quantite,
      '2026-10-01 09:00:00' AS date_mouvement,
      'Approvisionnement initial' AS motif,
      8.00 AS prix_unitaire,
      400.00 AS prix_total,
      NULL AS benefice
    UNION ALL
    SELECT
      'JEA/AC/001',
      'VET-002',
      'achat',
      25,
      '2026-10-01 10:00:00',
      'Approvisionnement initial',
      18.00,
      450.00,
      NULL
    UNION ALL
    SELECT
      'ROB/AC/001',
      'VET-003',
      'achat',
      15,
      '2026-10-02 09:30:00',
      'Approvisionnement initial',
      20.00,
      300.00,
      NULL
    UNION ALL
    SELECT
      'VES/AC/001',
      'VET-004',
      'achat',
      12,
      '2026-10-02 11:00:00',
      'Approvisionnement initial',
      25.00,
      300.00,
      NULL
    UNION ALL
    SELECT
      'TEN/AC/001',
      'CHS-001',
      'achat',
      20,
      '2026-10-03 09:00:00',
      'Approvisionnement initial',
      30.00,
      600.00,
      NULL
    UNION ALL
    SELECT
      'SAN/AC/001',
      'CHS-002',
      'achat',
      30,
      '2026-10-03 10:00:00',
      'Approvisionnement initial',
      12.00,
      360.00,
      NULL
    UNION ALL
    SELECT
      'SAC/AC/001',
      'ACC-001',
      'achat',
      18,
      '2026-10-04 09:00:00',
      'Approvisionnement initial',
      22.00,
      396.00,
      NULL
    UNION ALL
    SELECT
      'CEI/AC/001',
      'ACC-002',
      'achat',
      40,
      '2026-10-04 10:00:00',
      'Approvisionnement initial',
      10.00,
      400.00,
      NULL
    UNION ALL
    SELECT
      'CAS/AC/001',
      'ACC-003',
      'achat',
      35,
      '2026-10-05 09:00:00',
      'Approvisionnement initial',
      6.00,
      210.00,
      NULL
    UNION ALL
    SELECT
      'MON/AC/001',
      'ACC-004',
      'achat',
      10,
      '2026-10-05 10:00:00',
      'Approvisionnement initial',
      35.00,
      350.00,
      NULL
      -- Ventes
    UNION ALL
    SELECT
      'TSH/VE/001',
      'VET-001',
      'vente',
      3,
      '2026-10-06 14:00:00',
      'Vente conclue',
      10.80,
      32.40,
      8.40
    UNION ALL
    SELECT
      'JEA/VE/001',
      'VET-002',
      'vente',
      2,
      '2026-10-07 15:00:00',
      'Vente conclue',
      25.20,
      50.40,
      14.40
    UNION ALL
    SELECT
      'ROB/VE/001',
      'VET-003',
      'vente',
      2,
      '2026-10-08 11:00:00',
      'Vente conclue',
      29.00,
      58.00,
      18.00
    UNION ALL
    SELECT
      'TEN/VE/001',
      'CHS-001',
      'vente',
      1,
      '2026-10-09 16:00:00',
      'Vente conclue',
      42.00,
      42.00,
      12.00
    UNION ALL
    SELECT
      'SAN/VE/001',
      'CHS-002',
      'vente',
      4,
      '2026-10-10 13:00:00',
      'Vente conclue',
      18.00,
      72.00,
      24.00
      -- Ajustement
    UNION ALL
    SELECT
      'ROB/AJ/001',
      'VET-003',
      'ajustement',
      -1,
      '2026-10-11 10:00:00',
      'Produit endommagé',
      20.00,
      20.00,
      NULL
  ) AS m
  INNER JOIN produit AS p ON p.reference_produit = m.reference_produit;