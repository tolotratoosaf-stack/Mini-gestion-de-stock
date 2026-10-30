CREATE TABLE IF NOT EXISTS sous_categorie (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reference CHAR(7) NOT NULL UNIQUE,
  id_categorie_parent INT NOT NULL,
  name_sous_categorie VARCHAR(100) NOT NULL,
  description TEXT NOT NULL DEFAULT (name_sous_categorie),
  -- INDEXATION DE RECHERCHE
  INDEX idx_name_categorie (name_sous_categorie),
  -- CONTRAINTE
  CONSTRAINT fq_categorie_parent FOREIGN KEY (id_categorie_parent) REFERENCES categorie (id)
);

INSERT IGNORE INTO sous_categorie (reference, id_categorie_parent, name_sous_categorie, description)
VALUES
  -- Vêtements (CAT-001)
  (
    'SCA-001',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    'T-shirts',
    'T-shirts et hauts légers.'
  ),
  (
    'SCA-002',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    'Jeans',
    'Jeans et pantalons en denim.'
  ),
  (
    'SCA-003',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    'Robes',
    'Robes de tous styles.'
  ),
  (
    'SCA-004',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    'Vestes',
    'Vestes et manteaux.'
  ),
  (
    'SCA-011',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    'Chemises',
    'Chemises classiques et décontractées.'
  ),
  (
    'SCA-012',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    'Pantalons',
    'Pantalons de ville et décontractés.'
  ),
  (
    'SCA-013',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-001'
    ),
    'Pulls',
    'Pulls et gilets.'
  ),
  -- Chaussures (CAT-002)
  (
    'SCA-005',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-002'
    ),
    'Chaussures de sport',
    'Tennis, baskets et chaussures de running.'
  ),
  (
    'SCA-006',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-002'
    ),
    'Sandales',
    'Sandales et tongs.'
  ),
  (
    'SCA-014',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-002'
    ),
    'Bottes',
    'Bottes et bottines.'
  ),
  (
    'SCA-015',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-002'
    ),
    'Mocassins',
    'Mocassins et chaussures de ville.'
  ),
  -- Accessoires (CAT-003)
  (
    'SCA-007',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    'Sacs',
    'Sacs à dos, sacs à main et sacoches.'
  ),
  (
    'SCA-008',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    'Ceintures',
    'Ceintures en cuir et en tissu.'
  ),
  (
    'SCA-009',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    'Casquettes',
    'Casquettes, bonnets et chapeaux.'
  ),
  (
    'SCA-010',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    'Montres',
    'Montres classiques et sportives.'
  ),
  (
    'SCA-016',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    'Lunettes',
    'Lunettes de soleil et de vue.'
  ),
  (
    'SCA-017',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-003'
    ),
    'Écharpes',
    'Écharpes, foulards et gants.'
  ),
  -- Autre (CAT-004)
  (
    'SCA-018',
    (
      SELECT
        id
      FROM
        categorie
      WHERE
        reference = 'CAT-004'
    ),
    'Divers',
    'Articles divers.'
  );