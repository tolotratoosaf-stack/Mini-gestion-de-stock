<?php
    $host = "localhost";
    $dbname = "mini_gestion_de_stock";
    $user = "root";
    $mdp = "";

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $mdp,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    function getDonnee(string $sql, array $execute = []):array{
        $STATEMENT = $GLOBALS["pdo"] -> prepare($sql);
        empty($execute) ? $STATEMENT -> execute() : $STATEMENT-> execute($execute);

        $resultat = $STATEMENT->fetchAll();

        return $resultat;
    }

    function sendDonnee(string $sql, array $execute = []):void {
        $STATEMENT = $GLOBALS["pdo"] -> prepare($sql);
        $STATEMENT -> execute($execute);
    }

    function getPrefix(string $nom): string {
        $nom = mb_strtolower($nom, "UTF-8");
        $nom = strtr($nom, [
            "à"=>"a","â"=>"a","ä"=>"a","é"=>"e","è"=>"e","ê"=>"e","ë"=>"e",
            "î"=>"i","ï"=>"i","ô"=>"o","ö"=>"o","ù"=>"u","û"=>"u","ü"=>"u",
            "ç"=>"c","œ"=>"oe"
        ]);
        $nom = preg_replace("/[^a-z]/", "", $nom);   // enleve espaces, tirets, chiffres

        return strtoupper(str_pad(substr($nom, 0, 3), 3, "X"));
    }

    function euro($n): string {
        return number_format((float) $n, 2, ",", " ");
    }
?>