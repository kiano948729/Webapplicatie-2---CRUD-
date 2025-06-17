<div class="vakanties-main-blok">
    <h2>Find the best place</h2>
    <form action="backend/search.php" method="get">
        <div class="input-rij">
            <div class="invoer-blok">
                <label for="locatie">Location</label>
                <input type="text" id="locatie" name="locatie" placeholder="Type the destination">
            </div>

            <div class="invoer-blok">
                <label for="check-in">Check in</label>
                <input type="date" id="check-in" name="check-in">
            </div>

            <div class="invoer-blok">
                <label for="check-out">Check out</label>
                <input type="date" id="check-out" name="check-out">
            </div>
        </div>
        <div class="filters">
            <input type="radio" id="filter-huis" name="filter" value="house">
            <label for="filter-huis">House</label>

            <input type="radio" id="filter-hotel" name="filter" value="hotel">
            <label for="filter-hotel">Hotel</label>

            <input type="radio" id="filter-residentieel" name="filter" value="residential">
            <label for="filter-residentieel">Residential</label>

            <input type="radio" id="filter-appartement" name="filter" value="apartment">
            <label for="filter-appartement">Apartment</label>
        </div>

        <div class="zoek-knop">
            <button type="submit">Search Properties</button>
        </div>
    </form>
</div>

