<?php
  // Page actuellement affichée (ex. "etat.php"), même si la sidebar est incluse via require_once
  $currentPage = basename($_SERVER['SCRIPT_NAME']);

  // nom du fichier de la page => [lien, texte du menu]
  $menu = [
    'dashboard.php'         => ['../dashboard/dashboard.php', 'dashboard'],
    'insertion.php'         => ['../add_product/insertion.php', 'toutes les produits'],
    'stock.php'             => ['../mouvement_stock/stock.php', 'mouvement de stock'],
    'etat.php'              => ['../etat_stock/etat.php', 'etat de stock'],
    'approvisionnement.php' => ['../approvisionnement_vente/approvisionnement.php', 'Approvisionnement/Vente'],
  ];
?>
<!-- SIDE BAR -->
<aside>
  <nav>
    <p>Gestion de stock</p>
    <ul>
      <?php foreach ($menu as $file => [$href, $label]): ?>
        <li>
          <a href="<?= htmlspecialchars($href) ?>"<?= $file === $currentPage ? ' class="active" aria-current="page"' : '' ?>>
            <?= htmlspecialchars($label) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</aside>