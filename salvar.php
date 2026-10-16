<?php
declare(strict_types=1);
require __DIR__ . '/inc/app.php';
requirePost();

$id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
if ($id === false) {
    http_response_code(400);
    exit('Identificador inválido.');
}
$data = [
    'cliente' => trim(postedString('cliente')),
    'equipamento' => trim(postedString('equipamento')),
    'assunto' => postedString('assunto'),
    'status' => postedString('status'),
];
$errors = [];
if ($data['cliente'] === '') $errors['cliente'] = 'Informe o nome do cliente.';
if ($data['equipamento'] === '') $errors['equipamento'] = 'Informe o equipamento.';
if (mb_strlen($data['cliente']) > 80) $errors['cliente'] = 'Use até 80 caracteres para o cliente.';
if (mb_strlen($data['equipamento']) > 100) $errors['equipamento'] = 'Use até 100 caracteres para o equipamento.';
if (mb_strlen($data['assunto']) > 160) $errors['assunto'] = 'Use até 160 caracteres para o assunto.';
if (!in_array($data['status'], STATUS_OPTIONS, true)) $errors['status'] = 'Selecione um status válido.';

// PONTO DA AULA: falta validar a obrigatoriedade do assunto antes de gravar.
// O fluxo atual aceita string vazia e somente espaços, no cadastro e na edição.

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
        $query = db()->prepare('UPDATE solicitacoes SET cliente=:cliente, equipamento=:equipamento, assunto=:assunto, status=:status WHERE id=:id');
        $query->execute($data + ['id' => $id]);
        $_SESSION['flash'] = 'Solicitação ' . orderCode($id) . ' atualizada.';
    } else {
        $query = db()->prepare('INSERT INTO solicitacoes (cliente, equipamento, assunto, status) VALUES (:cliente, :equipamento, :assunto, :status)');
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
