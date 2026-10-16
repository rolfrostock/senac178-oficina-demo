<?php
declare(strict_types=1);

date_default_timezone_set('America/Sao_Paulo');
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1', '::ffff:127.0.0.1'], true)) {
    http_response_code(403);
    exit('Demonstração disponível somente neste computador.');
}
session_name('SENAC178OFICINA');
session_set_cookie_params(['path' => '/aula-senac178/', 'httponly' => true, 'samesite' => 'Lax']);
session_start();
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self'; base-uri 'none'; frame-ancestors 'none'; form-action 'self'");

const STATUS_OPTIONS = ['Recebido', 'Em diagnóstico', 'Concluído'];

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        $config = require dirname(__DIR__) . '/config.php';
        try {
            $pdo = new PDO(
                sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['database']),
                $config['username'], $config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]
            );
        } catch (PDOException $exception) {
            error_log('Oficina SENAC: conexão indisponível. Código: ' . $exception->getCode());
            http_response_code(503);
            exit('Não foi possível conectar ao banco da demonstração. Verifique o MySQL e a configuração local.');
        }
    }
    return $pdo;
}

function token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(24));
}

function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        http_response_code(405);
        exit('Método não permitido.');
    }
    $supplied = $_POST['csrf'] ?? null;
    if (!is_string($supplied) || !hash_equals(token(), $supplied)) {
        http_response_code(403);
        exit('A sessão do formulário expirou. Volte à página e tente novamente.');
    }
}

function redirect(string $path = 'index.php'): never
{
    header('Location: ' . $path, true, 303);
    exit;
}

function postedString(string $name): string
{
    return is_string($_POST[$name] ?? null) ? $_POST[$name] : '';
}

function orderCode(int $id): string
{
    return '#' . str_pad((string) $id, 3, '0', STR_PAD_LEFT);
}
