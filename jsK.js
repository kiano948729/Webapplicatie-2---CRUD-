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
            const searchType = formData.get("type"); // Lees het 'type' veld
            const endpoint = searchType === "accommodation"
                ? "backend/search_accommodations.php"
                : "backend/search.php"; // Standaard naar deals

            fetch(`${endpoint}?${query}`)
                .then(response => response.text())
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

    // Minimum prijs slider
    const minPriceSlider = document.getElementById("minPriceSlider");
    const minPriceLabel = document.getElementById("minPriceRangeValue");

    // Maximum prijs slider
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
            minPriceLabel.textContent = "€" + this.value;
        });
    }

    if (maxPriceSlider && maxPriceLabel) {
        maxPriceSlider.addEventListener("input", function () {
            maxPriceLabel.textContent = "€" + this.value;
        });
    }
});

// Functie om de show-more knoppen te koppelen
function koppelShowMoreKnoppen() {
    document.querySelectorAll('.show-more-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const overlay = document.getElementById(`expanded-${id}`);
            if (overlay) {
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
document.getElementById('sortSelect').addEventListener('change', function () {
    const selectedSort = this.value;
    const currentUrl = new URL(window.location.href);

    // Update of voeg de sort-parameter toe
    if (selectedSort) {
        currentUrl.searchParams.set('sort', selectedSort);
    } else {
        currentUrl.searchParams.delete('sort');
    }

    // Herlaad de pagina met nieuwe parameter
    window.location.href = currentUrl.toString();
});
minPriceSlider.addEventListener("input", function () {
    if (parseInt(this.value) > parseInt(maxPriceSlider.value)) {
        this.value = maxPriceSlider.value;
    }
    minPriceLabel.textContent = "€" + this.value;
});

maxPriceSlider.addEventListener("input", function () {
    if (parseInt(this.value) < parseInt(minPriceSlider.value)) {
        this.value = minPriceSlider.value;
    }
    maxPriceLabel.textContent = "€" + this.value;
});
function resetFilters() {
    const form = document.getElementById('zoekForm');
    form.reset(); // Reset alle velden

    // Update sliderlabels
    document.getElementById('minPriceRangeValue').textContent = '€0';
    document.getElementById('maxPriceRangeValue').textContent = '€1000';

    // Verstuur lege waarden
    const formData = new FormData(form);
    const query = new URLSearchParams(formData).toString();

    fetch('backend/fetch_bestemmingen.php?' + query)
        .then(response => response.text())
        .then(html => {
            document.getElementById('vakantieResultaten').innerHTML = html;
            koppelShowMoreKnoppen();
        });
}