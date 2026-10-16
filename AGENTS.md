# Projeto didático SENAC 178/2026

Aplicativo local em PHP 8 com PDO e MySQL. Não há frameworks nem dependências de rede.

- O CRUD de solicitações já está implementado em `index.php`, `salvar.php` e `excluir.php`.
- Os registros são persistentes no banco exclusivo da demonstração.
- Há uma falha didática intencional: `salvar.php` permite assunto vazio e composto apenas por espaços, no cadastro e na edição.
- Não corrija essa falha em alterações de preparação ou aparência. Corrija quando o usuário solicitar a validação do assunto durante a aula.
- Ao corrigir, valide no servidor antes de INSERT/UPDATE e utilize o fluxo de erros existente. Esse fluxo preserva os demais dados e retorna o foco para o assunto.
- Preserve prepared statements, escape de HTML, CSRF e restrição ao computador local.
- Não altere outros projetos, usuários ou bancos. Não exiba credenciais de `config.local.php`.
- O banco não exige texto significativo: `NOT NULL` não rejeita strings vazias ou só com espaços.
- Nunca limpe dados nem execute a restauração sem solicitação explícita do usuário.

## Qualidade em cada alteração

- Preserve UTF-8 sem BOM, acentuação em português e a codificação existente. Faça o menor diff necessário; não regrave arquivos inteiros para mudar uma regra.
- Use `.editorconfig`. No Windows PowerShell 5.x, não grave código com `Set-Content -Encoding UTF8`, que pode introduzir BOM. Prefira patches ou escrita explícita em UTF-8 sem BOM.
- Execute `php tools/verificar_utf8.php` antes e depois de editar. Se falhar, corrija a codificação e execute novamente antes de declarar a alteração pronta.
- Execute `php -l` nos arquivos PHP alterados. Confira no navegador palavras como solicitação, diagnóstico e concluído.
- O verificador é uma ferramenta local. Estas instruções não equivalem a um bloqueio automático do editor: o agente precisa executar os checks e informar o resultado real.
- Não remova acentos para fazer um check passar. Não use conversão global indiscriminada de encoding.
- Confira o diff e teste o fluxo afetado a cada nova versão. UTF-8 válido, lint e número de versão não comprovam sozinhos o funcionamento do sistema.
- Veja `aula/QUALIDADE_E_VERSIONAMENTO.md` para a origem dos controles no BAZZO e a fala de apoio da aula.

## Aprovação humana para commit e push

1. Antes de editar, confirme a pasta do projeto, o repositório, o remoto e a branch. Apresente um plano curto e mantenha a alteração no escopo solicitado.
2. Implemente e valide antes de pedir aprovação: UTF-8 sem BOM, lint dos PHP alterados, revisão do diff e teste do fluxo afetado no navegador. Em mudanças apenas documentais, valide conteúdo, encoding e diff. Informe qualquer limitação real.
3. Ao terminar, apresente objetivo, arquivos alterados, resumo do diff, verificações e resultados, riscos ou pendências, branch e mensagem proposta para o commit. Solicite aprovação humana e aguarde. Não execute commit automaticamente.
4. Com uma única proposta ativa, o comando humano isolado `Comitar` autoriza somente aquele commit, com os arquivos e a mensagem apresentados. Menções, exemplos e perguntas não autorizam a ação. Se houver várias propostas, peça identificação do projeto. Alterações posteriores invalidam a aprovação e exigem nova apresentação e confirmação.
5. Depois do commit, apresente o SHA, branch, remoto e destino proposto do push. Solicite aprovação separada e aguarde o comando humano `Push`, quando houver um único envio pendente. Autorizar commit não autoriza push.
6. Antes de um push autorizado, execute fetch, confirme a identidade do remoto e da branch e bloqueie divergências ou commits adicionais fora do escopo aprovado. Nunca use force-push para contornar divergência. Depois, confirme que o SHA remoto corresponde ao SHA publicado.
7. Commit e push não autorizam deploy, publicação de release, criação de tags, exclusões ou mudanças de visibilidade do repositório.
8. Se o humano já tiver solicitado expressamente uma operação concreta de commit ou envio, respeite essa autorização no escopo indicado, sem pedir a mesma autorização novamente. A publicação inicial desta demonstração foi solicitada explicitamente pelo humano; isso não constitui autorização permanente para novos commits ou pushes.
9. Nunca inclua `config.local.php`, credenciais, cookies, logs ou dumps de dados. Confira o conteúdo preparado para commit, além de `.gitignore`.

Essas regras orientam a execução do agente. Elas não são um bloqueio de permissões do Git nem uma aprovação técnica concedida pelo verificador de UTF-8.
