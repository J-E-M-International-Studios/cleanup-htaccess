<?php
// cleanup-htaccess.php

// Root path (where this script is running from)
$root = __DIR__;
$mainHtaccess = $root . '/.htaccess';

echo "<h2>Cleaning up duplicate .htaccess files...</h2>";

$deleted = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    if ($file->getFilename() === '.htaccess') {
        $filePath = $file->getRealPath();

        // Skip the main root .htaccess
        if ($filePath === realpath($mainHtaccess)) {
            continue;
        }

        // Try to delete the file
        if (@unlink($filePath)) {
            $deleted[] = $filePath;
            echo "🧹 Deleted: <code>{$filePath}</code><br>";
        } else {
            echo "❌ Could not delete: <code>{$filePath}</code><br>";
        }
    }
}

if (empty($deleted)) {
    echo "<p>✅ No duplicate .htaccess files found.</p>";
} else {
    echo "<p>✅ Done. " . count($deleted) . " duplicate .htaccess file(s) removed.</p>";
}
?>
