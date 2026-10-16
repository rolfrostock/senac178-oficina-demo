# Oficina Byte

Sistema local em PHP e MySQL para uma aula experimental de desenvolvimento de sistemas com apoio de IA. A empresa, os clientes e as solicitações são fictícios.

## Versão inicial com problema

Esta versão é a base da demonstração: o CRUD está pronto, mas aceita solicitações cujo assunto está vazio ou contém somente espaços. A falha é intencional e também existe na edição. A atividade consiste em identificar a dificuldade que isso causa à assistência e solicitar ao Codex uma correção pequena, revisável e testável.

Não corrigir antecipadamente a falha em tarefas de aparência ou preparação. Não usar dados reais de clientes.

## Funcionalidades

- Cadastro, consulta, edição e exclusão de solicitações.
- Busca e filtro por status, com persistência no MySQL.
- Modo escuro e claro com preferência do navegador.
- Conexão PDO, consultas preparadas, escape de HTML e CSRF.
- Orientações de trabalho em `AGENTS.md` e verificador local de UTF-8.

## Preparação local

Leia [as instruções de instalação e uso](LEIA_ME.md). O instalador inicial é específico para WAMP em `C:\wamp64`, MySQL na porta 3306 e administração local padrão sem senha. Ele recusa o destino quando a pasta, o banco ou o usuário já existem e cria uma credencial exclusiva para o banco da demonstração.

A aplicação funciona no próprio computador, em `http://localhost/aula-senac178/`. A configuração gerada `config.local.php` não faz parte do repositório. O SQL contém apenas a estrutura e três exemplos fictícios, um deles sem assunto.

## Condução da aula

- [Prompt técnico para o Codex](aula/PROMPT_PARA_CODEX.txt).
- [Casos de teste](aula/TESTES.md).
- [Qualidade, UTF-8 e versionamento](aula/QUALIDADE_E_VERSIONAMENTO.md).
- [Regras do agente e aprovações](AGENTS.md).

Execute `php tools/verificar_utf8.php` antes e depois de alterar o código. Use também `php -l` nos PHP modificados e teste o comportamento no navegador. Esses checks não substituem a revisão humana.

Para futuras alterações, o agente deve apresentar o diff e as verificações antes de pedir `Comitar`. Após o commit, deve apresentar o SHA e solicitar separadamente `Push`. Cada autorização vale somente para a proposta apresentada.
