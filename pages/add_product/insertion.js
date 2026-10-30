/* ==========================================
   INSERTION — INTERACTIONS DE LA MAQUETTE
   Animations douces + fonctionnalités UI
   Aucun enregistrement en base de données
   ========================================== */

(() => {
  "use strict";

  // Évite les erreurs si un élément optionnel est absent
  const get = (id) => document.getElementById(id);

  // ------------------------------------------
  // 1. Recherche et filtrage des produits
  // ------------------------------------------
  const search = get("search-products");
  const category = get("filter-category");
  const table = get("products-table");
  const emptyState = get("empty-state");
  const visibleCount = get("visible-count");
  const reset = get("reset-filters");
  const selectAll = get("select-all");

  const rows = table
    ? [...table.querySelectorAll("tbody tr[data-category]")]
    : [];

  function filterRows() {
    const term = search ? search.value.trim().toLocaleLowerCase("fr") : "";
    const selectedCategory = category ? category.value : "";
    let visible = 0;

    rows.forEach((row) => {
      const text = row.innerText.toLocaleLowerCase("fr");
      const matchesText = text.includes(term);
      const matchesCategory =
        !selectedCategory || row.dataset.category === selectedCategory;

      const shouldShow = matchesText && matchesCategory;

      row.hidden = !shouldShow;

      if (shouldShow) {
        visible++;
      }
    });

    if (emptyState) emptyState.hidden = visible !== 0;
    if (visibleCount) visibleCount.textContent = String(visible);

    if (selectAll) {
      selectAll.checked = false;
      selectAll.indeterminate = false;
    }

    updateSelectionCount();
  }

  if (search) search.addEventListener("input", filterRows);
  if (category) category.addEventListener("change", filterRows);

  if (reset) {
    reset.addEventListener("click", () => {
      if (search) search.value = "";
      if (category) category.value = "";
      filterRows();
    });
  }

  // ------------------------------------------
  // 2. Sélection des lignes du tableau
  // ------------------------------------------
  function getVisibleCheckboxes() {
    if (!table) return [];

    return [...table.querySelectorAll(".row-check")].filter(
      (checkbox) => !checkbox.closest("tr").hidden,
    );
  }

  function updateSelectionCount() {
    if (!selectAll) return;

    const boxes = getVisibleCheckboxes();
    const checked = boxes.filter((box) => box.checked).length;

    selectAll.checked = boxes.length > 0 && checked === boxes.length;
    selectAll.indeterminate = checked > 0 && checked < boxes.length;
  }

  if (selectAll && table) {
    selectAll.addEventListener("change", () => {
      getVisibleCheckboxes().forEach((checkbox) => {
        checkbox.checked = selectAll.checked;
      });
    });

    table.querySelectorAll(".row-check").forEach((checkbox) => {
      checkbox.addEventListener("change", updateSelectionCount);
    });
  }

  // ------------------------------------------
  // 3. Calcul automatique du volume
  // ------------------------------------------
  const dimensionIds = ["length", "width", "height"];
  const dimensions = dimensionIds.map(get).filter(Boolean);
  const volumeResult = get("volume-result");

  function updateVolume() {
    if (!volumeResult || dimensions.length !== 3) return;

    const values = dimensions.map((input) => input.value.trim());

    const valid = values.every(
      (value) =>
        value !== "" && Number.isFinite(Number(value)) && Number(value) > 0,
    );

    if (valid) {
      const volume = values.reduce((total, value) => total * Number(value), 1);

      volumeResult.textContent =
        volume.toLocaleString("fr-FR", {
          maximumFractionDigits: 2,
        }) + " cm³";
    } else {
      volumeResult.textContent = "— cm³";
    }
  }

  dimensions.forEach((input) => {
    input.addEventListener("input", updateVolume);
  });

  // ------------------------------------------
  // 4. Afficher ou masquer l'aide des catégories
  // ------------------------------------------
  const helpButton = get("show-category-help");
  const help = get("category-help");

  if (helpButton && help) {
    helpButton.addEventListener("click", () => {
      help.hidden = !help.hidden;
      helpButton.setAttribute("aria-expanded", String(!help.hidden));
    });
  }

  // ------------------------------------------
  // 5. Formulaire d'ajout de produit
  // Démonstration seulement : pas de sauvegarde
  // ------------------------------------------
  const productForm = get("product-form");
  const demoMessage = get("demo-message");

  if (productForm) {
    productForm.addEventListener("submit", (event) => {
      event.preventDefault();

      if (demoMessage) {
        demoMessage.hidden = false;
        demoMessage.scrollIntoView({
          behavior: "smooth",
          block: "nearest",
        });
      }
    });

    productForm.addEventListener("reset", () => {
      // Attend que le navigateur ait réinitialisé les champs
      setTimeout(() => {
        updateVolume();
        if (demoMessage) demoMessage.hidden = true;
      }, 0);
    });
  }

  // ------------------------------------------
  // 6. Formulaires de catégories
  // Démonstration seulement : pas de sauvegarde
  // ------------------------------------------
  document.querySelectorAll(".demo-category-form").forEach((form) => {
    form.addEventListener("submit", (event) => {
      event.preventDefault();

      const existing = form.querySelector(".category-demo-message");
      if (existing) existing.remove();

      const message = document.createElement("p");
      message.className = "category-demo-message demo-message";
      message.textContent =
        "Maquette uniquement : l’ajout réel sera connecté en PHP plus tard.";

      form.appendChild(message);

      // Apparition douce du message
      message.style.opacity = "0";
      message.style.transform = "translateY(6px)";

      requestAnimationFrame(() => {
        message.style.transition = "opacity 250ms ease, transform 250ms ease";
        message.style.opacity = "1";
        message.style.transform = "translateY(0)";
      });
    });
  });

  // ------------------------------------------
  // 7. Petites animations d'apparition
  // Ne remplace pas les animations du CSS global
  // ------------------------------------------
  const reduceMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;

  if (!reduceMotion) {
    const animatedItems = document.querySelectorAll(
      ".stat-card, .panel, .category-card",
    );

    animatedItems.forEach((item, index) => {
      item.style.animationDelay = `${Math.min(index * 60, 300)}ms`;
    });
  }

  // ------------------------------------------
  // 7b. Couleur automatique des miniatures
  // ------------------------------------------
  const THUMB_CLASSES = ["shoe", "cap", "shirt", "tee"];

  // Transforme un texte en nombre (toujours le même pour le même texte)
  function hashText(text) {
    let hash = 0;
    for (const char of text) {
      hash = (hash * 31 + char.codePointAt(0)) >>> 0;
    }
    return hash;
  }

  function colorizeThumbs() {
    document.querySelectorAll(".product-thumb[data-color]").forEach((thumb) => {
      const key = thumb.dataset.color.trim().toLocaleLowerCase("fr");
      const colorClass = THUMB_CLASSES[hashText(key) % THUMB_CLASSES.length];

      thumb.classList.remove(...THUMB_CLASSES);
      thumb.classList.add(colorClass);
    });
  }

  // ------------------------------------------
  // 8. Initialisation
  // ------------------------------------------
  filterRows();
  updateVolume();
  colorizeThumbs();
})();
