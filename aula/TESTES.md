# Testes do caso relacional

O baseline possui FK ativa e cliente_id anulável. A falha é permitir seleção ausente (NULL), não permitir referência a um ID inexistente. Há dois clientes vinculados e uma solicitação inicial sem cliente.

| Caso | Ação | Antes | Depois da correção |
|---|---|---|---|
| T1 | Sem cliente, equipamento e assunto válidos | Salva NULL: falha da regra de negócio. | Bloqueia, mostra mensagem, preserva campos e quantidade. |
| T2 | Selecionar Marina e registrar Trocar teclado | Salva vínculo. | Continua salvando e exibindo nome e contato pelo JOIN. |
| T3 | Na edição de uma OS válida, retirar o cliente | Grava NULL. | Bloqueia e mantém o cliente anterior no banco. |

Antes de clicar, peça o esperado. Depois compare a tela e o contador. Ao testar T3, usar uma OS de teste, sem reatribuir clientes do histórico para simular correção.

Preparação adicional do docente: verificar rejeição de ID inexistente no servidor, FK ativa, bloqueio de exclusão de cliente com pedidos e CRUD de cliente sem pedidos. Não gastar os vinte minutos com esses casos extras.

Após a correção, o pedido antigo sem cliente continua sem cliente. A validação impede novas gravações inválidas; um responsável precisa revisar o histórico com informação confiável. Não inferir o dono do equipamento nem associá-lo por palpite.

Para ensaiar novamente, restaure SOMENTE salvar.php da cópia de base. Não importe schema.sql sobre um banco existente, não remova FK e não apague o banco. As versões anterior e relacional usam bancos diferentes.
