-- CONNEXION ET INSCRIPTION
mysql.exe - u root - p;

-- CREATION DE DATABASE
CREATE DATABASE IF NOT EXISTS Mini_Gestion_de_stock;

-- UTILISATION
USE Mini_Gestion_de_stock;

-- --------------------------------------------------
--      CREATION DU VIEW ETAT DE STOCK
-- --------------------------------------------------
CREATE
OR REPLACE VIEW vue_stock AS
SELECT
  t.id_produit,
  t.reference_produit,
  t.Produit,
  t.Categorie,
  t.Quantite,
  t.Seuil_alerte,
  t.Cout_unitaire,
  ROUND(t.Cout_unitaire * (1 + t.marge_beneficiaire / 100), 2) AS Prix_vente,
  ROUND(t.Quantite * t.Cout_unitaire, 2) AS Valeur_stock,
  CASE
    WHEN t.Quantite <= 0 THEN 'Rupture'
    WHEN t.Quantite <= t.Seuil_alerte THEN 'À réapprovisionner'
    ELSE 'Disponible'
  END AS Etat
FROM
  (
    SELECT
      p.id AS id_produit,
      p.reference_produit AS reference_produit,
      p.name_produit AS Produit,
      p.categorie_principale AS Categorie,
      p.prix_achat AS Cout_unitaire,
      p.marge_beneficiaire,
      p.seuil_alerte AS Seuil_alerte,
      COALESCE(
        SUM(
          CASE m.type
            WHEN 'achat' THEN m.quantite
            WHEN 'vente' THEN - m.quantite
            WHEN 'ajustement' THEN m.quantite
            ELSE 0
          END
        ),
        0
      ) AS Quantite
    FROM
      produit AS p
      LEFT JOIN mouvement_stock AS m ON m.id_produit = p.id
    GROUP BY
      p.id,
      p.name_produit,
      p.categorie_principale,
      p.prix_achat,
      p.marge_beneficiaire,
      p.seuil_alerte
  ) AS t;