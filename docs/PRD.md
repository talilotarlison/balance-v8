# PRD — Modernização e Evolução do Balance

**Projeto:** Balance — Gerenciador Financeiro Pessoal   
**Documento:** PRD.md  
**Versão:** 1.0  
**Objetivo:** Modernização visual, melhoria de usabilidade e evolução funcional.

---

# 1. Visão geral do projeto

O Balance é um sistema de gerenciamento financeiro pessoal desenvolvido em PHP, com funcionalidades para controle de receitas, despesas e acompanhamento do saldo financeiro.

O objetivo desta evolução é transformar o sistema em uma aplicação financeira mais moderna, intuitiva, organizada e agradável de utilizar, sem perder a identidade visual já existente.

A modernização deverá preservar a arquitetura, as bibliotecas e as tecnologias atualmente utilizadas, priorizando melhorias incrementais em vez de reconstruções desnecessárias.

## 1.1 Objetivos principais

- Modernizar a interface visual.
- Padronizar cores, tipografia, ícones e componentes.
- Melhorar a experiência de navegação.
- Tornar o dashboard mais informativo e visualmente atraente.
- Aprimorar o cadastro e gerenciamento de transações.
- Melhorar filtros, pesquisas e visualização de informações.
- Implementar melhorias funcionais que agreguem valor ao controle financeiro.
- Garantir boa experiência em dispositivos móveis.
- Preservar os dados e as funcionalidades existentes.

# 2. Diretrizes obrigatórias para o desenvolvimento

Estas regras devem ser respeitadas durante toda a implementação.

## 2.1 Preservação tecnológica

- Manter PHP puro, sem introduzir frameworks PHP.
- Manter o banco de dados atualmente utilizado.
- Preservar as bibliotecas JavaScript e CSS existentes.
- Não substituir bibliotecas funcionais por alternativas apenas por preferência pessoal.
- Não introduzir dependências desnecessárias.
- Reaproveitar funções, componentes e estruturas existentes.
- Preservar os mecanismos de autenticação e gerenciamento de usuários.
- Não modificar a estrutura do banco sem necessidade justificada.

## 2.2 Preservação visual

A modernização deverá partir da identidade visual atual.

Não criar uma interface completamente diferente sem necessidade.

O trabalho deverá priorizar:
- Refinamento das cores existentes.
- Melhor contraste entre elementos.
- Melhor distribuição dos espaços.
- Padronização de bordas e arredondamentos.
- Melhoria da hierarquia visual.
- Ícones consistentes.
- Melhor apresentação dos cards.
- Componentes visuais mais profissionais.

## 2.3 Regra de segurança dos dados

Antes de qualquer modificação:
- Identificar o banco utilizado.
- Identificar as tabelas e relacionamentos.
- Identificar como as transações são armazenadas.
- Identificar as operações de inclusão, edição e exclusão.
- Criar estratégia de backup.
- Preservar os registros existentes.

Nenhuma funcionalidade nova poderá comprometer dados financeiros cadastrados.

# 3. Modernização da interface visual (UI)

## 3.1 Design System

Criar um padrão visual centralizado para toda a aplicação.

Utilizar variáveis CSS para definir as propriedades visuais.

### Paleta de cores

A paleta deverá respeitar a identidade atual do projeto, com cores padronizadas para cada contexto.

Sugestão de referências semânticas:

| Elemento | Aplicação |
|---|---|
| Primary | Botões principais e ações |
| Secondary | Elementos complementares |
| Success | Receitas e resultados positivos |
| Danger | Despesas, erros e exclusões |
| Warning | Alertas e pendências |
| Background | Fundo das páginas |
| Surface | Cards, modais e formulários |
| Text Primary | Textos principais |
| Text Secondary | Informações auxiliares |
| Border | Bordas e divisores |

Não utilizar cores diferentes aleatoriamente para elementos com a mesma finalidade.

### Variáveis CSS

