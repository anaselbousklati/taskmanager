<?php

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        die('Fout: .env bestand niet gevonden. Kopieer .env.example naar .env en vul je gegevens in.');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);

            $value = trim($value, '"\'');

            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

loadEnv(dirname(__DIR__) . '/.env');
