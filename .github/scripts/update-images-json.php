<?php

require_once __DIR__ . '/../../api/utils.php';

// Get GitHub token from command line argument
$GITHUB_TOKEN = $argv[1] ?? null;
if (!$GITHUB_TOKEN && file_exists(__DIR__ . '/../../api/config.php')) {
    require_once __DIR__ . '/../../api/config.php';
    $GITHUB_TOKEN = defined('GITHUB_PAT') ? GITHUB_PAT : null;
}

updateImagesJson($GITHUB_TOKEN);
