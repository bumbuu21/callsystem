<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Vercel serverless entry point
|--------------------------------------------------------------------------
|
| Vercel sends every request to this function. Static files are served from
| public/ because assets produced by the Composer build live in the function
| bundle and are not emitted as separate Vercel static assets.
|
*/

$projectRoot = dirname(__DIR__);
$publicRoot = realpath($projectRoot.'/public');
$requestPath = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

if ($publicRoot !== false && $requestPath !== '/') {
    $requestedFile = realpath($publicRoot.DIRECTORY_SEPARATOR.ltrim($requestPath, '/'));

    if (
        $requestedFile !== false
        && is_file($requestedFile)
        && str_starts_with($requestedFile, $publicRoot.DIRECTORY_SEPARATOR)
        && strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION)) !== 'php'
    ) {
        $extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));
        $mimeType = match ($extension) {
            'css' => 'text/css; charset=UTF-8',
            'js', 'mjs' => 'application/javascript; charset=UTF-8',
            'json', 'webmanifest' => 'application/json; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            default => function_exists('mime_content_type')
                ? (mime_content_type($requestedFile) ?: 'application/octet-stream')
                : 'application/octet-stream',
        };

        header('Content-Type: '.$mimeType);
        header('Content-Length: '.filesize($requestedFile));

        if (str_starts_with($requestPath, '/build/')) {
            header('Cache-Control: public, max-age=31536000, immutable');
        }

        readfile($requestedFile);
        exit;
    }
}

require $projectRoot.'/public/index.php';
