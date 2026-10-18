# Oficina Byte com duas tabelas

Versão de preparação 1.1.0. Aula SENAC PR 178/2026 em 19/10/2026 às 16h, duração de vinte minutos, banca de três pessoas como alunos. Tema: Desenvolvimento de sistemas com apoio da Inteligência Artificial: da identificação do problema à implementação de soluções.

## Caso e falha preparados

O CRUD já está pronto. Clientes(id, nome, contato) se relaciona com Solicitacoes(id, cliente_id, equipamento, assunto, status). Uma pessoa pode ter várias solicitações. A FK existe, mas cliente_id permite NULL. A aplicação também deixa a seleção vazia, permitindo pedidos sem cliente e sem contato. Essa é a falha didática principal. O assunto já é obrigatório nesta versão.

O campo anulável torna a base representativa de um sistema que aceitava vínculo opcional, enquanto a regra de negócio agora exige cliente identificado. Não afirme que a FK está quebrada: ela verifica IDs preenchidos, mas não impõe a obrigatoriedade nesta estrutura.

## Execução

URL nesta máquina: http://localhost/aula-senac178/ . Pasta: C:\wamp64\www\aula-senac178. Novo banco: senac178_oficina_relacional. O banco anterior senac178_oficina_demo permanece intacto. Configuração local e backups ficam fora do Git.

Para uma instalação nova, tools/instalar_local.php exige WAMP em C:\wamp64, MySQL na porta 3306, administração local padrão sem senha e destino livre. Ele recusa pasta, banco ou usuário já existentes. Para atualizar esta instalação anterior, tools/preparar_relacional.php cria um banco novo e um backup protegido do código/configuração anteriores; não altera o banco antigo. Esses procedimentos são preparação anterior à aula, não atividade ao vivo.

## Materiais e qualidade

Prompt: aula/PROMPT_PARA_ANTIGRAVITY.txt (ou aula/PROMPT_PARA_CODEX.txt). Assistente de IA: Google Antigravity (sem custos, via conta Gmail). Casos: aula/TESTES.md. Controles inspirados no BAZZO: aula/QUALIDADE_E_VERSIONAMENTO.md. Antes e depois de editar: php tools/verificar_utf8.php e php -l nos PHP alterados. Depois, revisar o diff e testar no navegador. O modo escuro tem alternância e preferência local.

O agente deve solicitar Comitar após apresentar a mudança validada e pedir Push separadamente após informar o SHA. Uma regra em AGENTS.md orienta o agente, mas não impede comandos manuais do Git.

## GitHub

Repositório público: https://github.com/rolfrostock/senac178-oficina-demo. O commit 90e0cd8 registra a base anterior de uma tabela. O novo caso de duas tabelas só estará disponível online depois de aprovar seu commit e seu push. A cópia local do kit não comprova publicação remota. Não existe tag automática de versão nem publicação da aplicação pelo GitHub.

## Contingência e limites

O aplicativo depende de Apache/PHP e MySQL, mesmo sem internet. Ele não funciona ao abrir o PHP diretamente como arquivo. Sem IA, usar o trecho de resposta preparado no kit e informar que é um exemplo local. Sem WAMP, usar capturas e ficha impressa, deixando claro que a execução ao vivo está indisponível. Nenhuma chamada à IA ocorre dentro do aplicativo.

Todo dado é fictício. Não atribuir automaticamente os pedidos antigos a clientes para fazer os testes passarem. Consulte as instruções completas do kit e preserve a base com problema antes do ensaio.
