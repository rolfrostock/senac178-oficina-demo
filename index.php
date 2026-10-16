<?php
declare(strict_types=1);
require __DIR__ . '/inc/app.php';
$form = ['cliente' => '', 'equipamento' => '', 'assunto' => '', 'status' => 'Recebido'];
$errors = [];
$editId = filter_var($_GET['editar'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
if ($editId === false) {
    http_response_code(400);
    exit('Identificador inválido.');
}
if ($editId) {
    $query = db()->prepare('SELECT * FROM solicitacoes WHERE id=?');
    $query->execute([$editId]);
    $existing = $query->fetch();
    if (!$existing) {
        http_response_code(404);
        exit('Solicitação não encontrada.');
    }
    $form = array_intersect_key($existing, $form);
}
if (isset($_SESSION['form']) && $_SESSION['form']['id'] === $editId) {
    $form = $_SESSION['form']['data'];
    $errors = $_SESSION['form']['errors'];
    unset($_SESSION['form']);
}
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
$q = is_string($_GET['q'] ?? null) ? mb_substr(trim($_GET['q']), 0, 100) : '';
$status = is_string($_GET['status'] ?? null) && in_array($_GET['status'], STATUS_OPTIONS, true) ? $_GET['status'] : '';
$where = []; $params = [];
if ($q !== '') { $where[] = '(cliente LIKE ? OR equipamento LIKE ? OR assunto LIKE ?)'; $params = array_fill(0, 3, '%' . $q . '%'); }
if ($status !== '') { $where[] = 'status = ?'; $params[] = $status; }
$sql = 'SELECT * FROM solicitacoes' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY id DESC';
$query = db()->prepare($sql); $query->execute($params); $rows = $query->fetchAll();
$stats = db()->query("SELECT COUNT(*) AS total, COALESCE(SUM(status <> 'Concluído'),0) AS abertas, COALESCE(SUM(status = 'Concluído'),0) AS concluidas FROM solicitacoes")->fetch();
$focusId = $errors ? (array_key_exists('assunto', $errors) ? 'assunto' : (array_key_exists('cliente', $errors) ? 'cliente' : 'equipamento')) : '';
?><!doctype html>
<html lang="pt-BR" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ordens de serviço · Oficina Byte</title><script src="assets/theme.js"></script><link rel="stylesheet" href="assets/style.css"><link rel="stylesheet" href="assets/theme.css"><script src="assets/app.js" defer></script></head>
<body><header class="topbar"><div class="brand"><span class="brandmark" aria-hidden="true">OB</span><div><strong>Oficina Byte</strong><span>Assistência de computadores</span></div></div><div class="topbar-actions"><div class="environment">Ambiente de demonstração · dados fictícios</div><button type="button" id="alternar-tema" class="theme-toggle" aria-label="Ativar modo claro">Modo claro</button></div></header>
<main><div class="page-heading"><div><p class="eyebrow">RECEPÇÃO E ATENDIMENTO</p><h1>Ordens de serviço</h1><p class="subtitle">Registre o atendimento e acompanhe as solicitações da oficina.</p></div><a class="button primary" href="index.php#formulario">Nova solicitação <span aria-hidden="true">+</span></a></div>
<?php if ($flash): ?><div class="flash" role="status"><?= e($flash) ?></div><?php endif; ?>
<div class="metrics"><div><span>Total de solicitações</span><strong><?= (int)$stats['total'] ?></strong></div><div><span>Em atendimento</span><strong><?= (int)$stats['abertas'] ?></strong></div><div><span>Concluídas</span><strong><?= (int)$stats['concluidas'] ?></strong></div></div>
<div class="workspace"><section class="records" aria-labelledby="list-title"><div class="section-heading"><h2 id="list-title">Solicitações registradas</h2><span><?= count($rows) ?> resultado(s)</span></div>
<form class="filters" method="get" action="index.php"><div><label for="busca">Buscar solicitação</label><input id="busca" name="q" value="<?= e($q) ?>" placeholder="Cliente, equipamento ou assunto"></div><div><label for="filtro-status">Status</label><select id="filtro-status" name="status"><option value="">Todos os status</option><?php foreach(STATUS_OPTIONS as $option): ?><option <?= $status===$option?'selected':'' ?>><?= e($option) ?></option><?php endforeach; ?></select></div><button class="button secondary" type="submit">Filtrar</button><?php if($q!==''||$status!==''): ?><a href="index.php">Limpar</a><?php endif; ?></form>
<div class="table-scroll"><table><thead><tr><th>OS / entrada</th><th>Cliente / equipamento</th><th>Assunto</th><th>Status</th><th>Ações</th></tr></thead><tbody>
<?php foreach($rows as $row): ?><tr data-id="<?= (int)$row['id'] ?>"><td><strong><?= e(orderCode((int)$row['id'])) ?></strong><small><?= e(date('d/m/Y', strtotime($row['criado_em']))) ?></small></td><td><strong><?= e($row['cliente']) ?></strong><small><?= e($row['equipamento']) ?></small></td><td class="subject"><?php if(trim($row['assunto'])===''): ?><span class="missing">Sem assunto</span><?php else: ?><?= e($row['assunto']) ?><?php endif; ?></td><td><span class="status <?= $row['status']==='Concluído'?'done':($row['status']==='Em diagnóstico'?'progress':'new') ?>"><?= e($row['status']) ?></span></td><td><div class="actions"><a href="index.php?editar=<?= (int)$row['id'] ?>#formulario" aria-label="Editar solicitação <?= e(orderCode((int)$row['id'])) ?>">Editar</a><form method="post" action="excluir.php" data-delete="<?= e(orderCode((int)$row['id'])) ?>"><input type="hidden" name="csrf" value="<?= e(token()) ?>"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button type="submit" class="delete" aria-label="Excluir solicitação <?= e(orderCode((int)$row['id'])) ?>">Excluir</button></form></div></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="5" class="empty">Nenhuma solicitação encontrada.</td></tr><?php endif; ?></tbody></table></div><p class="storage-note">Os registros ficam salvos no banco de dados, inclusive após atualizar a página.</p></section>
<section class="editor" aria-labelledby="form-title"><p class="eyebrow"><?= $editId?'ATUALIZAÇÃO':'NOVO ATENDIMENTO' ?></p><h2 id="form-title"><?= $editId?'Editar solicitação '.e(orderCode($editId)):'Nova solicitação' ?></h2><p class="form-description">Preencha os dados do pedido de reparo.</p>
<?php if($errors): ?><div class="error-summary" role="alert"><strong>Confira os dados antes de salvar.</strong><ul><?php foreach($errors as $message): ?><li><?= e($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form id="formulario" method="post" action="salvar.php" data-focus="<?= e($focusId) ?>"><input type="hidden" name="csrf" value="<?= e(token()) ?>"><input type="hidden" name="id" value="<?= (int)$editId ?>">
<label for="cliente">Cliente</label><input id="cliente" name="cliente" maxlength="80" required value="<?= e($form['cliente']) ?>" autocomplete="off">
<label for="equipamento">Equipamento</label><input id="equipamento" name="equipamento" maxlength="100" required value="<?= e($form['equipamento']) ?>" placeholder="Ex.: notebook Dell" autocomplete="off">
<label for="assunto">Assunto</label><input id="assunto" name="assunto" maxlength="160" value="<?= e($form['assunto']) ?>" placeholder="Ex.: computador não liga" <?= isset($errors['assunto'])?'aria-invalid="true" aria-describedby="erro-assunto"':'' ?> autocomplete="off">
<?php if(isset($errors['assunto'])): ?><p id="erro-assunto" class="field-error"><?= e($errors['assunto']) ?></p><?php endif; ?>
<label for="status">Status do atendimento</label><select id="status" name="status"><?php foreach(STATUS_OPTIONS as $option): ?><option <?= $form['status']===$option?'selected':'' ?>><?= e($option) ?></option><?php endforeach; ?></select>
<button type="submit" class="button primary submit"><?= $editId?'Salvar alterações':'Cadastrar solicitação' ?></button><?php if($editId): ?><a class="cancel" href="index.php#formulario">Cancelar edição</a><?php endif; ?></form></section></div>
<footer>Oficina Byte é uma empresa fictícia para atividades de aprendizagem. Aplicação local em PHP e MySQL.</footer></main></body></html>
