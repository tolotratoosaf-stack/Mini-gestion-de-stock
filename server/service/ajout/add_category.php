<?php
    require_once __DIR__ . "/../../config/config.php";

    // RECUPERATION DES DERNIERS REFERENCES
    $last_category = getDonnee("SELECT reference FROM categorie ORDER BY id DESC LIMIT 1",[]);
    $last_subcategory = getDonnee("SELECT reference FROM sous_categorie ORDER BY id DESC LIMIT 1",[]);

    function getLastReference(array $last):string {
        $num = $last ? ((int) substr($last[0]["reference"], 4)) + 1 : 1;
        $last_reference = sprintf("CAT-%03d", $num);

        return $last_reference;
    }

    $last_reference = getLastReference($last_category); #dernier reference pour le categorie parent
    $last_reference_subcategory = getLastReference($last_subcategory); #dernier reference pour la sous_categorie

    

    // AJOUT DES CATEGORIES
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        // les variables pour l'ajout
        $name = trim($_POST["name_category"]??"");
        $desc = trim($_POST["description_category"]??"");
        $categorie_parent_name = trim($_POST["categorie_parent"]??"");
        $subcategory = trim($_POST["new_subcategory"]??"");
        $description_subcategory = $_POST["desc_subcategorie"];

        // pour l'ajout du categorie parent
        if(!empty($name) && !empty($desc)) {
            $sql_categorie = "INSERT INTO
                categorie (reference, name_categorie, description)
                VALUES (:reference, :name, :desc)";
            $execute = [
                "reference" => $last_reference,
                "name" => $name,
                "desc" => $desc
            ];

            sendDonnee($sql_categorie, $execute);
        } elseif(!empty($categorie_parent_name) && !empty($subcategory)){ #pour la sous_categorie
            // RECUPARTION DE L'ID DU CATEGORIE PARENT
            $sql_parent = "SELECT id FROM categorie WHERE name_categorie = :name";
            $execute_subcategory = [
                "name"=>$categorie_parent_name
            ];
            $category_parent = getDonnee($sql_parent, $execute_subcategory);

            // AJOUT DANS LE SOUS-CATEGORIE
            $sql_subcategory = "INSERT INTO
                sous_categorie (reference, id_categorie_parent, name_sous_categorie, description)
                VALUES (:reference, :id_parent, :name_subcategory, :description);";
            $execute_send_subcategory = [
                "reference" => $last_reference_subcategory,
                "id_parent" => $category_parent[0]["id"],
                "name_subcategory" => $subcategory,
                "description" => $description_subcategory
            ];

            sendDonnee($sql_subcategory, $execute_send_subcategory);
        }

        header("Location: ../../../pages/add_product/insertion.php");
        exit;
    }
?>