Centralizar as propriedades em um arquivo global de estilos.

Exemplo conceitual:

```css
:root {
    --color-primary: /* preservar identidade atual */;
    --color-success: #16A34A;
    --color-danger: #DC2626;
    --color-warning: #D97706;

    --color-background: /* conforme tema atual */;
    --color-surface: /* conforme tema atual */;

    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;

    --spacing-sm: 8px;
    --spacing-md: 16px;
    --spacing-lg: 24px;
}
```

Os valores definitivos deverão ser definidos após analisar o CSS atual.

## 3.2 Tipografia

- Padronizar tamanhos de fontes.
- Criar hierarquia entre títulos, subtítulos e textos.
- Melhorar a legibilidade dos valores financeiros.
- Utilizar pesos tipográficos consistentes.
- Evitar excesso de fontes diferentes.

## 3.3 Ícones

Padronizar todos os ícones da aplicação.

Requisitos:
- Utilizar preferencialmente a biblioteca de ícones já instalada.
- Caso não exista uma biblioteca adequada, avaliar uma alternativa leve.
- Manter o mesmo padrão de tamanho e alinhamento.
- Utilizar ícones intuitivos para cada funcionalidade.
- Evitar misturar emojis, SVGs aleatórios e diferentes bibliotecas.

Exemplos de aplicações:

| Funcionalidade | Ícone |
|---|---|
| Dashboard | Painel |
| Receitas | Seta para cima |
| Despesas | Seta para baixo |
| Carteiras | Carteira |
| Categorias | Etiqueta |
| Relatórios | Gráfico |
| Configurações | Engrenagem |
| Pesquisa | Lupa |
| Adicionar | Mais |
| Editar | Lápis |
| Excluir | Lixeira |

## 3.4 Componentes padronizados

Criar ou reorganizar os componentes reutilizáveis:

- Botões primários e secundários.
- Botões de ação.
- Cards informativos.
- Campos de formulário.
- Selects.
- Modais.
- Tabelas.
- Badges de status.
- Alertas.
- Tooltips.
- Menus.
- Paginação.
- Estados vazios.
- Indicadores de carregamento.

Todos os componentes devem seguir o mesmo padrão visual.

# 4. Dashboard financeiro

O dashboard deverá oferecer uma visão rápida da situação financeira do usuário.

## 4.1 Cards financeiros

Organizar os indicadores principais em cards:

1. Saldo atual.
2. Total de receitas.
3. Total de despesas.
4. Resultado financeiro do período.

Cada card deverá apresentar:
- Ícone correspondente.
- Título.
- Valor formatado em reais.
- Indicador visual.
- Informação complementar quando aplicável.

Os valores devem ser calculados com base nos registros reais do banco de dados.

## 4.2 Gráficos

Melhorar a apresentação dos gráficos existentes, preservando a biblioteca atual.

Avaliar a implementação de:

- Receitas versus despesas.
- Evolução do saldo ao longo dos meses.
- Distribuição de despesas por categoria.
- Comparativo mensal.

Requisitos:
- Legendas claras.
- Cores padronizadas.
- Tooltips informativos.
- Responsividade.
- Filtros por período.
- Tratamento para ausência de dados.

Não adicionar gráficos redundantes.

## 4.3 Transações recentes

Apresentar uma lista das movimentações mais recentes.

Exibir:
- Descrição.
- Categoria.
- Data.
- Tipo da movimentação.
- Valor.
- Ações disponíveis.

Diferenciar visualmente receitas e despesas.

Adicionar link para visualizar todas as transações.

## 4.4 Filtros do dashboard

Permitir visualizar informações por:

- Hoje.
- Esta semana.
- Este mês.
- Mês anterior.
- Este ano.
- Período personalizado.

Os cards e gráficos devem respeitar o período selecionado.

# 5. Gerenciamento de transações

A área de transações deverá oferecer uma experiência mais organizada.

