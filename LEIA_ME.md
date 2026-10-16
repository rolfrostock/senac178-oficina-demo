# Oficina Byte para a aula SENAC 178/2026

Aplicação local para simular uma assistência de computadores com um CRUD já existente. Todos os clientes e atendimentos são fictícios.

## Abrir nesta máquina

- Endereço: http://localhost/aula-senac178/
- Projeto instalado: `C:\wamp64\www\aula-senac178`
- Banco exclusivo: `senac178_oficina_demo` no MySQL da porta 3306.
- Não há instalação de pacotes, conexão com IA ou chave de API na aplicação.

O WAMP deve manter Apache e MySQL ativos. Os registros persistem depois de atualizar a página e reiniciar a aplicação.

## Aparência e qualidade

O sistema abre em modo escuro por padrão. O botão no cabeçalho alterna entre claro e escuro e guarda a preferência no navegador, quando o armazenamento local está disponível. A mudança de aparência não altera os registros.

As orientações de UTF-8 e versionamento estão em `aula/QUALIDADE_E_VERSIONAMENTO.md`, com referência aos controles inspecionados no BAZZO e uma fala curta para a apresentação. Execute `php tools/verificar_utf8.php` antes e depois de editar. O comando verifica arquivos e não corrige a falha didática do cadastro.

## O que já está funcionando

Cadastro, listagem, busca, filtro por status, edição e exclusão de solicitações. Campos: cliente, equipamento, assunto e status. O MySQL gera o identificador e as datas. Há três registros iniciais, um deles sem assunto para tornar a dificuldade visível imediatamente.

## Problema para demonstrar

O sistema permite salvar uma solicitação com assunto vazio ou contendo somente espaços. A recepção recebe confirmação de sucesso e o registro fica salvo no banco. O técnico vê “Sem assunto” e não consegue saber qual serviço foi solicitado. A equipe precisa recuperar a informação, gerando retrabalho e atraso.

A falha foi preservada intencionalmente em `salvar.php`, tanto no cadastro quanto na edição. O formulário também permite essas entradas. O campo de banco é `NOT NULL`, mas isso não impede strings vazias ou espaços. Não corrija antes da demonstração.

## Reprodução em menos de um minuto

1. Abra o endereço local e observe o registro inicial sem assunto.
2. Cadastre cliente “Teste da aula”, equipamento “Notebook de exemplo” e deixe o assunto vazio.
3. O sistema confirma o cadastro e mostra uma nova solicitação “Sem assunto”.
4. Atualize a página: o registro continua na lista porque foi salvo no banco.
5. Repita com três espaços no assunto. O sistema também aceita.
6. Use Editar para consultar ou corrigir um registro. Use Excluir apenas para os registros fictícios que desejar remover.

## Usar com Codex durante a aula

Abra a pasta instalada como projeto local no Codex antes da apresentação e confira as permissões de edição. Veja `aula/PROMPT_PARA_CODEX.txt`. A solicitação pede uma alteração pequena no servidor, preservando o CRUD. Faça a definição de requisitos com a banca antes de enviar.

Depois da correção, execute os três casos de `aula/TESTES.md`, tanto no cadastro quanto na edição. Uma validação de novos pedidos não corrige automaticamente os registros antigos. O pedido inicial sem assunto pode ser corrigido pela edição, como exemplo de tratamento dos dados já existentes.

## Arquivos principais

- `index.php`: listagem, filtros e formulário de cadastro/edição.
- `salvar.php`: validações e INSERT/UPDATE. Aqui está a falha da aula.
- `excluir.php`: exclusão por POST.
- `inc/app.php`: conexão PDO, sessão, CSRF e funções comuns.
- `assets/`: estilos e confirmação de exclusão.
- `config.local.php`: configuração da instalação, com credencial local. Não compartilhar.
- `tools/schema.sql`: estrutura e três registros iniciais. Não executar em uma tabela existente.
- `tools/instalar_local.php`: instalação inicial pelo terminal; recusa pastas e bancos existentes.
- `aula/`: contexto, prompt e testes para o docente, bloqueados para acesso HTTP.

## Escopo da preparação

Esta versão substitui o microaplicativo anterior em memória para a simulação. O plano e os slides anteriores ainda descrevem aquele recorte e deverão ser alinhados antes de sua entrega final. O sistema possui uma falha de regra de negócio preparada, não uma integração de IA: o Codex será usado para apoiar a correção dos arquivos.

## Instalar em outro computador

O instalador foi preparado para WAMP em `C:\wamp64`, MySQL na porta 3306 e administração local padrão sem senha. Não muda a senha administrativa. Ele cria um usuário próprio com senha aleatória e acesso apenas ao banco da demonstração. Não use esse instalador em uma instalação diferente sem revisar os parâmetros de conexão. O projeto só aceita acesso do próprio computador e não é publicado na rede do laboratório.

Nenhum comando de restauração ou limpeza automática é executado ao abrir o aplicativo.

## Aprovações para versionar e enviar

Em novas alterações, o agente deve implementar, testar e apresentar uma proposta antes de solicitar `Comitar`. Após criar o commit aprovado, deve informar o SHA, branch e destino e solicitar separadamente `Push`. Aprovação de commit não autoriza envio ao GitHub. Veja as regras completas em `AGENTS.md`.
