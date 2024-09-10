<?php
include 'vendor/autoload.php';

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

$cssDir = 'src/css';
$cssFiles = [
  'font-lato.css',
  'font-montserrat.css',
  'font-opensans.css',
  'font-roboto.css',
  'teamcalneo.css'
];

$jsDir = 'src/js';
$jsFiles = [
  'color-modes.js',
  'teamcalneo.js'
];

/**
 * Open console output
 */
ob_start();

/**
 * Minify CSS
 */
echo "\n-------------------------------------------------------------------------------\nMinifying CSS files...\n-------------------------------------------------------------------------------\n";
foreach ($cssFiles as $file) {
  $minifier = new CSS();
  $minifier->add($cssDir . '/' . $file);
  $minifier->minify($cssDir . '/' . str_replace('.css', '.min.css', $file));
  echo "Minified '$cssDir/$file' => '$cssDir/" . str_replace('.css', '.min.css', $file) . "'.\n";
}

/**
 * Minify JS
 */
echo "\n-------------------------------------------------------------------------------\nMinifying JS files...\n-------------------------------------------------------------------------------\n";
foreach ($jsFiles as $file) {
  $minifier = new JS();
  $minifier->add($jsDir . '/' . $file);
  $minifier->minify($jsDir . '/' . str_replace('.js', '.min.js', $file));
  echo "Minified '$jsDir/$file' => '$jsDir/" . str_replace('.js', '.min.js', $file) . "'.\n";
}

/**
 *
 * Close console output
 */
ob_end_flush();
