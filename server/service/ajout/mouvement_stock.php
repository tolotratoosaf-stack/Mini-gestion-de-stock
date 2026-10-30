<?php
    require_once __DIR__ . "/../../config/config.php";

    $sql_produit = (string) "SELECT 
        p.id, p.name_produit, p.reference_produit, p.prix_achat,
        v.Quantite, v.Cout_unitaire, v.Prix_vente 
        FROM produit p
        JOIN vue_stock v ON p.id = v.id_produit
        ORDER BY name_produit";
    $all_product = getDonnee($sql_produit,[]);

    $swl_benef = (string) "SELECT marge_beneficiaire,id";


    // Code du type d'operation
    function getTypeCode(string $type): string {
        return match ($type) {
            "achat"      => "AC",
            "vente"      => "VE",
            "ajustement" => "AJ",
            default      => throw new InvalidArgumentException("Type d'opération inconnu : $type"),
        };
    }

    // Reference de transaction : CAS/AC/001
    function getTransactionReference(string $nomProduit, string $type): string {
        $prefix = getPrefix($nomProduit) . "/" . getTypeCode($type) . "/";   // "CAS/AC/"

        $last = getDonnee(
            "SELECT reference_transaction AS reference
            FROM mouvement_stock
            WHERE reference_transaction LIKE :prefix
            ORDER BY CAST(SUBSTRING(reference_transaction, 8) AS UNSIGNED) DESC
            LIMIT 1",
            ["prefix" => $prefix . "%"]
        );

        $num = $last ? ((int) substr($last[0]["reference"], 7)) + 1 : 1;

        return sprintf("%s%03d", $prefix, $num);
    }

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $action_type = $_POST["type_mouvement"];
        $produit = $_POST["produit"];
        $quantite = $_POST["quantite"];
        $prix = (float) ($_POST["prix_unitaire"] ?? 0);
        $date = $_POST["date"] . " " . $_POST["heure"];
        $motif = $_POST["motif"];
        $observation = $_POST["observation"];
        $sens = $_POST["sens"]??"retrait";

        // Retrouver le produit par sa reference
        $trouve = getDonnee(
            "SELECT p.id, p.name_produit, p.prix_achat, v.Quantite AS stock
             FROM produit p
             JOIN vue_stock v ON v.id_produit = p.id
             WHERE p.reference_produit = :ref",
            ["ref" => $produit]
        );

        $typeValide = in_array($action_type, ["achat", "vente", "ajustement"], true);

        if($trouve && $typeValide && $quantite > 0 && $motif !== ""){
            $p = $trouve[0];

            if ($action_type === "ajustement") {
                $prix = (float) $p["prix_achat"];
            }

            if($action_type === "ajustement" && $sens === "retrait") {
                $quantite = -$quantite;
            }

            $sortie = $action_type === "vente" || $quantite < 0;

            if(!$sortie || abs($quantite) <= (int) $p['stock']) {
                $benefice = $action_type === "vente"
                    ? round(($prix - (float) $p["prix_achat"]) * $quantite, 2)
                    : null;
                
                
                $sql = "INSERT INTO mouvement_stock
                    (reference_transaction, id_produit, type, quantite, date, motif, prix_unitaire, prix_total, benefice)
                    VALUES
                    (
                        :reference, :id_produit, :type, :quantite, :date, :motif, :prix_unitaire, :prix_total, :benefice
                    );";
                    $params = [
                        "reference" => getTransactionReference($p['name_produit'], $action_type),
                        "id_produit" => $p["id"],
                        "type" => $action_type,
                        "quantite" => $quantite,
                        "date" => $date,
                        "motif" => $motif,
                        "prix_unitaire" => $prix,
                        "prix_total" => round(abs($quantite) * $prix, 2),
                        "benefice" => $benefice
                    ];
            }
        }


        sendDonnee($sql, $params);
        header("Location: ../../../pages/approvisionnement_vente/approvisionnement.php");
        exit;
    }
?>