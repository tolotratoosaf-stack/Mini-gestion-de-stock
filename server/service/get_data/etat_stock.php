<?php
    require_once __DIR__ . "/../../config/config.php";

    // TOUTES LES PRODUITS
    $sql_prd = (string) "SELECT COUNT(*) AS prd FROM vue_stock";
    $count_product_stock = getDonnee($sql_prd,[]);

    // NOMBRES DE PRODUIT EN STOCK
    $sql_stock = (string) "SELECT SUM(Quantite) AS stock_total FROM vue_stock";
    $stock = getDonnee($sql_stock,[]);

    // VALEUR STOCK
    $sql_valeur = (string) "SELECT SUM(Valeur_stock) AS valeur_stock FROM vue_stock";
    $valeur_stock = getDonnee($sql_valeur,[]);

    // LES PRODUITS A STOCK FAIBLE
    $sql_faible = (string) "SELECT COUNT(*) AS stock_faible FROM vue_stock WHERE Etat IN ('À réapprovisionner','Rupture')";
    $stock_faible = getDonnee($sql_faible,[]);

    // L'ETAT DE STOCK
    $sql = (string) "SELECT * FROM vue_stock";
    $all_stock = getDonnee($sql,[]);
?>