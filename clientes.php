<?php
declare(strict_types=1);
require __DIR__ . '/inc/app.php';
$errors=[]; $values=['nome'=>'','contato'=>''];
$editId=filter_var($_GET['editar']??0,FILTER_VALIDATE_INT,['options'=>['min_range'=>0]]);
if($editId===false){http_response_code(400);exit('Identificador inválido.');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    requirePost();
    $id=filter_var($_POST['id']??0,FILTER_VALIDATE_INT,['options'=>['min_range'=>0]]);
    if($id===false){http_response_code(400);exit('Identificador inválido.');}
    if(postedString('acao')==='excluir'){
        $check=db()->prepare('SELECT COUNT(*) FROM solicitacoes WHERE cliente_id=?');$check->execute([$id]);
        if((int)$check->fetchColumn()>0){$_SESSION['flash']='Cliente mantido: existem solicitações vinculadas.';redirect('clientes.php');}
        $query=db()->prepare('DELETE FROM clientes WHERE id=?');$query->execute([$id]);
        $_SESSION['flash']='Cliente excluído.';redirect('clientes.php');
    }
    if(postedString('acao')!=='salvar'){http_response_code(400);exit('Ação inválida.');}
    $values=['nome'=>trim(postedString('nome')),'contato'=>trim(postedString('contato'))];
    foreach($values as $field=>$value){if($value===''||mb_strlen($value)>80)$errors[$field]='Preencha '.($field==='nome'?'o nome':'o contato').' com até 80 caracteres.';}
    $editId=$id;
    if(!$errors){
        if($id){$query=db()->prepare('UPDATE clientes SET nome=:nome,contato=:contato WHERE id=:id');$query->execute($values+['id'=>$id]);}
        else{$query=db()->prepare('INSERT INTO clientes(nome,contato) VALUES(:nome,:contato)');$query->execute($values);}
        $_SESSION['flash']='Cliente salvo.';redirect('clientes.php');
    }
}elseif($editId){$query=db()->prepare('SELECT nome,contato FROM clientes WHERE id=?');$query->execute([$editId]);$values=$query->fetch();if(!$values){http_response_code(404);exit('Cliente não encontrado.');}}
$rows=db()->query('SELECT c.*, (SELECT COUNT(*) FROM solicitacoes s WHERE s.cliente_id=c.id) AS pedidos FROM clientes c ORDER BY c.nome')->fetchAll();
$focusId = $errors ? (isset($errors['nome']) ? 'nome' : 'contato') : ((isset($_GET['novo']) || $editId) ? 'nome' : '');
$flash=$_SESSION['flash']??'';unset($_SESSION['flash']);
?><!doctype html><html lang="pt-BR" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Clientes · Oficina Byte</title><script src="assets/theme.js"></script><link rel="stylesheet" href="assets/style.css"><link rel="stylesheet" href="assets/theme.css"><script src="assets/app.js" defer></script></head><body class="clients-page">
<header class="topbar"><div class="brand"><span class="brandmark">OB</span><div><strong>Oficina Byte</strong><span>Assistência de computadores</span></div></div><div class="topbar-actions"><a href="index.php">Ordens de serviço</a><button type="button" id="alternar-tema" class="theme-toggle" aria-label="Ativar modo claro">Modo claro</button></div></header>
<main><div class="page-heading"><div><p class="eyebrow">CADASTRO DE APOIO</p><h1>Clientes</h1><p class="subtitle">Identificação e contato para vincular os pedidos da oficina.</p></div><a class="button primary" href="clientes.php?novo=1#formulario">Novo cliente <span aria-hidden="true">+</span></a></div>
<?php if($flash):?><div class="flash" role="status"><?=e($flash)?></div><?php endif;?>
<div class="workspace"><section class="records"><div class="section-heading"><h2>Clientes cadastrados</h2><span><?=count($rows)?> registro(s)</span></div><div class="table-scroll"><table><thead><tr><th>ID</th><th>Nome</th><th>Contato</th><th>Pedidos</th><th>Ações</th></tr></thead><tbody>
<?php foreach($rows as $row):?><tr><td><?=(int)$row['id']?></td><td><?=e($row['nome'])?></td><td><?=e($row['contato'])?></td><td><?=(int)$row['pedidos']?></td><td><div class="actions"><a href="clientes.php?editar=<?=(int)$row['id']?>#formulario">Editar</a><form action="clientes.php" method="post" data-delete="cliente #<?=(int)$row['id']?>"><input type="hidden" name="csrf" value="<?=e(token())?>"><input type="hidden" name="id" value="<?=(int)$row['id']?>"><input type="hidden" name="acao" value="excluir"><button class="delete" type="submit">Excluir</button></form></div></td></tr><?php endforeach;?></tbody></table></div><p class="storage-note">Clientes com solicitações vinculadas não podem ser excluídos.</p></section>
<section class="editor"><h2><?=$editId?'Editar cliente':'Novo cliente'?></h2><?php if($errors):?><div class="error-summary" role="alert"><?=e(implode(' ',$errors))?></div><?php endif;?><form id="formulario" action="clientes.php" method="post" data-focus="<?=e($focusId)?>"><input type="hidden" name="csrf" value="<?=e(token())?>"><input type="hidden" name="id" value="<?=(int)$editId?>"><input type="hidden" name="acao" value="salvar"><label for="nome">Nome</label><input id="nome" name="nome" value="<?=e($values['nome'])?>" required maxlength="80"><label for="contato">Contato</label><input id="contato" name="contato" value="<?=e($values['contato'])?>" required maxlength="80"><button type="submit" class="button primary submit">Salvar cliente</button><?php if($editId):?><a class="cancel" href="clientes.php">Cancelar edição</a><?php endif;?></form></section></div><footer>Dados fictícios para aprendizagem. Modelo Clientes e Solicitações.</footer></main></body></html>
