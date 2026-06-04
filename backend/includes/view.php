    <?php

/**
 * =====================================================
 * HELPER FUNCTIONS - VIEW CLASS
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */

// View dengan sebuah main template
function viewWithMainTemplate($fileName,$data = [],  $mainName = 'layouts/main') {
    extract($data);

    // Buffering content
    ob_start();
    include VIEWS_PATH . $fileName . '.php';
    $content = ob_get_clean();

    include VIEWS_PATH . $mainName . ".php";
}

// View tanpa sebuah main template
function view ($fileName, $data = []) {
    extract($data);

    // Buffering content
    ob_start();
    include VIEWS_PATH . $fileName . '.php';
    $content = ob_get_clean();
    
    echo $content;
}

// Include komponen view
function includes ($fileName, $data = []) {
    extract($data);

    // Buffering content
    ob_start();
    include VIEWS_PATH . $fileName . '.php';
    $content = ob_get_clean();
    
    return $content;
}

// Include komponen view
function includesWithUri ($uri, $fileName, $data = []) {

    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($requestUri !==  \FIRSTSECTION_URI . $uri ) return false;
    extract($data);

    // Buffering content
    ob_start();
    include VIEWS_PATH . $fileName . '.php';
    $content = ob_get_clean();
    
    return $content;
}


// Deklarasi path js
function pathJs ($fileName) {
    return BASE_URL . 'public/js/' . $fileName . ".js";
}

// Deklarasi path css
function pathCss ($fileName) {
    return BASE_URL . 'public/css/' . $fileName . ".css";
}

// Menampilkan errors 
function headerError($nameSession) {
    if ( isset($_SESSION['errors_messages'][$nameSession]) ) {
        $tampilkan = $_SESSION['errors_messages'][$nameSession];

        echo "
        <div class='alert alert-danger alert-dismissible fade shows' role='alert'>"
            . \e($tampilkan) .
            "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>";    

        $_SESSION['errors_messages'][$nameSession] = null;
        unset($_SESSION['errors_messages'][$nameSession]);
        
    }
}

function messageError($nameSession) {
    if ( isset($_SESSION['errors_messages'][$nameSession]) ) {
        $tampilkan = $_SESSION['errors_messages'][$nameSession];

        echo "
        <div class='invalid-feedback shows'>
            " . e($tampilkan ?? 'Nama lengkap wajib di isi!!') . "
        </div>";    

        $_SESSION['errors_messages'][$nameSession] = null;
        unset($_SESSION['errors_messages'][$nameSession]);
    }
}


/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    startSession();
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get dan hapus flash message
 */
function getFlashMessage() {
    startSession();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Menampilkan flash message dalam HTML
 */
function displayFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        $alertClass = $flash['type'] === 'success' ? 'alert-success' : 'alert-danger';
        echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">';
        echo htmlspecialchars($flash['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
    }
}

/**
 * Redirect ke URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}
