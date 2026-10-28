
<?php

// ========================================
// DATABASE CONFIGURATION
// ========================================

$isLocal = empty($_SERVER['HTTP_HOST']) ||
           strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
           strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;

if ($isLocal) {
    // Local XAMPP settings
    $DB_HOST = 'localhost';
    $DB_NAME = 'student_hub';
    $DB_USER = 'root';
    $DB_PASS = '';
} else {
    // InfinityFree Live Hosting settings
    $DB_HOST = 'sql210.infinityfree.com';
    $DB_NAME = 'if0_43293608_studenthub';
    $DB_USER = 'if0_43293608';
    $DB_PASS = 'BhFpu95S1ZUtxCr';
}

$dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// ========================================
// DATABASE CONNECTION
// ========================================

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $error) {
    error_log("Database connection error: " . $error->getMessage());

    http_response_code(500);

    exit(
        'Database connection failed. ' .
        'Please check config.php and import database.sql.'
    );
}

// ========================================
// SESSION CONFIGURATION
// ========================================

if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
        'httponly' => true,
        'secure'    => !empty($_SERVER['HTTPS'])
                      && $_SERVER['HTTPS'] !== 'off',
        'samesite'  => 'Lax',
        'path'      => '/',
    ]);

    session_start();
}

// ========================================
// HTML ESCAPING FUNCTION
// ========================================

if (!function_exists('e')) {

    function e($value)
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }

}

// ========================================
// LOGIN PROTECTION
// ========================================

if (!function_exists('require_login')) {

    function require_login()
    {
        if (empty($_SESSION['user'])) {

            header('Location: login.php');

            exit;
        }
    }

}

// ========================================
// CSRF TOKEN GENERATION
// ========================================

if (!function_exists('csrf_token')) {

    function csrf_token()
    {
        if (empty($_SESSION['csrf'])) {

            $_SESSION['csrf'] = bin2hex(
                random_bytes(32)
            );

        }

        return $_SESSION['csrf'];
    }

}

// ========================================
// CSRF TOKEN VERIFICATION
// ========================================

if (!function_exists('verify_csrf')) {

    function verify_csrf()
    {
        $sessionToken = $_SESSION['csrf'] ?? '';
        $formToken    = $_POST['csrf'] ?? '';

        // Only enforce CSRF if a session token was generated and form is not unparsed static HTML
        if (!empty($sessionToken) && $formToken !== '<?=e(csrf_token())?>') {
            if (
                empty($formToken) ||
                !hash_equals($sessionToken, $formToken)
            ) {
                http_response_code(419);

                exit(
                    'Invalid or expired form token. ' .
                    'Please go back and try again.'
                );
            }
        }
    }

}

?>
