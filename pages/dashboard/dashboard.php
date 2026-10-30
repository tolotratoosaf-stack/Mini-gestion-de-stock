<?php
  require_once __DIR__ . "/../../server/service/tout_produit.php";
  require_once __DIR__ . "/../../server/service/get_data/mouvement_stock.php";

  function getPercent(float $valeur, float $total, $decimales = 0 ):float{
    $resultat = (int) ($valeur / $total * 100);
    return round($resultat,$decimales);
  }
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Gestion de stock</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="dashboard.css" />
  </head>
  <body>
    <?php $page = 'dashboard'; require_once __DIR__ . "/../components/sideBar.php"; ?>

    <main>
      <h1>Bienvenue dans votre gestion de stock</h1>

      <!-- Indicateurs généraux -->
      <section class="hero" aria-labelledby="titre-resume">
        <h2 id="titre-resume" class="sr-only">Résumé</h2>
        <div class="card">
          <h3>Méthode de valorisation</h3>
          <p>FIFO</p>
        </div>
        <div class="card">
          <h3>Total produits</h3>
          <p><?= $count_product[0]["nombre_produit"]?> <span class="unit">prd</span></p>
        </div>
        <div class="card">
          <h3>Total bénéfice</h3>
          <p><?= euro($chiffre_affaire[0]["benefice"])?> <span class="unit">€</span></p>
        </div>
      </section>

      <!-- Statistiques par période -->
      <section class="stats" aria-labelledby="titre-stats">
        <header class="section-head">
          <div>
            <h2 id="titre-stats">Statistiques</h2>
            <p class="section-sub">Vue d'ensemble de votre activité</p>
          </div>
          <nav aria-label="Période">
            <ul>
              <li><a href="?periode=semaine" aria-current="true">Semaine</a></li>
              <li><a href="?periode=mois">Mois</a></li>
              <li><a href="?periode=annee">Année</a></li>
              <li><a href="?periode=tous">Tous</a></li>
            </ul>
          </nav>
        </header>

        <div class="stats-grid">
          <!-- Carte principale -->
          <div class="card stat-featured">
            <div>
              <h3>Chiffre d'affaires</h3>
              <p class="stat-value"><?= euro($chiffre_affaire[0]["chiffre_affaires"])?> <span class="unit">€</span></p>
              <p class="stat-note">Total des ventes sur la période</p>
            </div>

            <div class="split">
              <div class="split-bar" role="img" aria-label="Répartition du chiffre d'affaires">
                <span class="seg seg-direct" style="flex: <?= getPercent($total_achat[0]["total_achat"], $chiffre_affaire[0]["chiffre_affaires"],1)?>"></span>
                <span class="seg seg-indirect" style="flex: 0"></span>
                <span class="seg seg-benefice" style="flex: <?= getPercent($chiffre_affaire[0]["benefice"], $chiffre_affaire[0]["chiffre_affaires"],1)?>"></span>
              </div>
              <ul class="split-legend">
                <li><i class="dot seg-direct"></i>Charges directes</li>
                <li><i class="dot seg-indirect"></i>Charges indirectes</li>
                <li><i class="dot seg-benefice"></i>Bénéfice</li>
              </ul>
            </div>
          </div>

          <!-- Charges directes -->
          <div class="card stat-card is-warn">
            <div class="stat-head">
              <span class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" class="icon"><path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3.3 7L12 12l8.7-5M12 22V12"/></svg>
              </span>
              <h3>Charges directes</h3>
            </div>
            <p class="stat-value"><?= euro($total_achat[0]["total_achat"])?> <span class="unit">€</span></p>
            <div class="stat-foot">
              <div class="meter"><span style="width: <?= getPercent($total_achat[0]["total_achat"], $chiffre_affaire[0]["chiffre_affaires"],1)?>%"></span></div>
              <span class="chip"><?= euro(getPercent($total_achat[0]["total_achat"], $chiffre_affaire[0]["chiffre_affaires"],1))?> % du CA</span>
            </div>
          </div>

          <!-- Charges indirectes -->
          <div class="card stat-card is-bad">
            <div class="stat-head">
              <span class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" class="icon"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
              </span>
              <h3>Charges indirectes</h3>
            </div>
            <p class="stat-value">0 <span class="unit">€</span></p>
            <div class="stat-foot">
              <div class="meter"><span style="width: 0%"></span></div>
              <span class="chip">0 % du CA</span>
            </div>
          </div>

          <!-- Bénéfice -->
          <div class="card stat-card is-ok">
            <div class="stat-head">
              <span class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" class="icon"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
              </span>
              <h3>Bénéfice</h3>
            </div>
            <p class="stat-value"><?= euro($chiffre_affaire[0]["benefice"])?> <span class="unit">€</span></p>
            <div class="stat-foot">
              <div class="meter"><span style="width: <?= getPercent($chiffre_affaire[0]["benefice"], $chiffre_affaire[0]["chiffre_affaires"],1)?>%"></span></div>
              <span class="chip"><?= euro(getPercent($chiffre_affaire[0]["benefice"], $chiffre_affaire[0]["chiffre_affaires"],1))?> % du CA</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Fournisseurs -->
      <section aria-labelledby="titre-fournisseurs">
        <h2 id="titre-fournisseurs">Fournisseurs</h2>
        <table>
          <thead>
            <tr>
              <th scope="col"><input type="checkbox" id="all" aria-label="Tout sélectionner"></th>
              <th scope="col">Fournisseur</th>
              <th scope="col">Catégorie</th>
              <th scope="col">Statut</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="checkbox" name="frns[]" aria-label="Sélectionner Arnaud Goku"></td>
              <td>Arnaud Goku</td>
              <td>Informatique</td>
              <td>Bon</td>
              <td><a href="mailto:">Contacter</a></td>
            </tr>
            <tr>
              <td><input type="checkbox" name="frns[]" aria-label="Sélectionner Karine"></td>
              <td>Karine</td>
              <td>Vêtement</td>
              <td>Moyen</td>
              <td><a href="mailto:">Contacter</a></td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Nouveau fournisseur -->
      <section aria-labelledby="titre-ajout">
        <h2 id="titre-ajout">Ajouter un fournisseur</h2>
        <form action="" method="post">
          <label for="nom">Nom</label>
          <input type="text" name="nom" id="nom" required>

          <label for="categorie">Catégorie</label>
          <input type="text" name="categorie" id="categorie" required>

          <label for="statut">Statut</label>
          <select name="statut" id="statut" required>
            <option value="" selected disabled>Sélectionner un statut</option>
            <option value="Bon">Bon</option>
            <option value="Moyen">Moyen</option>
            <option value="Mauvais">Mauvais</option>
          </select>

          <label for="observation">Observation</label>
          <textarea name="observation" id="observation"></textarea>

          <button type="submit">Ajouter</button>
        </form>
      </section>
    </main>
  </body>
</html>