## 5.1 Cadastro

Melhorar o formulário de inclusão de receitas e despesas.

Campos:
- Descrição.
- Valor.
- Tipo: receita ou despesa.
- Categoria.
- Data.
- Observações.

Requisitos:
- Validação dos campos obrigatórios.
- Formatação monetária brasileira.
- Mensagens de erro claras.
- Feedback visual após salvar.
- Prevenção contra envio duplicado.
- Boa experiência em dispositivos móveis.

## 5.2 Edição

Permitir editar transações existentes.

Requisitos:
- Carregar os dados atuais.
- Permitir alterações.
- Validar informações.
- Atualizar os indicadores financeiros.
- Preservar a integridade dos registros.

## 5.3 Exclusão

Melhorar a experiência de exclusão.

- Solicitar confirmação antes de excluir.
- Identificar claramente a transação.
- Apresentar feedback após a operação.
- Atualizar os valores financeiros.

## 5.4 Pesquisa e filtros

Implementar ou aprimorar:

- Pesquisa por descrição.
- Filtro por tipo.
- Filtro por categoria.
- Filtro por período.
- Ordenação por data.
- Ordenação por valor.

Os filtros deverão funcionar em conjunto.

## 5.5 Listagem

Melhorar a apresentação da tabela de transações.

- Cabeçalhos organizados.
- Valores alinhados.
- Datas padronizadas.
- Categorias identificadas visualmente.
- Botões de ação padronizados.
- Paginação quando necessária.
- Mensagem adequada quando não existirem registros.

# 6. Categorias financeiras

Avaliar e aprimorar o gerenciamento de categorias.

Categorias sugeridas:

**Receitas**
- Salário.
- Freelance.
- Investimentos.
- Vendas.
- Outros.

**Despesas**
- Alimentação.
- Moradia.
- Transporte.
- Saúde.
- Educação.
- Lazer.
- Assinaturas.
- Compras.
- Contas.
- Outros.

Funcionalidades desejadas:
- Listar categorias.
- Criar categorias personalizadas.
- Editar categorias.
- Identificar categorias por cores ou ícones.
- Filtrar transações por categoria.

Não remover categorias que já estejam associadas a transações.

# 7. Relatórios financeiros

Criar uma área de relatórios que permita compreender melhor o comportamento financeiro.

Funcionalidades desejadas:

- Relatório mensal.
- Relatório anual.
- Comparação entre receitas e despesas.
- Despesas por categoria.
- Histórico de movimentações.
- Evolução do saldo.

Permitir exportação dos relatórios, preferencialmente em CSV, respeitando os filtros selecionados.

Caso já exista biblioteca de exportação, preservá-la.

# 8. Melhorias funcionais adicionais

Estas funcionalidades representam uma evolução do sistema e deverão ser implementadas somente depois de estabilizar as funcionalidades principais.

## 8.1 Transações recorrentes

Permitir cadastrar movimentações recorrentes.

Exemplos:
- Salário mensal.
- Aluguel.
- Internet.
- Energia.
- Assinaturas.

Possibilidades:
- Recorrência semanal.
- Recorrência mensal.
- Recorrência anual.
- Data inicial.
- Data final opcional.

Evitar gerar lançamentos duplicados.

## 8.2 Orçamento por categoria

Permitir definir limites de gastos.

Exemplo:

- Alimentação: R$ 600.
- Transporte: R$ 300.
- Lazer: R$ 200.

Exibir:
- Limite estabelecido.
- Valor utilizado.
- Valor restante.
- Percentual consumido.

Criar alertas visuais quando o limite estiver próximo de ser atingido ou ultrapassado.

## 8.3 Metas financeiras

Permitir cadastrar objetivos financeiros.

Exemplos:
- Reserva de emergência.
- Comprar computador.
- Comprar veículo.
- Viagem.

Informações:
- Nome da meta.
- Valor desejado.
- Valor acumulado.
- Prazo.
- Progresso percentual.

