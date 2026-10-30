const filterForm = document.getElementById("filter-form");
const dateInput = document.getElementById("date");
const searchInput = document.getElementById("search");
const rows = Array.from(document.querySelectorAll("#movement-table tr"));
const emptyMessage = document.getElementById("empty-message");
const resultCount = document.getElementById("result-count");
const selectAll = document.getElementById("select-all");

function filterMovements(event) {
  if (event) {
    event.preventDefault();
  }

  const selectedType =
    document.querySelector('input[name="mouvement"]:checked')?.value || "tous";

  const selectedDate = dateInput.value;
  const searchTerm = searchInput.value.trim().toLowerCase();

  let visibleCount = 0;

  rows.forEach(function (row) {
    const type = row.dataset.type;
    const date = row.dataset.date;
    const searchText = row.dataset.search.toLowerCase();

    const matchesType = selectedType === "tous" || type === selectedType;

    const matchesDate = !selectedDate || date === selectedDate;

    const matchesSearch = !searchTerm || searchText.includes(searchTerm);

    const visible = matchesType && matchesDate && matchesSearch;

    row.hidden = !visible;

    if (visible) {
      visibleCount++;
    }
  });

  emptyMessage.hidden = visibleCount !== 0;
  resultCount.textContent =
    visibleCount + " mouvement(s) affiché(s) — exemples statiques";

  selectAll.checked = false;
  selectAll.indeterminate = false;
}

filterForm.addEventListener("submit", filterMovements);
dateInput.addEventListener("change", filterMovements);
searchInput.addEventListener("input", filterMovements);

document.querySelectorAll('input[name="mouvement"]').forEach(function (radio) {
  radio.addEventListener("change", filterMovements);
});

selectAll.addEventListener("change", function () {
  rows.forEach(function (row) {
    if (!row.hidden) {
      row.querySelector(".row-checkbox").checked = selectAll.checked;
    }
  });
});

document.querySelectorAll(".row-checkbox").forEach(function (checkbox) {
  checkbox.addEventListener("change", function () {
    const visibleCheckboxes = rows
      .filter(function (row) {
        return !row.hidden;
      })
      .map(function (row) {
        return row.querySelector(".row-checkbox");
      });

    const checkedCount = visibleCheckboxes.filter(function (item) {
      return item.checked;
    }).length;

    selectAll.checked =
      visibleCheckboxes.length > 0 && checkedCount === visibleCheckboxes.length;

    selectAll.indeterminate =
      checkedCount > 0 && checkedCount < visibleCheckboxes.length;
  });
});
