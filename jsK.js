let bestemmingen = [];
let huidigeIndex = 0;

// Functie om de bestemmingen op te halen
function fetchBestemmingen() {
    fetch('backend/fetch_bestemmingen.php')
        .then(response => response.json())
        .then(data => {
            bestemmingen = data;
            toonBestemmingen();  // Laat de eerste set bestemmingen zien
        })
        .catch(error => console.error('Fout bij het ophalen van bestemmingen:', error));
}

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

// Functie om naar de volgende set bestemmingen te gaan
function volgendeSlide() {
    if (huidigeIndex + 4 < bestemmingen.length) {
        huidigeIndex += 4;  // Ga verder naar de volgende 4 bestemmingen
    } else {
        huidigeIndex = 0;  // Reset naar begin als we aan het einde zijn
    }
    toonBestemmingen();
}

// Functie om naar de vorige set bestemmingen te gaan
function vorigeSlide() {
    if (huidigeIndex - 4 >= 0) {
        huidigeIndex -= 4;  // Ga naar de vorige 4 bestemmingen
    } else {
        huidigeIndex = bestemmingen.length - 4;  // Als we in het begin zitten, ga naar het einde
    }
    toonBestemmingen();
}

// Zorg ervoor dat de bestemmingen worden opgehaald zodra de pagina wordt geladen
document.addEventListener('DOMContentLoaded', fetchBestemmingen);