/* ==========================================
   ÉTAT DE STOCK — FILTRAGE DE LA MAQUETTE
   Aucune donnée n'est enregistrée
   ========================================== */

(() => {
  "use strict";

  const form = document.getElementById("filter-form");
  const searchInput = document.getElementById("search");
  const categorySelect = document.getElementById("categorie");
  const rows = Array.from(document.querySelectorAll("#stock-table tr"));
  const emptyMessage = document.getElementById("empty-message");
  const resultCount = document.getElementById("result-count");
  const totalValue = document.getElementById("total-value");

  // Convertit « 1 412,00 € » en nombre
  function parseEuro(text) {
    return parseFloat(text.replace(/[^\d,-]/g, "").replace(",", ".")) || 0;
  }

  function filterStock() {
    const selectedState =
      document.querySelector('input[name="etat"]:checked')?.value || "tous";
    const selectedCategory = categorySelect.value;
    const term = searchInput.value.trim().toLocaleLowerCase("fr");

    let visible = 0;
    let total = 0;

    rows.forEach((row) => {
      const matchesState =
        selectedState === "tous" || row.dataset.etat === selectedState;
      const matchesCategory =
        !selectedCategory || row.dataset.categorie === selectedCategory;
      const matchesSearch =
        !term || row.dataset.search.toLocaleLowerCase("fr").includes(term);

      const show = matchesState && matchesCategory && matchesSearch;
      row.hidden = !show;

      if (show) {
        visible++;
        total += parseEuro(row.querySelector(".amount").textContent);
      }
    });

    emptyMessage.hidden = visible !== 0;
    resultCount.textContent = `${visible} produit(s) affiché(s)`;
    totalValue.textContent =
      total.toLocaleString("fr-FR", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      }) + " €";
  }

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    filterStock();
  });
  searchInput.addEventListener("input", filterStock);
  categorySelect.addEventListener("change", filterStock);
  document.querySelectorAll('input[name="etat"]').forEach((radio) => {
    radio.addEventListener("change", filterStock);
  });

  // Après un reset, le navigateur remet les champs à zéro puis on rafraîchit
  form.addEventListener("reset", () => setTimeout(filterStock, 0));

  filterStock();
})();
