<?php
require_once __DIR__ . '/phpstan-bootstrap.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
    'cache' => false,
    'debug' => true,
]);

// Mock 'isAllowed' function
$twig->addFunction(new \Twig\TwigFunction('isAllowed', function ($permission) {
    return true;
}));

// Mock 'url_decode' filter
$twig->addFilter(new \Twig\TwigFilter('url_decode', function ($string) {
    return urldecode($string);
}));

try {
    $twig->parse($twig->tokenize($twig->getLoader()->getSourceContext('sidebar.twig')));
    echo "SUCCESS: sidebar.twig compiled successfully.\n";
} catch (\Exception $e) {
    echo "FAIL: Compilation error: " . $e->getMessage() . "\n";
    exit(1);
}
