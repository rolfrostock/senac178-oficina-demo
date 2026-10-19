# Qualidade e codificação durante a evolução do sistema

## Preparação do orquestrador

Uma alteração pode atender à regra de negócio e ainda introduzir problemas de codificação ao regravar arquivos. Verifique UTF-8 sem BOM, sintaxe, textos e diferenças entre versões antes e depois das mudanças. Essas verificações ajudam a distinguir problemas de formatação de falhas da lógica, sem garantir ausência de erros.

Esses detalhes são preparação técnica do docente e instruções do agente. Não fazem parte da fala aos alunos na aula de vinte minutos. O foco da aula é entender a dificuldade do atendimento, definir a regra, revisar a proposta e verificar seu comportamento. Consulte o roteiro revisado em 03_Guia_docente.

## O que foi encontrado no BAZZO

Inspeção de referência realizada em 16/10/2026, somente leitura, no checkout local BAZZO/worktree-tema:

| Camada | Evidência no código ou documento | Papel e limite |
|---|---|---|
| Instruções do agente | AGENTS.md, seção Qualidade e encoding; docs/agentes-especialistas/bloco-obrigatorio-prompts.md, itens 1–7 | Exige UTF-8 sem BOM, acentuação preservada e verificação depois da edição. São instruções; não impedem por si só uma gravação incorreta. |
| Validação executável | tools/bazzo-wamp.ps1, função Invoke-Validation, linhas 343–420 | Lint PHP, decodificação UTF-8 estrita, detecção de BOM e heurística de mojibake. Encoding inválido e BOM bloqueiam; mojibake em arquivos alterados bloqueia. Padrões em arquivos não alterados geram aviso de baseline. |
| Escrita de artefatos | tools/bazzo-wamp.ps1, uso de UTF8Encoding(false) | Grava relatórios e manifestos explicitamente sem BOM. |
| Integridade operacional | .github/workflows/orchestrator-guard.yml | Verifica arquivos obrigatórios, checksums, identidade do projeto e outros contratos. Não deve ser apresentado como prova de que toda string de interface está correta. |

Não foi executado o pipeline do BAZZO nem feita alteração naquele projeto. As evidências acima correspondem à inspeção estática dos arquivos. Não foi copiado o orquestrador completo, que contém regras específicas de WordPress, Flutter, pesquisa e publicação incompatíveis com o tempo e o escopo desta aula.

## Controles adotados nesta demonstração

- `.editorconfig`: orienta editores compatíveis a salvar UTF-8 e finais de linha LF.
- `AGENTS.md`: exige alterações pequenas, UTF-8 sem BOM, lint e verificação dos textos.
- `tools/verificar_utf8.php`: checa bytes UTF-8 válidos, BOM e padrões conhecidos de corrupção. Retorna código de falha para uso em uma rotina de validação.
- A aplicação já declara UTF-8 no HTML e no cabeçalho HTTP, usa UTF-8 ao escapar HTML e conecta ao MySQL com utf8mb4. A tabela também foi criada com utf8mb4.
- A versão anterior do código permanece no ZIP original. A alteração de aparência não corrige a falha didática de validação.

Execute, dentro da pasta do projeto: `php tools/verificar_utf8.php`. Faça também `php -l` nos PHP alterados e confira a tela. O verificador não é executado automaticamente pelo navegador e não detecta toda alteração semântica ou perda de acentuação possível.

## Relação com o tema da aula

A IA apoia a implementação. O processo de desenvolvimento precisa estabelecer limites, preservar contratos e verificar a solução produzida. O controle de encoding é um exemplo concreto de qualidade e regressão durante o versionamento, não o problema de negócio que a atividade procura resolver.
