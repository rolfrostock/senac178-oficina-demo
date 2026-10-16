<?php
declare(strict_types=1);
require __DIR__ . '/inc/app.php';
requirePost();
$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    http_response_code(400);
    exit('Identificador inválido.');
}
$query = db()->prepare('DELETE FROM solicitacoes WHERE id = ?');
$query->execute([$id]);
$_SESSION['flash'] = $query->rowCount() ? 'Solicitação ' . orderCode($id) . ' excluída.' : 'Solicitação não encontrada.';
redirect();
