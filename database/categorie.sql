CREATE TABLE IF NOT EXISTS categorie (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reference CHAR(7) NOT NULL UNIQUE,
  name_categorie VARCHAR(100) NOT NULL,
  description TEXT NOT NULL DEFAULT (name_categorie),
  -- indexation de recherche
  INDEX idx_name_categorie (name_categorie)
);

INSERT IGNORE INTO categorie (reference, name_categorie, description)
VALUES
  ('CAT-001', 'Vêtements', 'Vêtements pour hommes, femmes et unisexe.'),
  ('CAT-002', 'Chaussures', 'Chaussures de ville, de sport et de saison.'),
  (
    'CAT-003',
    'Accessoires',
    'Sacs, ceintures, casquettes, montres et autres accessoires.'
  ),
  ('CAT-004', 'Autre', 'Produits ne rentrant dans aucune autre catégorie.');