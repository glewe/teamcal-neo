<?php
declare(strict_types=1);

/**
 * Extract Release Notes from doc/releaseinfo.php
 * Usage: php tools/extract_release_notes.php <version>
 */

if ($argc < 2) {
    echo "Usage: php tools/extract_release_notes.php <version>\n";
    exit(1);
}

$version = $argv[1];

// Handle cases where version might come with 'v' prefix
if (strpos($version, 'v') === 0) {
    $version = substr($version, 1);
}

// Load the releases array
// Capture output to prevent HTML trailing part of the file from printing
ob_start();
// Define dummy $LANG to prevent warnings in the HTML part of releaseinfo.php
$LANG = [];
$oldLevel = error_reporting(0);
include dirname(__DIR__) . '/doc/releaseinfo.php';
error_reporting($oldLevel);
ob_end_clean();

/** @var array $releases */
if (!isset($releases) || !is_array($releases)) {
    echo "Error: \$releases array not found in releaseinfo.php\n";
    exit(1);
}

$targetRelease = null;
foreach ($releases as $release) {
    if ($release['version'] === $version) {
        $targetRelease = $release;
        break;
    }
}

if (!$targetRelease) {
    echo "Release note for version $version not found.\n";
    exit(0); // Exit successfully with empty output (optional) or fail. Let's output nothing.
}

// Build Markdown Output
$output = "";

if (!empty($targetRelease['info'])) {
    $output .= $targetRelease['info'] . "\n\n";
}

$sections = [
    'bugfixes' => '**Bugfixes**',
    'features' => '**Features**',
    'improvements' => '**Improvements**',
    'removals' => '**Removals**',
];

foreach ($sections as $key => $header) {
    if (!empty($targetRelease[$key]) && is_array($targetRelease[$key])) {
        $output .= $header . "\n";
        foreach ($targetRelease[$key] as $item) {
            $line = "- " . $item['summary'];
            if (!empty($item['issue'])) {
                $line .= " ([Issue](" . $item['issue'] . "))";
            }
            $output .= $line . "\n";
        }
        $output .= "\n";
    }
}

echo trim($output);
