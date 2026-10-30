<?php
    require_once __DIR__ . "/../config/config.php";

    $_GET["pagination"] = 1;
    $p = (int) $_GET["pagination"] ?? 1;
    $limit = (int) 10;
    $offset = (int) ($p - 1) * $limit;

    $sql_produit = (string) "SELECT
            p.reference_produit,
            p.name_produit,
            p.cible,
            c.name_categorie AS categorie_principale,
            v.Quantite AS stock,
            v.Seuil_alerte,
            v.Etat,
            ROUND((p.prix_achat * (p.marge_beneficiaire+100) / 100),2) AS prix_vente
            FROM produit p
            JOIN categorie c ON c.id = p.id_categorie
            JOIN vue_stock v ON v.Produit = name_produit
        ";
    $all_products = getDonnee($sql_produit, []);

    $sql_count = (string) "SELECT COUNT(*) AS nombre_produit FROM produit"; 
    $count_product = getDonnee($sql_count, []);

    $sql_all_categorie = (string) "SELECT name_categorie AS categorie FROM categorie ORDER BY name_categorie";
    $all_categorie = getDonnee($sql_all_categorie, []);

    $sql_categorie = (string) "SELECT COUNT(*) AS categorie FROM categorie";
    $categorie = getDonnee($sql_categorie, []);

    $sql_subcategory = (string) "SELECT name_sous_categorie AS subcategory FROM sous_categorie ORDER BY id ASC";
    $subcategory = getDonnee($sql_subcategory);

    // TOTAL DU BENEFICE
?>