<?php
declare(strict_types=1);

// A configuração local é criada pelo instalador e não deve ser compartilhada.
$local = is_file(__DIR__ . '/config.local.php') ? require __DIR__ . '/config.local.php' : [];
return array_replace([
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'senac178_oficina_relacional',
    'username' => 'senac178_rel',
    'password' => '',
], $local);
