document.addEventListener("DOMContentLoaded", function () {
    const navButtons = document.querySelectorAll(".nav-btn");
    const contentSections = document.querySelectorAll(".content-section");
    const form = document.getElementById('zoekForm');
    const resultsDiv = document.getElementById('vakantieResultaten');


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
    if (form && resultsDiv) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const query = new URLSearchParams(formData).toString();

            fetch(`backend/search.php?${query}`)
                .then(response => response.text())
                .then(html => {
                    resultsDiv.innerHTML = html;
                })
                .catch(error => {
                    console.error('Zoeken mislukt:', error);
                    resultsDiv.innerHTML = '<p>Fout bij het laden van zoekresultaten.</p>';
                });
        });

    }
});


// Functie om de bestemmingen te tonen
function toonBestemmingen() {
    const container = document.getElementById('bestemming-lijst');
    container.innerHTML = '';  // Maak de lijst leeg

    const zichtbaar = bestemmingen.slice(huidigeIndex, huidigeIndex + 4); // Toon 4 bestemmingen tegelijk
    zichtbaar.forEach((bestemming) => {
        const kaart = document.createElement('div');
        kaart.className = 'bestemming-kaart';
        kaart.innerHTML = `
            <img src="${bestemming.photo_url}" alt="${bestemming.destination}">
            <h3>${bestemming.destination}</h3>
            <p>${bestemming.location}</p>
            <p>From <span>${bestemming.price}</span></p>
        `;
        container.appendChild(kaart);
    });
}

