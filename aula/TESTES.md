# Testes da aula

Os exemplos abaixo usam apenas dados fictícios. Antes da correção, os casos vazios são aceitos. Esse é o comportamento preparado que os alunos devem identificar.

| Caso | Operação | Entrada de assunto | Resultado correto esperado após o ajuste |
|---|---|---|---|
| T1 | Cadastrar | Trocar teclado | Novo registro aparece na lista e permanece após recarregar. |
| T2 | Cadastrar | Campo vazio | Bloqueio, mensagem, foco no assunto, demais campos preservados e nenhuma inserção. |
| T3 | Cadastrar | Três espaços | Mesmo bloqueio do T2. |
| T4 | Editar registro válido | Apagar o assunto | Bloqueio sem substituir o assunto que estava no banco. |
| T5 | Editar registro válido | Três espaços | Mesmo bloqueio do T4. |
| T6 | Editar registro válido | Limpeza do notebook | Alteração válida salva e persistente. |

No tempo da aula, priorize T1, T2 e T3. T4 é uma checagem adicional importante para a preparação e a revisão do Codex. Leia o contador antes de enviar e confirme que ele não muda nos casos rejeitados. Retire os filtros da listagem antes de comparar o total.

Registros antigos sem assunto continuam existindo após implementar a validação. A correção impede novas gravações inválidas; não recupera informações que nunca foram registradas.

Para ensaiar novamente a falha depois da correção, restaure SOMENTE os arquivos de código alterados a partir do ZIP original. Não importe o SQL nem apague o banco para restaurar o código. Preserve config.local.php, que pertence à instalação desta máquina.
