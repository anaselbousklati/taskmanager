<?php

function redirect(string $path): void
{
    $base = rtrim(getenv('APP_URL') ?: '', '/');
    header("Location: $base/index.php?page=$path");
    exit;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}


function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Je moet ingelogd zijn om deze pagina te bekijken.'];
        redirect('login');
    }
}


function sanitize(string $input): string
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}


function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}


function validateCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Ongeldige beveiligingstoken. Ververs de pagina en probeer opnieuw.');
    }
}

function getFlash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function priorityBadge(string $priority): string
{
    return match ($priority) {
        'hoog'   => 'badge-danger',
        'normaal' => 'badge-warning',
        'laag'   => 'badge-info',
        default  => 'badge-secondary',
    };
}

function statusBadge(string $status): string
{
    return match ($status) {
        'gedaan' => 'badge-success',
        'bezig'  => 'badge-warning',
        'open'   => 'badge-secondary',
        default  => 'badge-secondary',
    };
}

function isOverdue(?string $deadline, string $status): bool
{
    if (empty($deadline) || $status === 'gedaan') return false;
    return strtotime($deadline) < strtotime('today');
}

function formatDate(?string $date): string
{
    if (empty($date)) return '-';
    return date('d M Y', strtotime($date));
}
