-- CONNEXION ET INSCRIPTION
.\mysql.exe -u root -p;

-- CREATION DE DATABASE
CREATE DATABASE IF NOT EXISTS Mini_Gestion_de_stock;

-- UTILISATION
USE Mini_Gestion_de_stock;

-- --------------------------------------------------
--      CREATION DU PRODUIT
-- --------------------------------------------------
CREATE TABLE produit(
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    reference_produit VARCHAR(255) NOT NULL UNIQUE,
    name_produit VARCHAR(255) NOT NULL UNIQUE,
    description_produit TEXT DEFAULT (name_produit),
    fiche_tech LONGTEXT NOT NULL,

    unite_produit VARCHAR(100) NOT NULL DEFAULT "quantites",

    image_size BIGINT UNSIGNED,
    image_url VARCHAR(255),

    marge_beneficiaire DECIMAL(5,2) NOT NULL,

    date_insertion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- INDEXATION DE RECHERCHE
    INDEX idx_name_produit (name_produit),

    -- CHECKING DU MARGE BENEFICIAIRE
    CONSTRAINT ch_benefice
        CHECK (marge_beneficiaire>=0)
);

-- -------------------------------------------------
--      INSERTION DES PRODUITS
-- -------------------------------------------------
INSERT INTO produit (
    reference_produit,
    name_produit,
    description_produit,
    fiche_tech,
    unite_produit,
    image_size,
    image_url,
    marge_beneficiaire
)
VALUES
(
    'CLV-001',
    'Clavier mécanique RGB',
    'Clavier mécanique pour gaming',
    'Switch Blue, RGB, USB, AZERTY',
    'pieces',
    245000,
    'images/clavier-rgb.jpg',
    25.00
),
(
    'SRS-002',
    'Souris sans fil Logitech',
    'Souris ergonomique sans fil',
    '1600 DPI, USB 2.4GHz, batterie AA',
    'pieces',
    185000,
    'images/souris-logitech.jpg',
    20.00
),
(
    'ECR-003',
    'Écran Samsung 24 pouces',
    'Moniteur Full HD pour ordinateur',
    '24 pouces, IPS, 1920x1080, HDMI',
    'pieces',
    350000,
    'images/ecran-samsung.jpg',
    18.50
),
(
    'CSQ-004',
    'Casque gaming HyperX',
    'Casque avec microphone intégré',
    'Audio stéréo, microphone, jack 3.5mm',
    'pieces',
    210000,
    'images/casque-hyperx.jpg',
    30.00
),
(
    'SSD-005',
    'SSD Kingston 500 Go',
    'Disque de stockage SSD',
    '500 Go, SATA III, 550 Mo/s',
    'pieces',
    165000,
    'images/ssd-kingston.jpg',
    15.00
),
(
    'RAM-006',
    'RAM Corsair 16 Go',
    'Mémoire vive DDR4',
    '16 Go, DDR4, 3200 MHz',
    'pieces',
    125000,
    'images/ram-corsair.jpg',
    22.00
),
(
    'WEB-007',
    'Webcam Logitech HD',
    'Webcam pour visioconférence',
    '1080p, 30 FPS, USB',
    'pieces',
    145000,
    'images/webcam.jpg',
    28.00
),
(
    'USB-008',
    'Clé USB SanDisk 64 Go',
    'Clé USB haute vitesse',
    '64 Go, USB 3.0',
    'pieces',
    85000,
    'images/usb-sandisk.jpg',
    35.00
),
(
    'HDD-009',
    'Disque dur externe 1 To',
    'Disque dur portable',
    '1 To, USB 3.0, 2.5 pouces',
    'pieces',
    195000,
    'images/hdd-externe.jpg',
    20.00
),
(
    'IMP-010',
    'Imprimante Canon Pixma',
    'Imprimante multifonction couleur',
    'Jet encre, WiFi, scanner intégré',
    'pieces',
    280000,
    'images/imprimante-canon.jpg',
    17.50
);