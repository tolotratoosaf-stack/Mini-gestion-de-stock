<?php
    require_once __DIR__ . "/../../config/config.php";

    // AJOUT D'UN PRODUIT
    

    // Dernier reference produit
    // Reference produit : PREFIXE-001
    function getProductReference(string $categorie): string {
        $last = getDonnee(
            "SELECT reference_produit AS reference FROM produit ORDER BY id DESC LIMIT 1",
            []
        );

        // "VET-007" -> on garde ce qui suit le tiret (position 4)
        $num = $last ? ((int) substr($last[0]["reference"], 4)) + 1 : 1;

        return sprintf("%s-%03d", getPrefix($categorie), $num);
    }


    



    // AJOUT DE PRODUIT
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $titre = (string) trim($_POST["titre"]??"");
        $description = (string) trim($_POST["description"]??"");
        $categorie = (string) trim($_POST["categorie"]??"");
        $marque = (string) trim($_POST["marque"]??"");
        $prix_achat = (float) ($_POST["prix"]??0);
        $marge_beneficiaire = (float) ($_POST["marge_pourcentage"]??10);
        $quantite_initiale = (int) ($_POST["quantite"]??1);
        $subcategory = (string) ($_POST["sous_categorie"]??"");
        $cible = (array) ($_POST["cible"]??[]);
        $longueur = (float) ($_POST["longueur"]??1);
        $largeur = (float) ($_POST["largeur"]??1);
        $hauteur = (float) ($_POST["hauteur"]??1);


        // L'ID DU CATEGORIE
        $id_categorie = getDonnee(
            "SELECT id FROM categorie WHERE name_categorie = :categorie",
            [
                "categorie"=>$categorie
            ]
        );
        $id_categ = $id_categorie[0]["id"];

        // L'ID DU SUBCATEGORIE
        $id_sous = getDonnee(
            "SELECT id FROM sous_categorie WHERE name_sous_categorie IN (:subcategory)",
            [
                "subcategory"=>$subcategory
            ]
        );
        $id_subcategory = $id_sous[0]['id'];

        // CIBLE LISTE
        $cible_liste = (string) implode(",",$cible);

        $sql = "INSERT INTO
            produit (reference_produit,id_categorie,id_sous_categorie,name_produit,description_produit,
            categorie_principale,marque,prix_achat,marge_beneficiaire,cible,longueur,largeur,hauteur)
            VALUES (
                :reference, :id_categorie, :id_subcategory, :titre, :description, :categorie_principale, :marque, :prix, :benefice,
                :cible, :longueur, :largeur, :hauteur);";
        $execute = [
            "reference" => getProductReference($categorie),
            "id_categorie" => $id_categ,
            "id_subcategory" => $id_subcategory,
            "titre" => $titre,
            "description" => $description,
            "categorie_principale" => $categorie,
            "marque" => $marque,
            "prix" => $prix_achat,
            "benefice" => $marge_beneficiaire,
            "cible" => $cible_liste,
            "longueur" => $longueur,
            "largeur" => $largeur,
            "hauteur" => $hauteur
        ];

        sendDonnee($sql,$execute);

        header("Location: ../../../pages/add_product/insertion.php");
        exit;
    }
?>