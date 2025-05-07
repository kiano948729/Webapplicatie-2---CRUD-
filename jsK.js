const bestemmingen = [
    {
        img: 'https://via.placeholder.com/300x180?text=Thailand',
        route: 'Dhaka → Thailand',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$475.00'
    },
    {
        img: 'https://via.placeholder.com/300x180?text=Tokyo',
        route: 'Dhaka → Tokyo',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$475.00'
    },
    {
        img: 'https://via.placeholder.com/300x180?text=Chicago',
        route: 'Dhaka → Chicago',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$475.00'
    },
    {
        img: 'https://via.placeholder.com/300x180?text=Cox\'s+Bazar',
        route: 'Dhaka → Cox\'s Bazar',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$475.00'
    },
    {
        img: 'https://via.placeholder.com/300x180?text=Paris',
        route: 'Dhaka → Paris',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$499.00'
    },
    {
        img: 'https://via.placeholder.com/300x180?text=London',
        route: 'Dhaka → London',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$520.00'
    },
    {
        img: 'https://via.placeholder.com/300x180?text=New+York',
        route: 'Dhaka → New York',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$530.00'
    },
    {
        img: 'https://via.placeholder.com/300x180?text=Sydney',
        route: 'Dhaka → Sydney',
        datum: 'Fri May 21 - Mon Jun 10',
        prijs: '$580.00'
    }
];

let huidigeIndex = 0;

function toonBestemmingen() {
    const container = document.getElementById('bestemming-lijst');
    container.innerHTML = '';
    const zichtbaar = bestemmingen.slice(huidigeIndex, huidigeIndex + 4);
    zichtbaar.forEach((bestemming) => {
        const kaart = document.createElement('div');
        kaart.className = 'bestemming-kaart';
        kaart.innerHTML = `
        <img src="${bestemming.img}" alt="${bestemming.route}">
        <h3>${bestemming.route}</h3>
        <p>${bestemming.datum}</p>
        <p>From <span>${bestemming.prijs}</span></p>
      `;
        container.appendChild(kaart);
    });
}

function volgendeSlide() {
    if (huidigeIndex + 4 < bestemmingen.length) {
        huidigeIndex += 4;
    } else {
        huidigeIndex = 0; // Reset naar begin
    }
    toonBestemmingen();
}

function vorigeSlide() {
    if (huidigeIndex - 4 >= 0) {
        huidigeIndex -= 4;
    } else {
        huidigeIndex = bestemmingen.length - 4;
    }
    toonBestemmingen();
}

// Initieel tonen
toonBestemmingen();