-- CONNEXION ET INSCRIPTION
mysql.exe - u root - p;

-- CREATION DE DATABASE
CREATE DATABASE IF NOT EXISTS Mini_Gestion_de_stock;

ALTER DATABASE mini_gestion_de_stock CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- UTILISATION
USE Mini_Gestion_de_stock;

-- --------------------------------------------------
--      CREATION DU PRODUIT
-- --------------------------------------------------
CREATE TABLE IF NOT EXISTS produit (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  reference_produit VARCHAR(255) NOT NULL UNIQUE,
  -- relation
  id_categorie INT NOT NULL,
  id_sous_categorie INT NULL,
  -- informations générales
  name_produit VARCHAR(255) NOT NULL UNIQUE,
  description_produit TEXT NULL,
  categorie_principale VARCHAR(150) NOT NULL,
  marque VARCHAR(100),
  prix_achat DECIMAL(10, 2) UNSIGNED NOT NULL DEFAULT 0,
  marge_beneficiaire DECIMAL(5, 2) NOT NULL,
  seuil_alerte INT UNSIGNED NOT NULL DEFAULT 5,
  -- classification
  sous_categorie VARCHAR(250),
  cible VARCHAR(250),
  date_insertion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  -- dimensions du produit (en cm)
  longueur INT UNSIGNED NOT NULL,
  largeur INT UNSIGNED NOT NULL,
  hauteur INT UNSIGNED NOT NULL,
  -- la marge ne peut pas être négative
  CONSTRAINT ch_benefice CHECK (marge_beneficiaire >= 0),
  CONSTRAINT fk_produit_categorie FOREIGN KEY (id_categorie) REFERENCES categorie (id) ON DELETE RESTRICT,
  CONSTRAINT fk_produit_sous_categorie FOREIGN KEY (id_sous_categorie) REFERENCES sous_categorie (id) ON DELETE RESTRICT
);

-- -------------------------------------------------
--      INSERTION DES PRODUITS
--      Les ids de catégorie / sous-catégorie sont retrouvés par leur référence.
-- -------------------------------------------------
INSERT IGNORE INTO produit (
  reference_produit,
  id_categorie,
  id_sous_categorie,
  name_produit,
  description_produit,
  marque,
  prix_achat,
  marge_beneficiaire,
  cible,
  longueur,
  largeur,
  hauteur
)
VALUES
  (
    'VET-001',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-001'
    ),
    'T-shirt classique',
    'T-shirt en coton confortable pour usage quotidien.',
    'BasicWear',
    8.00,
    35.00,
    'Homme',
    30,
    25,
    2
  ),
  (
    'VET-002',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-002'
    ),
    'Jean slim',
    'Jean slim en denim bleu.',
    'DenimStyle',
    18.00,
    40.00,
    'Homme',
    35,
    28,
    4
  ),
  (
    'VET-003',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-003'
    ),
    'Robe d été',
    'Robe légère idéale pour les journées chaudes.',
    'Elegance',
    20.00,
    45.00,
    'Femme',
    40,
    30,
    3
  ),
  (
    'VET-004',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-004'
    ),
    'Veste en jean',
    'Veste en denim adaptée aux saisons fraîches.',
    'DenimStyle',
    25.00,
    30.00,
    'Unisexe',
    45,
    35,
    6
  ),
  (
    'CHS-001',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-002'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-005'
    ),
    'Tennis de sport',
    'Chaussures de sport pour les activités quotidiennes.',
    'SportFlex',
    30.00,
    40.00,
    'Homme',
    32,
    22,
    12
  ),
  (
    'CHS-002',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-002'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-006'
    ),
    'Sandales élégantes',
    'Sandales légères pour les sorties estivales.',
    'Bella',
    12.00,
    50.00,
    'Femme',
    28,
    20,
    8
  ),
  (
    'ACC-001',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-007'
    ),
    'Sac à dos urbain',
    'Sac à dos avec plusieurs compartiments.',
    'UrbanBag',
    22.00,
    35.00,
    'Unisexe',
    45,
    30,
    15
  ),
  (
    'ACC-002',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-008'
    ),
    'Ceinture en cuir',
    'Ceinture classique pour compléter une tenue.',
    'LeatherPro',
    10.00,
    40.00,
    'Homme',
    110,
    4,
    2
  ),
  (
    'ACC-003',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-009'
    ),
    'Casquette sportive',
    'Casquette ajustable pour le sport et les loisirs.',
    'SportFlex',
    6.00,
    50.00,
    'Unisexe',
    20,
    18,
    12
  ),
  (
    'ACC-004',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    (
      SELECT
        id
      FROM
        sous_categorie
      WHERE
        reference = 'SCA-010'
    ),
    'Montre classique',
    'Montre élégante avec bracelet ajustable.',
    'TimeStyle',
    35.00,
    45.00,
    'Unisexe',
    25,
    5,
    2
  );