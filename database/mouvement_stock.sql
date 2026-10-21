-- CONNEXION ET INSCRIPTION
.\mysql.exe -u root -p;

-- CREATION DE DATABASE
CREATE DATABASE IF NOT EXISTS Mini_Gestion_de_stock;

-- UTILISATION
USE Mini_Gestion_de_stock;

-- --------------------------------------------------
--      CREATION DU TABLE MOUVEMENT DE STOCK
-- --------------------------------------------------
CREATE TABLE mouvement_stock(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    id_produit INT UNSIGNED NOT NULL,
    mouvement ENUM("entree", "sortie") NOT NULL DEFAULT "entree",
    nombre_produit INT UNSIGNED NOT NULL DEFAULT 1,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    motif VARCHAR(255) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    prix_total DECIMAL(12,2) NOT NULL,

    -- INDEXATION DES RECHERCHES POSSIBLES
    INDEX idx_date (date),

    -- RELATION VERS INSERTION PRODUIT
    CONSTRAINT fq_idProduit_insertionProduit
        FOREIGN KEY (id_produit)
        REFERENCES produit(id)
);

-- ---------------------------------------------
--      ENREGISTREMENT DE STOCK INITIAL
-- ---------------------------------------------
INSERT INTO mouvement_stock (
    id_produit,
    mouvement,
    nombre_produit,
    motif,
    prix_unitaire,
    prix_total
)
SELECT
    p.id,
    'entree',
    d.quantite,
    'Stock initial',
    d.prix,
    d.quantite * d.prix
FROM (
    SELECT 'CLV-001' AS reference_produit, 50 AS quantite, 45000.00 AS prix
    UNION ALL SELECT 'SRS-002', 100, 25000.00
    UNION ALL SELECT 'ECR-003', 20, 450000.00
    UNION ALL SELECT 'CSQ-004', 35, 85000.00
    UNION ALL SELECT 'SSD-005', 40, 150000.00
    UNION ALL SELECT 'RAM-006', 60, 120000.00
    UNION ALL SELECT 'WEB-007', 25, 95000.00
    UNION ALL SELECT 'USB-008', 150, 18000.00
    UNION ALL SELECT 'HDD-009', 30, 180000.00
    UNION ALL SELECT 'IMP-010', 15, 350000.00
) AS d
JOIN produit p
    ON p.reference_produit = d.reference_produit;






-- -----------------------------------------------
--      TESTE MOUVEMENT DE STOCK POUR LES VENTES
-- -----------------------------------------------
-- Clavier : vente de 8 unités
SET @produit = (
    SELECT id FROM produit
    WHERE reference_produit = 'CLV-001'
);

INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
VALUES (
    @produit, 'sortie', 8,
    'Vente client', 65000.00, 520000.00
);


-- Souris : vente de 15 unités
SET @produit = (
    SELECT id FROM produit
    WHERE reference_produit = 'SRS-002'
);

INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
VALUES (
    @produit, 'sortie', 15,
    'Vente client', 35000.00, 525000.00
);


-- SSD : vente de 6 unités
SET @produit = (
    SELECT id FROM produit
    WHERE reference_produit = 'SSD-005'
);

INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
VALUES (
    @produit, 'sortie', 6,
    'Vente client', 195000.00, 1170000.00
);


-- RAM : vente de 10 unités
SET @produit = (
    SELECT id FROM produit
    WHERE reference_produit = 'RAM-006'
);

INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
VALUES (
    @produit, 'sortie', 10,
    'Vente client', 155000.00, 1550000.00
);


-- Clé USB : vente de 25 unités
SET @produit = (
    SELECT id FROM produit
    WHERE reference_produit = 'USB-008'
);

INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
VALUES (
    @produit, 'sortie', 25,
    'Vente client', 25000.00, 625000.00
);





-- ---------------------------------------------------------------
--      TESTE MOUVEMENT DE STOCK APPROVISIONNEMENT
-- ---------------------------------------------------------------
-- Réapprovisionnement des claviers
INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
SELECT
    id, 'entree', 30,
    'Reapprovisionnement fournisseur',
    45000.00, 1350000.00
FROM produit
WHERE reference_produit = 'CLV-001';


-- Réapprovisionnement des souris
INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
SELECT
    id, 'entree', 50,
    'Reapprovisionnement fournisseur',
    25000.00, 1250000.00
FROM produit
WHERE reference_produit = 'SRS-002';


-- Réapprovisionnement des SSD
INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
SELECT
    id, 'entree', 20,
    'Reapprovisionnement fournisseur',
    145000.00, 2900000.00
FROM produit
WHERE reference_produit = 'SSD-005';


-- Réapprovisionnement des clés USB
INSERT INTO mouvement_stock (
    id_produit, mouvement, nombre_produit,
    motif, prix_unitaire, prix_total
)
SELECT
    id, 'entree', 100,
    'Reapprovisionnement fournisseur',
    17500.00, 1750000.00
FROM produit
WHERE reference_produit = 'USB-008';