# Tarefas e pendências do Balance

Este arquivo organiza o trabalho técnico à luz do PRD e do código atual. Os itens marcados como concluídos significam que há implementação observável nos arquivos; não significam que foi executada validação funcional ou que o banco ativo já recebeu os scripts.

## Concluído no código/documentado

- [x] Diagnóstico de estrutura, tecnologias, funcionalidades, identidade visual e lacunas frente ao PRD (`DIAGNOSTICO.md`).
- [x] Padronização visual incremental com tokens CSS, estados de botões, tema escuro e favicon.
- [x] Cadastro de bancos associado ao usuário, com tipo, cor, descrição e data de criação.
- [x] Cadastro de categorias Entrada/Saída com cores semânticas padronizadas.
- [x] Categoria Agendado com cor âmbar, seleção em novos lançamentos e exibição no dashboard.
- [x] Página de lembretes sobre a tabela `links`, com data prevista, contatos/URL opcionais e conclusão reversível.
- [x] Migração documentada para associar lembretes novos ao usuário e preservar registros legados sem atribuição automática.
- [x] Campos de data do lançamento, banco e categoria no formulário de inclusão.
- [x] Campos equivalentes no formulário de edição e exibição completa dos dados na visualização.
- [x] Filtros combináveis na listagem por tipo de lançamento e tipo de banco.
- [x] Dashboard com cinco lançamentos gerais recentes, até seis lançamentos recentes por categoria e saldo líquido por banco/consolidado.
- [x] Scripts SQL versionados para bancos, categorias, novos campos e dump de lançamentos.
- [x] Inclusão de `usuario_id` e isolamento do CRUD, busca, listagem e consultas do dashboard pelo usuário autenticado.

## Pendências recomendadas — prioridade crítica

- [ ] Aplicar `database/scripts/lancamentos_usuario.sql` uma vez em cada base existente e validar a FK/índice; associar dados antigos apenas depois de confirmar sua titularidade.
- [x] Revisar as rotas atuais de leitura, edição e exclusão para verificar sessão e propriedade do lançamento.
- [ ] Revisar autenticação e substituir hashes MD5 antigos por `password_hash()`/`password_verify()` com migração compatível, se ainda aplicável ao banco real.
- [ ] Aplicar tokens CSRF e validação no servidor em todos os formulários e endpoints mutáveis.
- [ ] Aplicar `database/scripts/categorias_agendado.sql` somente em bases existentes cuja tabela `categorias` ainda tenha o ENUM antigo.
- [ ] Escapar saída HTML no ponto de renderização e remover confiança em HTML escapado antes de persistir.
- [ ] Fazer backup restaurável e reconciliar schema real com os scripts antes de aplicar qualquer migração.

## Pendências recomendadas — integridade financeira

- [ ] Avaliar migração de `FLOAT` para `DECIMAL`, com tratamento de sinal e reconciliação dos cálculos.
- [ ] Normalizar datas antigas, preservando valor original e definindo convenção de fuso horário.
- [ ] Definir claramente se `Agendado` compõe saldo, gráfico, relatórios ou apenas previsões.
- [ ] Revisar indicadores percentuais e período dos cards; atualmente os resumos principais não têm todos os filtros de período previstos no PRD.
- [ ] Adicionar cobertura de cenários sem banco/categoria e de lançamentos legados com campos novos nulos.

## Pendências funcionais do PRD

- [ ] Filtros por categoria e período, ordenação segura e paginação na listagem.
- [ ] Relatórios mensais/anuais e exportação CSV com filtros aplicados.
- [ ] Filtros de período nos indicadores e gráficos do dashboard.
- [ ] Avaliar transações recorrentes, orçamento por categoria e metas financeiras após estabilizar segurança e modelo de dados.
- [ ] Completar fluxos de backup/exportação e documentação de instalação/restauração.

## Pendências de qualidade e documentação

- [x] Atualizar `README.md`: banco usado, configuração segura do `.env`, instalação PHP/MariaDB e ordem das migrações.
- [ ] Consolidar a grafia/localização dos changelogs e manter um único local oficial para alterações.
- [ ] Criar testes automatizados para autenticação, autorização, CRUD, filtros e cálculos financeiros.
- [ ] Verificar responsividade, acessibilidade, logs PHP e console do navegador em ambiente de desenvolvimento.
- [ ] Atualizar `PROJECT_STATUS.md` e este backlog quando o estado do código mudar.

## Regra de execução

Não executar scripts SQL em um banco com dados reais sem confirmar o ambiente, produzir backup restaurável e revisar o plano de migração. Priorizar mudanças pequenas, reversíveis e cobertas por validação; preservar registros existentes e documentar limitações.
