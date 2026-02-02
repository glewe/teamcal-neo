<?php
declare(strict_types=1);

include dirname(__DIR__) . '/vendor/autoload.php';

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

$cssDir   = dirname(__DIR__) . '/public/css';
$cssFiles = [
  'font-lato.css',
  'font-montserrat.css',
  'font-opensans.css',
  'font-poppins.css',
  'font-roboto.css',
  'font-robotomono.css',
  'teamcalneo.css'
];

$jsDir   = dirname(__DIR__) . '/public/js';
$jsFiles = [
  'color-modes.js',
  'width-modes.js',
  'teamcalneo.js'
];

/**
 * Open console output
 */
ob_start();

/**
 * Minify CSS
 */
echo "\nMinifying CSS files...\n";
foreach ($cssFiles as $file) {
  $minifier = new CSS();
  $minifier->add($cssDir . '/' . $file);
  $minifier->minify($cssDir . '/' . str_replace('.css', '.min.css', $file));
  echo "Minified '$cssDir/$file' => '$cssDir/" . str_replace('.css', '.min.css', $file) . "'.\n";
}

/**
 * Minify JS
 */
echo "\nMinifying JS files...\n";
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
