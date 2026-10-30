<?php
  require_once __DIR__ . "/../../server/service/get_data/etat_stock.php";
  require_once __DIR__ . "/../../server/service/tout_produit.php";

  function etat(string $etat, array $params): string {
    return match ($etat) {
      "Disponible"         => $params[0],
      "À réapprovisionner" => $params[1],
      default              => $params[2],
    };
  }

  $ok_faible_epuise    = ["ok", "faible", "epuise"];
  $stock_faible_epuise = ["En stock", "Stock faible", "Épuisé"];
  $ok_low_out          = ["state-ok", "state-low", "state-out"];
  $meter_class         = ["", "is-warn", "is-bad"];
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>État de stock</title>
    <link rel="stylesheet" href="etat.css" />
  </head>
  <body>
    <!-- SIDE BAR -->
    <?php require_once __DIR__ . "/../components/sideBar.php"; ?>

    <main>
      <!-- BARRE SUPERIEURE -->
      <header class="topbar">
        <div class="breadcrumbs">
          Gestion <span>/</span> <strong>État de stock</strong>
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
            <h1>État de stock</h1>
            <p class="subtitle">
              Consultez les quantités disponibles, la valeur de votre stock et
              les produits à réapprovisionner.
            </p>
          </div>
          <span class="heading-chip">Valorisation : FIFO</span>
        </section>

        <!-- STATISTIQUES -->
        <section class="stats-grid" aria-label="Résumé du stock">
          <article class="stat-card">
            <h3>Produits référencés</h3>
            <p class="stat-value"><?= $count_product_stock[0]["prd"]?> <small>produits</small></p>
            <p class="stat-description">Nombre d'articles dans le catalogue.</p>
          </article>

          <article class="stat-card">
            <h3>Unités en stock</h3>
            <p class="stat-value"><?= $stock[0]["stock_total"]?> <small>unités</small></p>
            <p class="stat-description">Quantité totale disponible.</p>
          </article>

          <article class="stat-card">
            <h3>Valeur du stock</h3>
            <p class="stat-value"><?= euro($valeur_stock[0]["valeur_stock"])?> <small>€</small></p>
            <p class="stat-description">
              Quantités × coût d'achat (exemple statique).
            </p>
          </article>

          <article class="stat-card is-warn">
            <h3>Alertes</h3>
            <p class="stat-value"><?= $stock_faible[0]["stock_faible"]?> <small>produits</small></p>
            <p class="stat-description">
              Stocks faibles ou épuisés à réapprovisionner.
            </p>
          </article>
        </section>

        <!-- TABLEAU DE L'ETAT DE STOCK -->
        <section class="content-card">
          <div class="table-heading">
            <h2>Stock actuel par produit</h2>
            <p>Situation du stock à la date du jour.</p>
          </div>

          <!-- FILTRES -->
          <form class="filters" id="filter-form">
            <div class="search-wrap">
              <input
                class="search-input"
                type="search"
                id="search"
                name="search"
                placeholder="Rechercher un produit..."
                aria-label="Rechercher par référence ou nom du produit"
              />
            </div>

            <select
              class="filter-select"
              id="categorie"
              name="categorie"
              aria-label="Filtrer par catégorie"
            >
              <option value="">Toutes les catégories</option>
              <?php foreach($all_categorie as $categorie):?>
                <option value="<?= $categorie["categorie"]?>">
                  <?= $categorie["categorie"]?>
                </option>
              <?php endforeach?>
            </select>

            <div class="filter-options">
              <label for="etat-tous">
                <input type="radio" name="etat" id="etat-tous" value="tous" checked />
                Tous
              </label>
              <label for="etat-ok">
                <input type="radio" name="etat" id="etat-ok" value="ok" />
                En stock
              </label>
              <label for="etat-faible">
                <input type="radio" name="etat" id="etat-faible" value="faible" />
                Faible
              </label>
              <label for="etat-epuise">
                <input type="radio" name="etat" id="etat-epuise" value="epuise" />
                Épuisé
              </label>
            </div>

            <button class="filter-button" type="reset" id="reset-filters">
              Réinitialiser
            </button>
          </form>

          <!-- TABLEAU -->
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>Produit</th>
                  <th>Catégorie</th>
                  <th>Quantité</th>
                  <th>Seuil d'alerte</th>
                  <th>Niveau</th>
                  <th>Coût unitaire</th>
                  <th>Prix de vente</th>
                  <th>Valeur du stock</th>
                  <th>État</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <tbody id="stock-table">
                <?php foreach ($all_stock as $produit):
                  $cat = $produit["Categorie"] ?: "Non précisé";
                  $niveau = max(0, min(100, round($produit["Quantite"] / max(1, $produit["Seuil_alerte"] * 4) * 100)));
                ?>
                  <tr
                    data-etat="<?= etat($produit["Etat"], $ok_faible_epuise) ?>"
                    data-categorie="<?= htmlspecialchars($cat) ?>"
                    data-search="<?= htmlspecialchars($produit["reference_produit"] . " " . $produit["Produit"]) ?>"
                  >
                    <td>
                      <span class="product-name"><?= htmlspecialchars($produit["Produit"]) ?></span>
                      <span class="product-reference">Réf. <?= htmlspecialchars($produit["reference_produit"]) ?></span>
                    </td>
                    <td><?= htmlspecialchars($cat) ?></td>
                    <td><strong><?= $produit["Quantite"] ?></strong> unités</td>
                    <td><?= $produit["Seuil_alerte"] ?></td>
                    <td><div class="meter <?= etat($produit["Etat"], $meter_class) ?>"><span style="width: <?= $niveau ?>%"></span></div></td>
                    <td><?= euro($produit["Cout_unitaire"]) ?> €</td>
                    <td><?= euro($produit["Prix_vente"]) ?> €</td>
                    <td class="amount"><?= euro($produit["Valeur_stock"]) ?> €</td>
                    <td><span class="state <?= etat($produit["Etat"], $ok_low_out) ?>"><?= etat($produit["Etat"], $stock_faible_epuise) ?></span></td>
                    <td><a class="action-link" href="#details-<?= htmlspecialchars($produit["reference_produit"]) ?>">Détails</a></td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
            <p class="empty-message" id="empty-message" hidden>
              Aucun produit ne correspond à votre recherche.
            </p>
          </div>

          <!-- BAS DU TABLEAU -->
          <div class="table-footer">
            <p id="result-count"><?= $count_product_stock[0]["prd"]?> produit(s) affiché(s)</p>
            <p>Valeur totale affichée : <strong id="total-value"><?= euro($valeur_stock[0]["valeur_stock"])?> €</strong></p>
          </div>
        </section>
      </div>
    </main>

    <script src="etat.js" defer></script>
  </body>
</html>