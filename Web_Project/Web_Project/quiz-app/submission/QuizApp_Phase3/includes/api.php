<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function set_api_cors_headers(): void
{
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    // Allow local dev origins on any Vite port (e.g. 5173, 5174).
    if (preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?$#', $origin) === 1) {
        header("Access-Control-Allow-Origin: {$origin}");
        header('Vary: Origin');
        header('Access-Control-Allow-Credentials: true');
    }

    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
}

function api_bootstrap(string $method): void
{
    set_api_cors_headers();
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        api_response(405, ['error' => 'Method not allowed.']);
    }
}

function api_json_input(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);

    if (!is_array($decoded)) {
        api_response(400, ['error' => 'Invalid JSON payload.']);
    }

    return $decoded;
}

function api_response(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}
