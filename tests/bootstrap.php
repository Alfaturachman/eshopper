<?php
define('ENVIRONMENT', 'testing');
define('ENVIRONMENT_SILENT', true);

// Set directory paths
$system_path = 'system';
$application_folder = 'application';
$view_folder = 'application/views';

// Resolve system path
if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp . DIRECTORY_SEPARATOR;
} else {
    $system_path = strtr(rtrim($system_path, '/\\'), '/\\', DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
}

if (!is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your system folder path does not appear to be set correctly.';
    exit(3);
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SYSDIR', basename(BASEPATH));

if (is_dir($application_folder)) {
    if (($_temp = realpath($application_folder)) !== FALSE) {
        $application_folder = $_temp;
    }
    define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);
} else {
    define('APPPATH', FCPATH . $application_folder . DIRECTORY_SEPARATOR);
}

if (is_dir($view_folder)) {
    if (($_temp = realpath($view_folder)) !== FALSE) {
        $view_folder = $_temp;
    }
    define('VIEWPATH', $view_folder . DIRECTORY_SEPARATOR);
} else {
    define('VIEWPATH', APPPATH . 'views' . DIRECTORY_SEPARATOR);
}

// Load CodeIgniter Core Common functions
require_path(BASEPATH . 'core/Common.php');

// Load Autoloader if available
if (file_exists(FCPATH . 'vendor/autoload.php')) {
    require_once FCPATH . 'vendor/autoload.php';
}

require_once __DIR__ . '/TestCase.php';

function require_path($path) {
    if (file_exists($path)) {
        require_once $path;
    }
}
