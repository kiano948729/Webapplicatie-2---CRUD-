<?php

// Zorg ervoor dat ROOT_PATH alleen gedefinieerd wordt als het nog niet is
if (!defined('ROOT_PATH')) {
    // Definieer root van het project
    define('ROOT_PATH', realpath(__DIR__ . '/..'));

    // Mapconstanten (file system paths)
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('BACKEND_PATH', ROOT_PATH . '/backend');
    define('FRONTEND_PATH', ROOT_PATH . '/frontend');
    define('INCLUDES_PATH', BACKEND_PATH . '/includes');
    define('FETCH_PATH', BACKEND_PATH . '/fetch');
    define('CONTROLLERS_PATH', BACKEND_PATH . '/controllers');
    define('TEMPLATES_PATH', FRONTEND_PATH . '/templates');
    define('PUBLIC_PATH', FRONTEND_PATH . '/public');
    define('IMG_PATH', PUBLIC_PATH . '/img');

    // URL-pad constanten (voor gebruik in HTML)
    define('ROOT_URL', '');
    define('CONFIG_URL', '/config');
    define('BACKEND_URL', '/backend');
    define('FETCH_URL', BACKEND_URL . '/fetch');
    define('CONTROLLERS_URL', BACKEND_URL . '/controllers');
    define('FRONTEND_URL', '/frontend');
    define('TEMPLATES_URL', FRONTEND_URL . '/templates');
    define('PUBLIC_URL', FRONTEND_URL . '/public');
    define('CSS_URL', PUBLIC_URL . '/css');
    define('JS_URL', PUBLIC_URL . '/js');
    define('IMG_URL', PUBLIC_URL . '/img');

    define('USERS_PATH', BACKEND_PATH . '/users');
    define('ACCOMMODATIONS_PATH', BACKEND_PATH . '/accomodaties');
    define('BOOKINGS_PATH', BACKEND_PATH . '/boekingen_accomodaties');
    define('REVIEWS_PATH', BACKEND_PATH . '/reviews');
    define('CONTACT_PATH', BACKEND_PATH . '/contact');
    define('ADMIN_CSS_URL', '/backend/admin/admin_css');
}

// Start de sessie als deze nog niet actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload core files
if (!isset($GLOBALS['core_loaded'])) {
    require_once CONFIG_PATH . '/databaseConnect.php';
    require_once BACKEND_PATH . '/conn.php';

    // Fetchers
    require_once FETCH_PATH . '/fetch_bestemmingen.php';
    require_once FETCH_PATH . '/fetch_reviews.php';
    require_once FETCH_PATH . '/fetch_boeking_accomodatie.php';
    require_once FETCH_PATH . '/fetch_deals.php'; // Optioneel, afhankelijk van jouw gebruik

    $GLOBALS['core_loaded'] = true; // Voorkom dubbele includes
}

// Huidige gebruiker uit sessie halen
$current_user = $_SESSION['username'] ?? null;