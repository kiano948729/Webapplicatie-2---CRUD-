console.log("jsK.js is geladen ✅");

document.addEventListener("DOMContentLoaded", function () {
    koppelShowMoreKnoppen();

    const navButtons = document.querySelectorAll(".nav-btn, .sub-nav-btn");
    const contentSections = document.querySelectorAll(".content-section");

    // Navigatiefunctionaliteit
    navButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const targetId = btn.dataset.target;

            // Verberg alle contentsecties
            contentSections.forEach((section) => {
                section.style.display = "none";
            });

            // Toon de juiste sectie
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.style.display = "block";
            }
        });
    });

    // Zoekformulier functionaliteit
    const form = document.getElementById('zoekForm');
    const resultsDiv = document.getElementById('vakantieResultaten');

    if (form && resultsDiv) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const query = new URLSearchParams(formData).toString();
            const searchType = formData.get("type") || "accommodation";
            const endpoint = searchType === "accommodation"
                ? "../../backend/controllers/search_accommodations.php"
                : "../../backend/controllers/search.php";

            fetch(`${endpoint}?${query}`)
                .then(response => {
                    if (!response.ok) throw new Error("Netwerkreactie was geen succes.");
                    return response.text();
                })
                .then(html => {
                    resultsDiv.innerHTML = html;
                    koppelShowMoreKnoppen(); // Herkoppel knoppen na nieuwe inhoud
                })
                .catch(error => {
                    console.error('Zoeken mislukt:', error);
                    resultsDiv.innerHTML = '<p>Fout bij het laden van zoekresultaten.</p>';
                });
        });
    }

    // Minimum en maximum prijs slider
    const minPriceSlider = document.getElementById("minPriceSlider");
    const minPriceLabel = document.getElementById("minPriceRangeValue");
    const maxPriceSlider = document.getElementById("maxPriceSlider");
    const maxPriceLabel = document.getElementById("maxPriceRangeValue");

    // Update labels bij paginalaad
    if (minPriceSlider && minPriceLabel) {
        minPriceLabel.textContent = "€" + minPriceSlider.value;
    }
    if (maxPriceSlider && maxPriceLabel) {
        maxPriceLabel.textContent = "€" + maxPriceSlider.value;
    }

    // Update labels tijdens slepen
    if (minPriceSlider && minPriceLabel) {
        minPriceSlider.addEventListener("input", function () {
            if (parseInt(this.value) > parseInt(maxPriceSlider.value)) {
                this.value = maxPriceSlider.value;
            }
            minPriceLabel.textContent = "€" + this.value;
        });
    }

    if (maxPriceSlider && maxPriceLabel) {
        maxPriceSlider.addEventListener("input", function () {
            if (parseInt(this.value) < parseInt(minPriceSlider.value)) {
                this.value = minPriceSlider.value;
            }
            maxPriceLabel.textContent = "€" + this.value;
        });
    }

    // Sorteerfunctionaliteit
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const selectedSort = this.value;
            const currentUrl = new URL(window.location.href);

            if (selectedSort) {
                currentUrl.searchParams.set('sort', selectedSort);
            } else {
                currentUrl.searchParams.delete('sort');
            }

            window.location.href = currentUrl.toString();
        });
    }
});

// Functie om 'Meer info' knoppen te koppelen
function koppelShowMoreKnoppen() {
    document.querySelectorAll('.show-more-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const overlay = document.getElementById(`expanded-${id}`);

            if (!overlay) {
                // Overlay nog niet geladen, haal hem op via AJAX
                fetch(`../../backend/ajax_get_accommodatie_overlay.php?id=${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Overlay kon niet worden geladen.");
                        return response.text();
                    })
                    .then(html => {
                        document.body.insertAdjacentHTML('beforeend', html);
                        document.getElementById(`expanded-${id}`).style.display = 'block';
                    })
                    .catch(error => {
                        console.error("Fout bij laden overlay:", error);
                        alert("Er ging iets fout bij het tonen van meer informatie.");
                    });
            } else {
                overlay.style.display = 'block';
            }
        });
    });
}

// Functie om info-overlay te sluiten
function closeExpandedInfo(id) {
    const overlay = document.getElementById(`expanded-${id}`);
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// Reset filters functie
function resetFilters() {
    const form = document.getElementById('zoekForm');
    const minPriceLabel = document.getElementById('minPriceRangeValue');
    const maxPriceLabel = document.getElementById('maxPriceRangeValue');

    if (form) {
        form.reset(); // Reset alle velden
    }

    // Update sliderlabels
    if (minPriceLabel) minPriceLabel.textContent = '€0';
    if (maxPriceLabel) maxPriceLabel.textContent = '€1000';

    // Standaardwaarden terugzetten
    if (document.getElementById("minPriceSlider")) document.getElementById("minPriceSlider").value = 0;
    if (document.getElementById("maxPriceSlider")) document.getElementById("maxPriceSlider").value = 1000;

    // Laad standaardresultaten
    if (form && resultsDiv) {
        const formData = new FormData(form);
        const query = new URLSearchParams(formData).toString();

        fetch('../../backend/fetch_bestemmingen.php?' + query)
            .then(response => {
                if (!response.ok) throw new Error("Standaardresultaten kunnen niet worden geladen.");
                return response.text();
            })
            .then(html => {
                resultsDiv.innerHTML = html;
                koppelShowMoreKnoppen();
            })
            .catch(error => {
                console.error("Fout bij laden standaardresultaten:", error);
                resultsDiv.innerHTML = '<p>Er ging iets fout bij het laden van de resultaten.</p>';
            });
    }
}