Apresentar uma barra de progresso visual.

## 8.4 Exportação e backup

Avaliar:
- Exportação de transações em CSV.
- Backup do banco de dados.
- Restauração segura.
- Confirmação antes de operações críticas.

A restauração deverá validar o arquivo antes de substituir qualquer dado existente.

# 9. Responsividade

A aplicação deverá funcionar adequadamente em:

- Computadores.
- Notebooks.
- Tablets.
- Smartphones.

Melhorias necessárias:
- Menu adaptável.
- Cards reorganizados.
- Formulários responsivos.
- Tabelas adaptáveis.
- Modais ajustados à tela.
- Botões com áreas de toque adequadas.
- Gráficos responsivos.

No celular, priorizar a apresentação das informações essenciais e facilitar o cadastro de movimentações.

# 10. Experiência do usuário (UX)

Implementar melhorias de interação:

- Feedback visual nas operações.
- Mensagens de sucesso e erro.
- Estados vazios informativos.
- Confirmação para ações destrutivas.
- Indicadores de carregamento.
- Navegação consistente.
- Campos com rótulos claros.
- Destaque para ações principais.
- Redução de cliques desnecessários.

Evitar animações exageradas e efeitos que prejudiquem o desempenho.

# 11. Tema e personalização

Avaliar a possibilidade de oferecer:

- Tema claro.
- Tema escuro.
- Persistência da preferência do usuário.

A implementação deverá reutilizar os tokens do Design System.

Não duplicar folhas de estilo nem criar regras conflitantes para cada tema.

# 12. Segurança e qualidade técnica

Realizar uma revisão das operações existentes.

Requisitos:

- Utilizar consultas preparadas.
- Validar dados no backend.
- Escapar dados apresentados no HTML.
- Proteger formulários contra CSRF.
- Utilizar hash seguro de senhas.
- Validar sessões e permissões.
- Evitar acesso indevido a dados de outros usuários.
- Validar operações de edição e exclusão.
- Evitar exposição de informações sensíveis.
- Manter mensagens de erro sem revelar detalhes internos.

Preservar a compatibilidade com a versão de PHP identificada no projeto.

# 13. Organização do código

Antes de criar novos arquivos, analisar a estrutura existente.

Priorizar:
- Reutilização de componentes.
- Separação entre apresentação e lógica.
- Centralização de configurações.
- Padronização de funções.
- Organização dos arquivos CSS e JavaScript.
- Evitar duplicação de código.

Não realizar uma refatoração estrutural ampla apenas para atender a preferências de organização.

# 14. Plano de implementação

A implementação deverá ocorrer em fases.

## Fase 1 — Diagnóstico

- Analisar todos os arquivos.
- Identificar tecnologias e bibliotecas.
- Identificar banco de dados e estrutura das tabelas.
- Mapear funcionalidades existentes.
- Identificar problemas visuais.
- Verificar problemas funcionais.
- Registrar riscos e dependências.

**Entrega:** diagnóstico técnico e plano de alterações.

## Fase 2 — Design System

- Padronizar cores.
- Padronizar tipografia.
- Organizar ícones.
- Criar componentes reutilizáveis.
- Ajustar espaçamentos.
- Melhorar botões e formulários.

**Entrega:** identidade visual modernizada.

## Fase 3 — Dashboard

- Melhorar cards.
- Aprimorar gráficos.
- Melhorar transações recentes.
- Implementar filtros por período.
- Ajustar responsividade.

**Entrega:** dashboard modernizado.

## Fase 4 — Transações

- Melhorar cadastro.
- Melhorar edição.
- Melhorar exclusão.
- Aprimorar pesquisa.
- Implementar filtros combinados.
- Melhorar listagem.

**Entrega:** gerenciamento de transações aprimorado.

## Fase 5 — Funcionalidades adicionais

