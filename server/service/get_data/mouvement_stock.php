<?php
    require_once __DIR__ . "/../../config/config.php";

    // CHIFFRE D'AFFAIRE
    $sql_CA = (string) "SELECT
            COALESCE(SUM(prix_total), 0) AS chiffre_affaires,
            COALESCE(SUM(benefice), 0)   AS benefice
        FROM mouvement_stock WHERE type = 'vente'";
    $chiffre_affaire = getDonnee($sql_CA,[]);

    // TOTAL VENTE CONCLUS
    $sql_vente = (string) "SELECT SUM(quantite) AS total_vente FROM mouvement_stock WHERE motif LIKE '%vente%'";
    $total_vente = getDonnee($sql_vente,[]);

    // TOTAL APPROVISIONNEMENT
    $sql_achat = (string) "SELECT SUM(quantite) AS total_achat FROM mouvement_stock WHERE motif LIKE '%approvisionnement%'";
    $total_achat = getDonnee($sql_achat,[]);

    // LES PRODUITS DE MOUVEMENT DE STOCK
    $sql = (string) "SELECT m.*, p.reference_produit, p.name_produit
        FROM mouvement_stock m
        JOIN produit p ON p.id = id_produit
        ORDER BY m.date DESC, m.reference_transaction DESC";
    $mouvement_stock = getDonnee($sql,[]);
?>