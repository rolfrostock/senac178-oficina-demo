<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Execute pelo terminal.');
}

$root = dirname(__DIR__);
if (isset($argv[1])) {
    $root = realpath($argv[1]);
    if ($root === false || !is_dir($root)) {
        fwrite(STDERR, "Pasta de verificação inválida.\n");
        exit(2);
    }
}
$extensions = ['php', 'js', 'css', 'md', 'txt', 'sql', 'json', 'yml', 'yaml'];
$special = ['.htaccess', '.editorconfig'];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
$count = 0;
$failures = [];
foreach ($files as $file) {
    if (!$file->isFile() || (!in_array(strtolower($file->getExtension()), $extensions, true) && !in_array($file->getFilename(), $special, true))) continue;
    $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    if (str_starts_with($relative, '.git/') || str_contains($relative, '/node_modules/')) continue;
    $count++;
    $bytes = file_get_contents($file->getPathname());
    if ($bytes === false) { $failures[] = $relative . ': leitura indisponível'; continue; }
    if (str_starts_with($bytes, "\xEF\xBB\xBF")) $failures[] = $relative . ': BOM UTF-8';
    if (preg_match('//u', $bytes) !== 1) { $failures[] = $relative . ': bytes inválidos em UTF-8'; continue; }
    // Heurística de corrupção de texto. Não substitui a leitura dos termos em português.
    if (preg_match('/[\x{00C2}\x{00C3}][\x{0080}-\x{00BF}]|\x{00E2}[\x{0080}-\x{00BF}]{2}|\x{FFFD}/u', $bytes)) {
        $failures[] = $relative . ': possível texto corrompido';
    }
}
echo 'Arquivos verificados: ' . $count . "\n";
if ($failures) {
    foreach ($failures as $failure) fwrite(STDERR, $failure . "\n");
    exit(1);
}
echo "OK: UTF-8 válido, sem BOM ou padrões conhecidos de texto corrompido.\n";
echo "Confira também a acentuação na tela. A heurística não detecta toda corrupção possível.\n";
