<?php
include 'vendor/autoload.php';
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

/**
 * Open console output
 */
ob_start();

/**
 * Minify CSS
 */
echo "\n-------------------------------------------------------------------------------\nMinifying CSS files...\n-------------------------------------------------------------------------------\n";
$minifier = new CSS();
$minifier->add('src/css/teamcalneo.css');
$minifier->minify('src/css/teamcalneo.min.css');
echo "Minified 'src/css/teamcalneo.css' => 'src/css/teamcalneo.min.css'.\n";

/**
 * Minify JS
 */
echo "\n-------------------------------------------------------------------------------\nMinifying JS files...\n-------------------------------------------------------------------------------\n";
$minifier = new JS();
$minifier->add('src/js/ajax.js');
$minifier->minify('src/js/ajax.min.js');
echo "Minified 'src/js/ajax.js' => 'src/js/ajax.min.js'.\n";

$minifier = new JS();
$minifier->add('src/js/funcs.js');
$minifier->minify('src/js/funcs.min.js');
echo "Minified 'src/js/funcs.js' => 'src/js/funcs.min.js'.\n";

$minifier = new JS();
$minifier->add('src/js/modal.js');
$minifier->minify('src/js/modal.min.js');
echo "Minified 'src/js/modal.js' => 'src/js/modal.min.js'.\n";

/**
 *
 * Close console output
 */
ob_end_flush();
