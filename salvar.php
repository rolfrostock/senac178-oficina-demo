<?php
declare(strict_types=1);
require __DIR__ . '/inc/app.php';
requirePost();

$id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
if ($id === false) {
    http_response_code(400);
    exit('Identificador inválido.');
}
// Seleção vazia vira NULL. A FK permite esse valor no esquema de demonstração.
$rawClient = postedString('cliente_id');
$clientId = $rawClient === '' ? null : filter_var($rawClient, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$data = [
    'cliente_id' => $clientId,
    'equipamento' => trim(postedString('equipamento')),
    'assunto' => trim(postedString('assunto')),
    'status' => postedString('status'),
];
$errors = [];
if ($clientId === false) $errors['cliente_id'] = 'Selecione um cliente válido.';
if (is_int($clientId)) {
    $clientQuery = db()->prepare('SELECT id FROM clientes WHERE id = ?');
    $clientQuery->execute([$clientId]);
    if (!$clientQuery->fetchColumn()) $errors['cliente_id'] = 'O cliente selecionado não existe.';
}
if ($data['assunto'] === '') $errors['assunto'] = 'Informe o assunto da solicitação.';
if ($data['equipamento'] === '') $errors['equipamento'] = 'Informe o equipamento.';
if (mb_strlen($data['equipamento']) > 100) $errors['equipamento'] = 'Use até 100 caracteres para o equipamento.';
if (mb_strlen($data['assunto']) > 160) $errors['assunto'] = 'Use até 160 caracteres para o assunto.';
if (!in_array($data['status'], STATUS_OPTIONS, true)) $errors['status'] = 'Selecione um status válido.';

// PONTO DA AULA: falta exigir um cliente quando a seleção está vazia.
// cliente_id NULL é aceito, embora a regra de negócio exija um vínculo.

if ($errors) {
    $_SESSION['form'] = ['data' => $data, 'errors' => $errors, 'id' => $id];
    redirect('index.php' . ($id ? '?editar=' . $id : '') . '#formulario');
}

try {
    if ($id) {
        $check = db()->prepare('SELECT id FROM solicitacoes WHERE id = ?');
        $check->execute([$id]);
        if (!$check->fetchColumn()) {
            http_response_code(404);
            exit('Solicitação não encontrada.');
        }
        $query = db()->prepare('UPDATE solicitacoes SET cliente_id=:cliente_id, equipamento=:equipamento, assunto=:assunto, status=:status WHERE id=:id');
        $query->execute($data + ['id' => $id]);
        $_SESSION['flash'] = 'Solicitação ' . orderCode($id) . ' atualizada.';
    } else {
        $query = db()->prepare('INSERT INTO solicitacoes (cliente_id, equipamento, assunto, status) VALUES (:cliente_id, :equipamento, :assunto, :status)');
        $query->execute($data);
        $_SESSION['flash'] = 'Solicitação ' . orderCode((int) db()->lastInsertId()) . ' cadastrada.';
    }
    unset($_SESSION['form']);
    redirect();
} catch (PDOException $exception) {
    error_log('Oficina SENAC: falha ao salvar. Código: ' . $exception->getCode());
    $_SESSION['form'] = ['data' => $data, 'errors' => ['geral' => 'Não foi possível salvar. Tente novamente.'], 'id' => $id];
    redirect('index.php' . ($id ? '?editar=' . $id : '') . '#formulario');
}
