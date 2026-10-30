<?php
  require_once __DIR__ . "/../../server/service/tout_produit.php";

  $nb_page = (int) count($all_products)/10;

  function state(string $etat):string{
    if(trim($etat) === "Disponible") {
      return "state-ok";
    } elseif (trim($etat) === "À réapprovisionner") {
      return "state-low";
    } elseif (trim($etat) === "Rupture") {
      return "state-out";
    } else {
      return "state-out";
    }
  }
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des produits</title>
    <link rel="stylesheet" href="insertion.css">
  </head>
  <body>
    <!-- Sidebar de démonstration : remplacez-la par votre sideBar.php lorsque vous intégrerez la page au projet. -->
    <?php require_once __DIR__ . '/../components/sideBar.php';?>

    <main class="main-content">
      <header class="topbar">
        <div class="breadcrumbs">Gestion <span>/</span> <strong>Produits</strong></div>
        <div class="topbar-right">
          <span class="status-dot"></span>
          <span class="topbar-label">Mode conception</span>
          <div class="top-avatar">AD</div>
        </div>
      </header>

      <div class="page-wrap">
        <section class="page-heading">
          <div>
            <p class="eyebrow">CATALOGUE</p>
            <h1>Gestion des produits</h1>
            <p class="subtitle">Consultez votre catalogue et préparez l'ajout de nouveaux produits.</p>
          </div>
          <a href="#ajouter-produit" class="button button-primary"><span>＋</span> Nouveau produit</a>
        </section>

        <section class="stats-grid" aria-label="Résumé des produits">
          <!-- CARTE POUR LE NOMBRE TOTAL DE PRODUIT -->
          <article class="stat-card">
            <div class="stat-icon blue">▦</div>
            <div class="stat-info">
              <span class="stat-label">Total des produits</span>
              <strong><?= $count_product[0]["nombre_produit"]?></strong>
              <small class="positive">Catalogue actuel</small>
            </div>
            <span class="stat-decoration">▦</span>
          </article>

          <!-- CARTE POUR LE NOMBRE TOTAL DES CATEGORIES EXISTANTS -->
          <article class="stat-card">
            <div class="stat-icon violet">▤</div>
            <div class="stat-info">
              <span class="stat-label">Catégories</span>
              <strong><?= $categorie[0]["categorie"]?></strong>
              <small>Catégories disponibles</small>
            </div>
            <span class="stat-decoration">▤</span>
          </article>

          <!-- CARTE DE SUIVI DE STOCK -->
          <article class="stat-card">
            <div class="stat-icon amber">!</div>
            <div class="stat-info">
              <span class="stat-label">Stocks faibles</span>
              <strong>03</strong>
              <small class="warning-text">À vérifier</small>
            </div>
            <span class="stat-decoration">!</span>
          </article>
        </section>

        <!-- SECTION POUR TOUTES LES PRODUITS -->
        <section class="panel product-list">
          <!-- header du section -->
          <div class="panel-heading">
            <div>
              <h2>Liste des produits</h2>
              <p>Retrouvez rapidement les articles de votre catalogue.</p>
            </div>
            <span class="result-count"><?= $count_product[0]["nombre_produit"]?> produits</span>
          </div>

          <!-- Le petit onglet pour le recherche et pour le filtre -->
          <div class="toolbar">
            <!-- search bar -->
            <label class="search-box">
              <span>⌕</span>
              <input
                type="search"
                id="search-products"
                placeholder="Rechercher un produit, une marque…"
              >
            </label>

            <!-- filtre produit -->
            <label class="filter-select">
              <span>Catégorie :</span>
              <select id="filter-category">
                <option value="">Toutes les catégories</option>
                <?php foreach($all_categorie as $categorie):?>
                  <option><?= $categorie["categorie"]?></option>
                <?php endforeach?>
              </select>
            </label>

            <!-- bouton pour reinitialiser toutes les filtres -->
            <button
              class="button button-outline"
              id="reset-filters"
              type="button"
            >
              ↻ Réinitialiser
            </button>
          </div>

          <!-- TABLE POUR TOUTE LES PRODUITS -->
          <div class="table-container">
            <table id="products-table">
              <!-- en-tete du table -->
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" id="select-all" aria-label="Sélectionner tous les produits">
                  </th>
                  <th>Produit</th>
                  <th>Catégorie</th>
                  <th>Cible</th>
                  <th>Prix de vente</th>
                  <th>Stock</th>
                  <th>État</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <!-- corps du table -->
              <tbody>
                <?php foreach($all_products as $produit):?>
                  <tr data-category="<?= $produit["categorie_principale"]?>">
                    <td>
                      <input
                        type="checkbox"
                        class="row-check"
                        arial-label="Sélectionner <?= $produit["name_produit"]?>">
                    </td>
                    <td>
                      <div class="product-cell">
                        <div
                          class="product-thumb shoe"
                          data-color="<?= htmlspecialchars($produit["name_produit"]) ?>"
                        >
                          <?= htmlspecialchars(mb_strtoupper(mb_substr($produit["name_produit"], 0, 2, "UTF-8"), "UTF-8")) ?>
                        </div>
                        <div>
                          <strong><?= $produit["name_produit"]?></strong>
                          <small>Réf. <?= $produit["reference_produit"]?></small>
                        </div>
                      </div>
                    </td>
                    <td><?= $produit["categorie_principale"]?></td>
                    <td>
                      <span class="target-tag"><?= $produit["cible"]?></span>
                    </td>
                    <td class="price"><?= $produit["prix_vente"]?> €</td>
                    <td>
                      <strong><?= $produit["stock"]?></strong>
                    </td>
                    <td>
                      <span class="state <?= state($produit["Etat"])?>"><i></i><?= $produit["Etat"]?></span>
                    </td>
                    <td>
                      <button class="icon-button" title="Plus d'actions" type="button">···</button>
                    </td>
                  </tr>
                <?php endforeach?>
              </tbody>
            </table>

            <!-- si aucun recherche -->
            <div class="empty-state" id="empty-state" hidden>
              Aucun produit ne correspond à votre recherche.
            </div>
          </div>

          <!-- PIED DE SECTION: Pagination et nombre de produit -->
          <div class="table-footer">
            <span>
              Affichage de 
              <strong id="visible-count">4</strong> 
              produits sur <?= $count_product[0]["nombre_produit"]?>
            </span>

            <div class="pagination">
              <button type="button" disabled>‹</button>
              <?php if ($nb_page <= 1):?>
                <button type="button" class="current-page">1</button>
              <?php else:?>
                <?php for ($i=1; $i < $nb_page; $i++):?>
                  <button type="button"><?= $i?></button>
                <?php endfor?>
              <?php endif?>
              <button type="button">›</button>
            </div>
          </div>
        </section>

        <!-- SECTION D'AJOUT DE PRODUIT -->
        <section class="panel form-panel" id="ajouter-produit">
          <!-- mini en-tete -->
          <div class="panel-heading">
            <div>
              <h2>Ajouter un produit</h2>
              <p>Renseignez les informations générales et les caractéristiques de l'article.</p>
            </div>
            <span class="required-note">
              <b>*</b> Champs obligatoires
            </span>
          </div>

          <!-- LES CHAMPS DE L'AJOUT DU PRODUIT -->
          <form  method="POST" action="../../server/service/ajout/ajout_produit.php">
            <div class="form-section">
              <!-- mini en-tete -->
              <div class="form-section-title">
                <span class="section-number">01</span>
                <div>
                  <h3>Informations générales</h3>
                  <p>Identité et classement du produit</p>
                </div>
              </div>

              <!-- informations generales -->
              <div class="form-grid">
                <!-- titre du produit -->
                <div class="field span-2">
                  <label for="title">Titre du produit <b>*</b></label>
                  <input
                    type="text"
                    id="title"
                    name="titre"
                    placeholder="Ex. : Chaussures Jordan"
                    required>
                  <small>Choisissez un nom clair et facile à retrouver.</small>
                </div>

                <!-- description du produit -->
                <div class="field span-2">
                  <label for="description">Description</label>
                  <textarea
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Décrivez les principales caractéristiques du produit…"
                  ></textarea>
                </div>

                <!-- categorie du produit -->
                <div class="field">
                  <label for="category">Catégorie principale <b>*</b></label>
                  <select id="category" name="categorie" required>
                    <option value="" selected disabled>Sélectionner une catégorie</option>
                    <?php foreach($all_categorie as $categorie):?>
                      <option><?= $categorie["categorie"]?></option>
                    <?php endforeach?>
                  </select>
                </div>

                <!-- marque -->
                <div class="field">
                  <label for="brand">Marque</label>
                  <input type="text" id="brand" name="marque" placeholder="Ex. : Nike">
                </div>

                <!-- prix d'achat -->
                <div class="field">
                  <label for="price">Prix d'achat (€) <b>*</b></label>
                  <div class="input-suffix">
                    <input
                      type="number"
                      id="price"
                      name="prix"
                      min="0"
                      step="0.01"
                      placeholder="0,00"
                      required
                    >
                    <span>EUR</span>
                  </div>
                </div>

                <!-- marge beneficiaire -->
                <div class="field">
                  <label for="margin">Marge bénéficiaire (%) <b>*</b></label>
                  <div class="input-suffix">
                    <input
                      type="number"
                      id="margin"
                      name="marge_pourcentage"
                      min="0"
                      step="0.01"
                      placeholder="Ex. : 20"
                      required
                    >
                    <span>%</span>
                  </div>
                </div>

                <!-- Quantite initiale -->
                <div class="field">
                  <label for="quantity">Quantité initiale</label>
                  <div class="input-suffix">
                    <input
                      type="number"
                      id="quantity"
                      name="quantite"
                      min="0"
                      step="1"
                      placeholder="0"
                      value="0"
                    >
                    <span>unités</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- CLASSIFICATION ET CIBLE -->
            <div class="form-section">
              <!-- mini en-tete -->
              <div class="form-section-title">
                <span class="section-number">02</span>
                <div>
                  <h3>Classification et cible</h3>
                  <p>Précisez les sous-catégories et le public concerné</p>
                </div>
              </div>

              <div class="form-grid">
                <!-- sous-categorie -->
                <fieldset class="field span-2 choice-group">
                  <legend>Sous-catégories</legend>
                  <p class="field-help">Vous pouvez sélectionner plusieurs options.</p>
                  <div class="choice-list">
                    <?php foreach($subcategory as $subcategorie):?>
                      <label class="choice">
                        <input type="radio" name="sous_categorie" value=<?= $subcategorie["subcategory"]?>>
                        <span><?= $subcategorie["subcategory"]?></span>
                      </label>
                    <?php endforeach?>
                    <button
                      type="button"
                      class="text-button"
                      id="show-category-help"
                    >+ Autres catégories</button>
                  </div>
                  <small class="inline-help" id="category-help" hidden>
                    Les autres catégories pourront être ajoutées dans la section « Gestion des catégories » ci-dessous.
                  </small>
                </fieldset>

                <!-- Cible du produit -->
                <fieldset class="field span-2 choice-group">
                  <legend>Cible du produit</legend>
                  <p class="field-help">Sélectionnez une ou plusieurs cibles.</p>
                  <div class="choice-list">
                    <label class="choice">
                      <input type="checkbox" name="cible[]" value="homme">
                      <span>Homme</span>
                    </label>
                    <label class="choice">
                      <input type="checkbox" name="cible[]" value="femme">
                      <span>Femme</span>
                    </label>
                    <label class="choice">
                      <input type="checkbox" name="cible[]" value="unisexe">
                      <span>Unisexe</span>
                    </label>
                    <label class="choice">
                      <input type="checkbox" name="cible[]" value="adulte">
                      <span>Adulte</span>
                    </label>
                    <label class="choice">
                      <input type="checkbox" name="cible[]" value="enfant">
                      <span>Enfant</span>
                    </label>
                  </div>
                </fieldset>
              </div>
            </div>

            <!-- Dimension produit -->
            <div class="form-section">
              <!-- mini en-tete -->
              <div class="form-section-title">
                <span class="section-number">03</span>
                <div>
                  <h3>Dimensions du produit</h3>
                  <p>Informations facultatives pour les articles concernés</p>
                </div>
              </div>

              <div class="form-grid dimensions-grid">
                <div class="field">
                  <label for="length">Longueur</label>
                  <div class="input-suffix">
                    <input
                      type="number"
                      id="length"
                      name="longueur"
                      min="0"
                      step="0.1"
                      placeholder="0,0"
                    >
                    <span>cm</span>
                  </div>
                </div>

                <div class="field">
                  <label for="width">Largeur</label>
                  <div class="input-suffix">
                    <input
                      type="number"
                      id="width"
                      name="largeur"
                      min="0"
                      step="0.1"
                      placeholder="0,0"
                    >
                    <span>cm</span>
                  </div>
                </div>

                <div class="field">
                  <label for="height">Hauteur</label>
                  <div class="input-suffix">
                    <input
                      type="number"
                      id="height"
                      name="hauteur"
                      min="0"
                      step="0.1"
                      placeholder="0,0"
                    >
                    <span>cm</span>
                  </div>
                </div>

                <!-- CALCULE DE VOLUME ESTIME -->
                <div class="volume-card">
                  <div class="volume-symbol">◇</div>
                  <div>
                    <span>Volume estimé</span>
                    <strong id="volume-result">— cm³</strong>
                    <small>Longueur × largeur × hauteur</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- BOUTON POUR LES VALIDATIONS OU REINITIALISATION -->
            <div class="form-actions">
              <button type="reset" class="button button-outline">Réinitialiser</button>
              <button type="submit" class="button button-primary"><span>＋</span> Ajouter le produit</button>
            </div>

            <p
              class="demo-message"
              id="demo-message"
              role="status"
              hidden
            >
              Maquette uniquement : le formulaire est prêt côté affichage, mais l'enregistrement PHP sera ajouté dans une prochaine étape.
            </p>
          </form>
        </section>

        <!-- SECTION D'AJOUT DE NOUVEAU CATEGORIE -->
        <section class="panel category-panel" id="gestion-categories">
          <!-- mini en-tete -->
          <div class="panel-heading">
            <div>
              <h2>Gestion des catégories</h2>
              <p>Préparez l'organisation du catalogue.</p>
            </div>
            <span class="soft-tag">Configuration</span>
          </div>

          <div class="category-management-grid">
            <!-- Ajout categorie principale -->
            <form class="category-card" method="POST" action="../../server/service/ajout/add_category.php">
              <div class="category-card-icon violet">▤</div>
              <h3>Nouvelle catégorie</h3>
              <p>Créez une catégorie principale pour organiser vos produits.</p>
              <label for="new-category">Nom de la catégorie</label>
              <input
                type="text"
                name="name_category"
                id="new-category"
                placeholder="Ex. : Vêtements"
                required
              >
              <label for="description_category">Description du categorie</label>
              <textarea
                name="description_category"
                id="description_category"
                rows="3"
                placeholder="Ex: Vêtements pour hommes, femmes et unisexe."
                required
              ></textarea>
              <button type="submit" class="button button-outline">＋ Ajouter une catégorie</button>
            </form>

            <!-- Ajout sous-categorie -->
            <form class="category-card" method="POST" action="../../server/service/ajout/add_category.php">
              <div class="category-card-icon blue">⌞</div>
              <h3>Nouvelle sous-catégorie</h3>
              <p>Associez une sous-catégorie à une catégorie principale.</p>
              <label for="parent-category">Catégorie parente</label>
              <select id="parent-category" name="categorie_parent" required>
                <option value="" selected disabled>Sélectionner une catégorie</option>
                <?php foreach($all_categorie as $categorie):?>
                  <option><?= $categorie["categorie"]?></option>
                <?php endforeach?>
              </select>
              <label for="new-subcategory">Nom de la sous-catégorie</label>
              <input
                type="text"
                name="new_subcategory"
                id="new-subcategory"
                placeholder="Ex. : Chemises"
                required
              >
              <label for="id_subcategory">Description de la sous-categorie</label>
              <textarea
                name="desc_subcategorie"
                placeholder="Ex: T-shirts et hauts légers."
                id="id_subcategory"
              ></textarea>
              <button type="submit" class="button button-outline">＋ Ajouter une sous-catégorie</button>
            </form>
          </div>
        </section>

        <!-- FOOTER -->
        <footer class="page-footer">
          StockFlow 
          <span>•</span>
          Maquette de conception — données de démonstration
        </footer>
      </div>
    </main>
    <script src="insertion.js" defer></script>
  </body>
</html>