- Categorias.
- Relatórios.
- Exportação.
- Orçamentos.
- Transações recorrentes.
- Metas financeiras.

**Entrega:** evolução funcional.

## Fase 6 — Testes e validação

- Testar todas as funcionalidades.
- Testar responsividade.
- Verificar cálculos financeiros.
- Verificar integridade do banco.
- Testar autenticação.
- Corrigir erros visuais.
- Verificar console do navegador.
- Verificar logs do PHP.

**Entrega:** versão estável e documentada.

# 15. Critérios de aceitação

O projeto será considerado atualizado quando:

- [ ] A identidade visual estiver padronizada.
- [ ] Os ícones seguirem um padrão consistente.
- [ ] Os componentes apresentarem aparência uniforme.
- [ ] O dashboard estiver visualmente aprimorado.
- [ ] Os gráficos apresentarem informações corretas.
- [ ] O cadastro de receitas e despesas funcionar corretamente.
- [ ] A edição e exclusão funcionarem corretamente.
- [ ] Os filtros apresentarem resultados corretos.
- [ ] Os valores financeiros estiverem corretos.
- [ ] A interface funcionar em dispositivos móveis.
- [ ] As bibliotecas existentes tiverem sido preservadas.
- [ ] Os dados antigos permanecerem íntegros.
- [ ] Não existirem erros críticos de execução.
- [ ] As funcionalidades novas estiverem testadas.
- [ ] A documentação estiver atualizada.

# 16. Instruções especiais para o Codex

Você deverá atuar como desenvolvedor sênior especializado em PHP, JavaScript, CSS, UX/UI e sistemas financeiros.

**IMPORTANTE: não comece modificando o código imediatamente.**

Primeiro, analise o projeto existente e compreenda como ele funciona.

Siga obrigatoriamente estas orientações:

1. Leia os arquivos principais do projeto.
2. Analise a estrutura de diretórios.
3. Identifique as bibliotecas instaladas e suas versões.
4. Identifique o banco de dados efetivamente utilizado.
5. Identifique as funcionalidades que já existem.
6. Verifique quais melhorias já estão implementadas.
7. Identifique possíveis conflitos entre este PRD e a arquitetura atual.
8. Elabore um plano de implementação compatível com o projeto.

Não implemente funcionalidades que já existem sem antes avaliar se precisam apenas de melhorias.

Não substitua bibliotecas funcionais sem justificativa técnica.

Não altere o banco de dados sem apresentar a necessidade e definir uma estratégia de migração e backup.

Não remova funcionalidades existentes.

Não invente estruturas, arquivos ou tabelas sem antes verificar o código.

## Estratégia de execução

Após o diagnóstico, implemente uma fase por vez.

Para cada fase:

1. Apresente o que será alterado.
2. Identifique os arquivos envolvidos.
3. Execute as modificações.
4. Teste as funcionalidades afetadas.
5. Corrija eventuais problemas.
6. Registre o que foi concluído.
7. Informe o que ainda está pendente.

Não avance para a próxima fase enquanto houver erros críticos na fase atual.

## Prioridade máxima

A prioridade é entregar um sistema visualmente moderno, consistente, funcional e confiável, mantendo a identidade do Balance.

**O resultado deve parecer uma evolução profissional do sistema existente, e não um projeto completamente diferente.**

---

# 17. Resultado esperado

Um gerenciador financeiro pessoal com:

- Interface moderna e profissional.
- Identidade visual consistente.
- Dashboard informativo.
- Controle de receitas e despesas.
- Categorias organizadas.
- Relatórios financeiros.
- Filtros eficientes.
- Orçamentos e metas.
- Boa experiência em dispositivos móveis.
- Código organizado e sustentável.
- Preservação das tecnologias e dos dados existentes.

**Diretriz final:** qualidade visual, simplicidade de uso, confiabilidade financeira e preservação da arquitetura atual devem orientar todas as decisões de desenvolvimento.