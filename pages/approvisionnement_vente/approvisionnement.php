<?php
  require_once __DIR__ . "/../../server/service/ajout/mouvement_stock.php";
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Approvisionnement / Vente</title>
    <link rel="stylesheet" href="approvisionnement.css" />
  </head>
  <body>
    <!-- SIDE BAR -->
    <?php require_once __DIR__ . "/../components/sideBar.php"; ?>

    <main>
      <!-- BARRE SUPERIEURE -->
      <header class="topbar">
        <div class="breadcrumbs">
          Gestion <span>/</span> <strong>Approvisionnement / Vente</strong>
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
            <h1>Approvisionnement / Vente</h1>
            <p class="subtitle">
              Enregistrez une entrée, une sortie ou une correction de stock.
              Chaque opération apparaîtra dans l'historique des mouvements.
            </p>
          </div>
        </section>

        <div class="layout">
          <!-- FORMULAIRE -->
          <section class="panel">
            <div class="panel-heading">
              <h2>Nouvelle opération</h2>
              <p>Choisissez le type d'opération puis renseignez les détails.</p>
            </div>

            <form id="operation-form" method="POST" action="../../server/service/ajout/mouvement_stock.php">
              <!-- TYPE D'OPERATION -->
              <fieldset class="type-switch">
                <legend class="sr-only">Type d'opération</legend>

                <label for="type-achat">
                  <input type="radio" name="type_mouvement" id="type-achat" value="achat" checked />
                  <span>Approvisionnement</span>
                </label>
                <label for="type-vente">
                  <input type="radio" name="type_mouvement" id="type-vente" value="vente" />
                  <span>Vente</span>
                </label>
                <label for="type-ajustement">
                  <input type="radio" name="type_mouvement" id="type-ajustement" value="ajustement" />
                  <span>Ajustement</span>
                </label>
              </fieldset>

              <div class="form-grid">
                <div class="field span-2">
                  <label for="produit">Produit <b>*</b></label>
                  <select id="produit" name="produit" required>
                    <option value="" selected disabled>Sélectionner un produit</option>
                    <?php foreach($all_product as $product):?>
                      <option
                        value="<?= $product["reference_produit"]?>"
                        data-stock="<?= $product["Quantite"]?>"
                        data-cout="<?= $product["Cout_unitaire"]?>"
                        data-prix="<?= $product["Prix_vente"]?>"
                      ><?= $product["name_produit"]?> (<?= $product["reference_produit"]?>)</option>
                    <?php endforeach?>
                  </select>
                </div>

                <!-- Sens : uniquement pour un ajustement -->
                <div class="field span-2" id="sens-field" hidden>
                  <label>Sens de l'ajustement</label>
                  <div class="inline-choices">
                    <label for="sens-retrait">
                      <input type="radio" name="sens" id="sens-retrait" value="retrait" checked />
                      Retirer du stock
                    </label>
                    <label for="sens-ajout">
                      <input type="radio" name="sens" id="sens-ajout" value="ajout" />
                      Ajouter au stock
                    </label>
                  </div>
                </div>

                <div class="field">
                  <label for="quantite">Quantité <b>*</b></label>
                  <div class="input-suffix">
                    <input type="number" id="quantite" name="quantite" min="1" step="1" placeholder="0" required />
                    <span>unités</span>
                  </div>
                  <small class="field-error" id="quantite-error" hidden></small>
                </div>

                <div class="field">
                  <label for="prix" id="prix-label">Prix d'achat unitaire</label>
                  <div class="input-suffix">
                    <input type="number" id="prix" name="prix_unitaire" min="0" step="0.01" placeholder="0,00" required />
                    <span>€</span>
                  </div>
                </div>

                <div class="field">
                  <label for="date">Date <b>*</b></label>
                  <input type="date" id="date" name="date" required />
                </div>

                <div class="field">
                  <label for="heure">Heure <b>*</b></label>
                  <input type="time" id="heure" name="heure" required />
                </div>

                <div class="field span-2">
                  <label for="motif">Motif <b>*</b></label>
                  <select id="motif" name="motif" required></select>
                </div>

                <div class="field span-2">
                  <label for="observation">Observation</label>
                  <textarea id="observation" name="observation" rows="3" placeholder="Informations complémentaires (facultatif)…"></textarea>
                </div>
              </div>

              <div class="form-actions">
                <button type="reset" class="button button-outline">Réinitialiser</button>
                <button type="submit" class="button button-primary" id="submit-btn">
                  Enregistrer l'opération
                </button>
              </div>

              <p class="demo-message" id="demo-message" role="status" hidden>
                Maquette uniquement : l'enregistrement en base sera connecté en PHP plus tard.
              </p>
            </form>
          </section>

          <!-- RESUME EN DIRECT -->
          <aside class="summary" aria-live="polite">
            <h2>Résumé de l'opération</h2>

            <dl>
              <div>
                <dt>Produit</dt>
                <dd id="sum-produit">—</dd>
              </div>
              <div>
                <dt>Stock actuel</dt>
                <dd id="sum-stock">—</dd>
              </div>
              <div>
                <dt>Stock après opération</dt>
                <dd id="sum-after" class="big">—</dd>
              </div>
              <div>
                <dt id="sum-montant-label">Montant total</dt>
                <dd id="sum-montant">—</dd>
              </div>
              <div id="sum-benefice-row" hidden>
                <dt>Bénéfice estimé</dt>
                <dd id="sum-benefice" class="positive">—</dd>
              </div>
            </dl>

            <p class="summary-note">
              Le stock est recalculé à partir de tous les mouvements enregistrés.
            </p>
          </aside>
        </div>
      </div>
    </main>

    <script src="approvisionnement.js" defer></script>
  </body>
</html>