<?php
  require_once __DIR__ . "/../../server/service/get_data/mouvement_stock.php";

  function getDateFr(string $valeur, $format):string {
    $dt = new DateTime($valeur);
    $date = $dt->format('d/m/Y');
    $heure = $dt->format('H:i:s');

    return match ($format) {
      "date" => $date,
      "heure" => $heure,
    };
  }

  function sens(string $valeur):string {
    if(str_contains($valeur,'achat')){
      return "amount-expense";
    } elseif(str_contains($valeur,'vente')) {
      return "amount-income";
    } else {
      return "";
    }
  }
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mouvement de stock</title>
    <link rel="stylesheet" href="stock.css" />
    <style></style>
  </head>
  <body>
    <!-- SIDE BAR -->
    <?php require_once __DIR__ . "/../components/sideBar.php";?>


    <main>
      <!-- BARRE SUPERIEURE -->
      <header class="topbar">
        <div class="breadcrumbs">
          Gestion <span>/</span> <strong>Mouvement de stock</strong>
        </div>

        <div class="topbar-right">
          <span class="status-dot"></span>
          <span class="topbar-label">Mode conception</span>
          <div class="top-avatar">AD</div>
        </div>
      </header>

      <div class="page-wrap">
        <!-- TITRE DE LA PAGE -->
        <section class="page-heading">
          <div>
            <p class="eyebrow">GESTION DU STOCK</p>
            <h1>MOUVEMENT DE STOCK</h1>
            <p class="subtitle">
              Consultez l'historique des achats, des ventes et des ajustements
              de votre stock.
            </p>
          </div>
        </section>

        <!-- STATISTIQUES -->
        <section class="stats-grid">
          <article class="stat-card">
            <h3>Chiffre d'affaires</h3>
            <p class="stat-value"><?= euro($chiffre_affaire[0]["chiffre_affaires"])?> <small>€</small></p>
            <p class="stat-description">
              Montant total des ventes enregistrées.
            </p>
          </article>

          <article class="stat-card">
            <h3>Total des ventes</h3>
            <p class="stat-value"><?= $total_vente[0]["total_vente"]?> <small>unités</small></p>
            <p class="stat-description">
              Nombre total d'unités vendues sur la période.
            </p>
          </article>

          <article class="stat-card">
            <h3>Total des approvisionnements</h3>
            <p class="stat-value"><?= $total_achat[0]["total_achat"]?> <small>unités</small></p>
            <p class="stat-description">
              Nombre total d'unités achetées pour le stock.
            </p>
          </article>

          <article class="stat-card">
            <h3>Bénéfice brut estimé</h3>
            <p class="stat-value stat-green"><?= euro($chiffre_affaire[0]["benefice"])?> <small>€</small></p>
            <p class="stat-description">
              À calculer à partir du coût d'achat des produits vendus.
            </p>
          </article>
        </section>

        <!-- HISTORIQUE DES MOUVEMENTS -->
        <section class="content-card">
          <div class="table-heading">
            <h2>Historique des mouvements</h2>
            <p>Retrouvez les entrées et sorties de stock.</p>
          </div>

          <!-- FILTRES -->
          <form class="filters" id="filter-form">
            <input
              class="filter-date"
              type="date"
              name="date"
              id="date"
              aria-label="Filtrer par date"
            />

            <input
              class="search-input"
              type="search"
              id="search"
              name="search"
              placeholder="Rechercher un produit..."
              aria-label="Rechercher par référence ou nom du produit"
            />

            <div class="filter-options">
              <label for="achat">
                <input type="radio" name="mouvement" id="achat" value="achat" />
                Achat
              </label>

              <label for="vente">
                <input type="radio" name="mouvement" id="vente" value="vente" />
                Vente
              </label>

              <label for="ajustement">
                <input
                  type="radio"
                  name="mouvement"
                  id="ajustement"
                  value="ajustement"
                />
                Ajustement
              </label>

              <label for="tous">
                <input
                  type="radio"
                  name="mouvement"
                  id="tous"
                  value="tous"
                  checked
                />
                Tous
              </label>
            </div>

            <button class="filter-button" type="submit">Filtrer</button>
          </form>

          <!-- TABLEAU -->
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>
                    <input
                      type="checkbox"
                      id="select-all"
                      aria-label="Sélectionner tous les mouvements"
                    />
                  </th>
                  <th>Transaction</th>
                  <th>Produit</th>
                  <th>Type</th>
                  <th>Quantité</th>
                  <th>Date</th>
                  <th>Heure</th>
                  <th>Motif</th>
                  <th>Prix unitaire</th>
                  <th>Montant total</th>
                  <th>Bénéfice</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <tbody id="movement-table">
                <?php foreach($mouvement_stock as $mouvement):?>
                  <tr
                    data-type="<?= $mouvement["type"]?>"
                    data-date="<?= getDateFr($mouvement["date"],"date")?>"
                    data-search="<?= $mouvement["reference_produit"]?> <?= $mouvement["name_produit"]?>">
                    <td>
                      <input
                        type="checkbox"
                        class="row-checkbox"
                        aria-label="Sélectionner l'<?= $mouvement["type"]?> <?= $mouvement["reference_produit"]?>"
                      />
                    </td>
                    <td><?= $mouvement["reference_transaction"]?></td>
                    <td>
                      <span class="product-name"><?= $mouvement["name_produit"]?></span>
                    </td>
                    <td>
                      <span class="movement-badge badge-<?= strtolower($mouvement["type"])?>"> <?= strtolower($mouvement["type"])?> </span>
                    </td>
                    <td><?= $mouvement["quantite"]?> unités</td>
                    <td><?= getDateFr($mouvement["date"],'date')?></td>
                    <td><?= getDateFr($mouvement["date"],'heure')?></td>
                    <td><?= $mouvement["motif"]?></td>
                    <td><?= $mouvement["prix_unitaire"]?> €</td>
                    <td class="<?= sens($mouvement["type"])?>">
                      <?php if($mouvement["type"]==='ajustement'):?>
                        —
                      <?php else:?>
                        <?= $mouvement["type"]==="achat"?"-":""?>
                        <?= euro($mouvement["prix_total"])?> €
                      <?php endif?>
                    </td>
                    <td class="<?= $mouvement["type"]==='vente'?'profit':''?>">
                      <?= $mouvement["type"]==='vente'? $mouvement["benefice"]:'—'?>
                    </td>
                    <td>
                      <a class="action-link" href="?details=<?= $mouvement["reference_produit"]?>"> Détails </a>
                    </td>
                  </tr>
                <?php endforeach?>
              </tbody>
            </table>

            <p class="empty-message" id="empty-message" hidden>
              Aucun mouvement ne correspond à votre recherche.
            </p>
          </div>

          <!-- BAS DU TABLEAU -->
          <div class="table-footer">
            <p id="result-count">3 mouvements affichés (exemples)</p>

            <div class="pagination">
              <button type="button" disabled>Précédent</button>
              <button type="button" disabled>1</button>
              <button type="button" disabled>Suivant</button>
            </div>
          </div>
        </section>
      </div>
    </main>

    <!-- FILTRAGE VISUEL DES EXEMPLES -->
    <script src="stock.js" defer></script>
  </body>
</html>

