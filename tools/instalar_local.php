<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Execute pelo terminal.');
}
// Este instalador cria somente a pasta e o banco novos, sem substituir existentes.
$source = dirname(__DIR__);
$destination = 'C:/wamp64/www/aula-senac178';
$database = 'senac178_oficina_relacional';
$username = 'senac178_rel';
$password = bin2hex(random_bytes(24));
if (file_exists($destination)) exit("A pasta de destino já existe. Nenhum arquivo foi alterado.\n");
$admin = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$check = $admin->prepare('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME=?');
$check->execute([$database]);
if ($check->fetchColumn()) exit("O banco de destino já existe. Nenhum banco foi alterado.\n");
$check = $admin->prepare('SELECT COUNT(*) FROM mysql.user WHERE User=?');
$check->execute([$username]);
if ((int)$check->fetchColumn() > 0) exit("O usuário de banco de destino já existe. Nenhuma credencial foi alterada.\n");

function copyProject(string $source, string $destination): void
{
    if (!mkdir($destination, 0775, true) && !is_dir($destination)) throw new RuntimeException('Não foi possível criar a pasta.');
    foreach (new DirectoryIterator($source) as $entry) {
        if ($entry->isDot() || in_array($entry->getFilename(), ['config.local.php', '.git', 'backups'], true)) continue;
        $target = $destination . '/' . $entry->getFilename();
        if ($entry->isDir()) copyProject($entry->getPathname(), $target);
        elseif (!copy($entry->getPathname(), $target)) throw new RuntimeException('Falha na cópia de um arquivo.');
    }
}
copyProject($source, $destination);
$admin->exec('CREATE DATABASE `' . $database . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$admin->exec('USE `' . $database . '`');
$admin->exec(file_get_contents(__DIR__ . '/schema.sql'));
$account = $admin->quote($username) . "@'127.0.0.1'";
$admin->exec('CREATE USER ' . $account . ' IDENTIFIED BY ' . $admin->quote($password));
$admin->exec('GRANT SELECT, INSERT, UPDATE, DELETE ON `' . $database . '`.* TO ' . $account);
$config = ['host' => '127.0.0.1', 'port' => 3306, 'database' => $database, 'username' => $username, 'password' => $password];
file_put_contents($destination . '/config.local.php', "<?php\n// Credencial exclusiva deste computador. Não compartilhar.\nreturn " . var_export($config, true) . ";\n");
$verify = new PDO('mysql:host=127.0.0.1;port=3306;dbname=' . $database . ';charset=utf8mb4', $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
echo 'Instalado em: ' . $destination . "\n";
echo 'Banco exclusivo: ' . $database . "\n";
echo 'Registros fictícios: ' . $verify->query('SELECT COUNT(*) FROM solicitacoes')->fetchColumn() . "\n";
echo "URL: http://localhost/aula-senac178/\n";
echo "Falha de obrigatoriedade do cliente preservada para a aula.\n";
