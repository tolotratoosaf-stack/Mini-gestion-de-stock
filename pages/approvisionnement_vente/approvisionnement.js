/* ==========================================
   APPROVISIONNEMENT / VENTE — MAQUETTE
   Calculs en direct, aucun enregistrement
   ========================================== */

(() => {
  "use strict";

  const get = (id) => document.getElementById(id);

  const form = get("operation-form");
  const produit = get("produit");
  const quantite = get("quantite");
  const prix = get("prix");
  const prixLabel = get("prix-label");
  const motif = get("motif");
  const sensField = get("sens-field");
  const dateInput = get("date");
  const heureInput = get("heure");
  const quantiteError = get("quantite-error");
  const submitBtn = get("submit-btn");
  const demoMessage = get("demo-message");

  const sum = {
    produit: get("sum-produit"),
    stock: get("sum-stock"),
    after: get("sum-after"),
    montantLabel: get("sum-montant-label"),
    montant: get("sum-montant"),
    beneficeRow: get("sum-benefice-row"),
    benefice: get("sum-benefice"),
  };

  const MOTIFS = {
    achat: ["Approvisionnement", "Retour client", "Autre"],
    vente: ["Vente conclue", "Autre"],
    ajustement: [
      "Produit endommagé",
      "Perte ou vol",
      "Erreur d'inventaire",
      "Autre",
    ],
  };

  const euro = (n) =>
    n.toLocaleString("fr-FR", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    }) + " €";

  const currentType = () =>
    document.querySelector('input[name="type_mouvement"]:checked').value;

  const currentSens = () =>
    document.querySelector('input[name="sens"]:checked').value;

  function selectedProduct() {
    const option = produit.selectedOptions[0];
    if (!option || !option.value) return null;
    return {
      name: option.textContent,
      stock: Number(option.dataset.stock),
      cout: Number(option.dataset.cout),
      prix: Number(option.dataset.prix),
    };
  }

  // ------------------------------------------
  // Date et heure par défaut
  // ------------------------------------------
  function setNow() {
    const now = new Date();
    const pad = (n) => String(n).padStart(2, "0");
    dateInput.value = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;
    heureInput.value = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
  }

  // ------------------------------------------
  // Adapter le formulaire au type d'opération
  // ------------------------------------------
  function applyType() {
    const type = currentType();

    motif.innerHTML = MOTIFS[type]
      .map((m) => `<option value="${m}">${m}</option>`)
      .join("");

    sensField.hidden = type !== "ajustement";

    prixLabel.textContent =
      type === "vente" ? "Prix de vente unitaire" : "Prix d'achat unitaire";

    // Pour un ajustement, le prix n'est pas modifiable (coût d'achat)
    prix.readOnly = type === "ajustement";

    fillPrice();
    update();
  }

  function fillPrice() {
    const p = selectedProduct();
    if (!p) {
      prix.value = "";
      return;
    }
    prix.value = (currentType() === "vente" ? p.prix : p.cout).toFixed(2);
  }

  // ------------------------------------------
  // Résumé et validation
  // ------------------------------------------
  function update() {
    const type = currentType();
    const p = selectedProduct();
    const qty = Number(quantite.value) || 0;
    const unit = Number(prix.value) || 0;

    sum.beneficeRow.hidden = type !== "vente";
    sum.montantLabel.textContent =
      type === "achat"
        ? "Dépense totale"
        : type === "vente"
          ? "Montant de la vente"
          : "Montant";

    if (!p) {
      sum.produit.textContent = "—";
      sum.stock.textContent = "—";
      sum.after.textContent = "—";
      sum.montant.textContent = "—";
      sum.benefice.textContent = "—";
      quantiteError.hidden = true;
      submitBtn.disabled = false;
      return;
    }

    const decreases =
      type === "vente" ||
      (type === "ajustement" && currentSens() === "retrait");
    const delta = decreases ? -qty : qty;
    const after = p.stock + delta;

    sum.produit.textContent = p.name;
    sum.stock.textContent = p.stock + " unités";
    sum.after.textContent = qty ? after + " unités" : "—";
    sum.after.style.color = after < 0 ? "#fca5a5" : "";

    if (type === "achat") {
      sum.montant.textContent = qty ? "−" + euro(qty * unit) : "—";
    } else if (type === "vente") {
      sum.montant.textContent = qty ? euro(qty * unit) : "—";
      const profit = qty * (unit - p.cout);
      sum.benefice.textContent = qty ? euro(profit) : "—";
      sum.benefice.className = profit >= 0 ? "positive" : "negative";
    } else {
      sum.montant.textContent = "—";
    }

    // Impossible de sortir plus que le stock disponible
    const tooMuch = decreases && qty > p.stock;
    quantiteError.hidden = !tooMuch;
    if (tooMuch) {
      quantiteError.textContent = `Stock insuffisant : ${p.stock} unité(s) disponible(s).`;
    }
    submitBtn.disabled = tooMuch;
  }

  // ------------------------------------------
  // Événements
  // ------------------------------------------
  document
    .querySelectorAll('input[name="type_mouvement"]')
    .forEach((radio) => radio.addEventListener("change", applyType));

  document
    .querySelectorAll('input[name="sens"]')
    .forEach((radio) => radio.addEventListener("change", update));

  produit.addEventListener("change", () => {
    fillPrice();
    update();
  });
  quantite.addEventListener("input", update);
  prix.addEventListener("input", update);

  form.addEventListener("reset", () => {
    // Attend que le navigateur ait réinitialisé les champs
    setTimeout(() => {
      demoMessage.hidden = true;
      setNow();
      applyType();
    }, 0);
  });

  // ------------------------------------------
  // Initialisation
  // ------------------------------------------
  setNow();
  applyType();
})();
