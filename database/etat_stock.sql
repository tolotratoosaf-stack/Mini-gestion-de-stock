-- CONNEXION ET INSCRIPTION
.\mysql.exe -u root -p;

-- CREATION DE DATABASE
CREATE DATABASE IF NOT EXISTS Mini_Gestion_de_stock;

-- UTILISATION
USE Mini_Gestion_de_stock;

-- --------------------------------------------------
--      CREATION DU VIEW ETAT DE STOCK
-- --------------------------------------------------
CREATE OR REPLACE VIEW etat_stock AS
    SELECT
        p.id AS id_produit,
        p.reference_produit,
        p.name_produit,

        COALESCE(
            SUM(
                CASE
                    WHEN m.mouvement = 'entree'
                        THEN m.nombre_produit

                    WHEN m.mouvement = 'sortie'
                        THEN -m.nombre_produit

                    ELSE 0
                END
            ),
            0
        ) AS stock_disponible

    FROM produit p

    LEFT JOIN mouvement_stock m
        ON p.id = m.id_produit

    GROUP BY
        p.id,
        p.reference_produit,
        p.name_produit;