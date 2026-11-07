<?php
$usuarioDashboardId = (int) ($_SESSION['idUser'] ?? 0);
$bancosDashboard = [];
$categoriasDashboard = [];
$saldoConsolidadoDashboard = 0.0;

$stmtBancosDashboard = $conexao->prepare("SELECT b.id, b.nome, b.cor, COALESCE(SUM(CASE WHEN f.tipo = 'Entrada' THEN ABS(f.preco) WHEN f.tipo = 'Saida' THEN -ABS(f.preco) ELSE 0 END), 0) AS saldo FROM bancos b LEFT JOIN fluxocaixa f ON f.banco_id = b.id WHERE b.usuario_id = ? GROUP BY b.id, b.nome, b.cor ORDER BY b.nome");
if ($stmtBancosDashboard) {
  $stmtBancosDashboard->bind_param('i', $usuarioDashboardId);
  if ($stmtBancosDashboard->execute()) {
    $stmtBancosDashboard->bind_result($idBancoDashboard, $nomeBancoDashboard, $corBancoDashboard, $saldoBancoDashboard);
    while ($stmtBancosDashboard->fetch()) {
      $bancosDashboard[] = ['id' => (int) $idBancoDashboard, 'nome' => $nomeBancoDashboard, 'cor' => $corBancoDashboard, 'saldo' => (float) $saldoBancoDashboard];
      $saldoConsolidadoDashboard += (float) $saldoBancoDashboard;
    }
  }
  $stmtBancosDashboard->close();
}

$stmtCategoriasDashboard = $conexao->prepare('SELECT id, nome, tipo, cor FROM categorias WHERE usuario_id = ? ORDER BY tipo, nome');
if ($stmtCategoriasDashboard) {
  $stmtCategoriasDashboard->bind_param('i', $usuarioDashboardId);
  if ($stmtCategoriasDashboard->execute()) {
    $stmtCategoriasDashboard->bind_result($idCategoriaDashboard, $nomeCategoriaDashboard, $tipoCategoriaDashboard, $corCategoriaDashboard);
    while ($stmtCategoriasDashboard->fetch()) {
      $categoriasDashboard[] = ['id' => (int) $idCategoriaDashboard, 'nome' => $nomeCategoriaDashboard, 'tipo' => $tipoCategoriaDashboard, 'cor' => $corCategoriaDashboard];
    }
  }
  $stmtCategoriasDashboard->close();
}
?>

<section class="recent-transactions" aria-labelledby="recent-transactions-title">
  <div class="recent-transactions-heading">
    <div>
      <span class="section-eyebrow">Atividade</span>
      <h3 id="recent-transactions-title">Últimos lançamentos</h3>
    </div>
    <a class="recent-all-link" href="lancamentos.php">Ver todos <i class="material-icons" aria-hidden="true">arrow_forward</i></a>
  </div>

  <div class="recent-transactions-list">
    <?php
      $consultaRecentes = new SelectAll();
      $resultadoRecentes = mysqli_query($conexao, $consultaRecentes->selectUltimosCinco());
      $totalRecentes = $resultadoRecentes ? mysqli_num_rows($resultadoRecentes) : 0;

      if ($totalRecentes === 0) {
    ?>
      <p class="recent-empty">Seus lançamentos recentes aparecerão aqui.</p>
    <?php
      } else {
        while ($lancamentoRecente = mysqli_fetch_assoc($resultadoRecentes)) {
          $tipoRecente = (string) $lancamentoRecente['tipo'];
          $classeTipoRecente = $tipoRecente === 'Entrada' ? 'income' : ($tipoRecente === 'Saida' ? 'expense' : 'scheduled');
          $iconeTipoRecente = $tipoRecente === 'Entrada' ? 'north_east' : ($tipoRecente === 'Saida' ? 'south_west' : 'schedule');
          $valorRecente = number_format((float) $lancamentoRecente['preco'], 2, ',', '.');
          $nomeRecente = htmlspecialchars((string) $lancamentoRecente['nome'], ENT_QUOTES, 'UTF-8');
          $dataRecente = htmlspecialchars((string) $lancamentoRecente['data'], ENT_QUOTES, 'UTF-8');
          $tipoSeguro = htmlspecialchars($tipoRecente, ENT_QUOTES, 'UTF-8');
    ?>
      <article class="recent-transaction">
        <span class="recent-transaction-icon <?= $classeTipoRecente ?>"><i class="material-icons" aria-hidden="true"><?= $iconeTipoRecente ?></i></span>
        <div class="recent-transaction-info">
          <strong><?= $nomeRecente ?></strong>
          <span><?= $tipoSeguro ?> <span aria-hidden="true">·</span> <?= $dataRecente ?></span>
        </div>
        <strong class="recent-transaction-amount <?= $classeTipoRecente ?>">R$ <?= $valorRecente ?></strong>
      </article>
    <?php
        }
      }
    ?>
  </div>
</section>

<section class="dashboard-bank-balances" aria-labelledby="dashboard-bank-balances-title">
  <div class="recent-transactions-heading">
    <div>
      <span class="section-eyebrow">Resumo financeiro</span>
      <h3 id="dashboard-bank-balances-title">Saldo por banco</h3>
    </div>
    <strong class="dashboard-consolidated-balance">Total: R$ <?= number_format($saldoConsolidadoDashboard, 2, ',', '.') ?></strong>
  </div>
  <?php if (!$bancosDashboard): ?>
    <p class="recent-empty">Cadastre bancos para acompanhar o saldo e os lançamentos por conta.</p>
  <?php else: ?>
    <div class="dashboard-bank-grid">
      <?php foreach ($bancosDashboard as $bancoDashboard): ?>
        <?php
          $corBancoCss = preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $bancoDashboard['cor']) ? $bancoDashboard['cor'] : '#246BFE';
          $contrasteBanco = '#ffffff';
          $corHex = ltrim($corBancoCss, '#');
          $luminosidadeBanco = (hexdec(substr($corHex, 0, 2)) * 299 + hexdec(substr($corHex, 2, 2)) * 587 + hexdec(substr($corHex, 4, 2)) * 114) / 1000;
          if ($luminosidadeBanco > 160) $contrasteBanco = '#172033';
        ?>
        <article class="dashboard-bank-card" style="--dashboard-bank-color: <?= htmlspecialchars($corBancoCss, ENT_QUOTES, 'UTF-8') ?>; --dashboard-bank-text: <?= $contrasteBanco ?>">
          <span><i class="material-icons" aria-hidden="true">account_balance</i><?= htmlspecialchars($bancoDashboard['nome'], ENT_QUOTES, 'UTF-8') ?></span>
          <strong>R$ <?= number_format($bancoDashboard['saldo'], 2, ',', '.') ?></strong>
          <small>Entradas menos saídas</small>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section class="category-recent-section" aria-labelledby="category-recent-title">
  <div class="recent-transactions-heading">
    <div>
      <span class="section-eyebrow">Atividade</span>
      <h3 id="category-recent-title">Últimos lançamentos por categoria</h3>
    </div>
    <a class="recent-all-link" href="lancamentos.php">Ver todos <i class="material-icons" aria-hidden="true">arrow_forward</i></a>
  </div>

  <?php if (!$categoriasDashboard): ?>
    <p class="recent-empty">Cadastre categorias para acompanhar os lançamentos agrupados.</p>
  <?php else: ?>
    <div class="category-recent-grid">
      <?php foreach ($categoriasDashboard as $categoriaDashboard): ?>
        <?php
          $lancamentosCategoriaDashboard = [];
          $stmtLancamentosCategoria = $conexao->prepare('SELECT f.tipo, f.nome, f.preco, f.data, f.data_lancamento, b.nome FROM fluxocaixa f LEFT JOIN bancos b ON b.id = f.banco_id WHERE f.categoria_id = ? AND (f.banco_id IS NULL OR b.usuario_id = ?) ORDER BY f.id DESC LIMIT 6');
          if ($stmtLancamentosCategoria) {
            $stmtLancamentosCategoria->bind_param('ii', $categoriaDashboard['id'], $usuarioDashboardId);
            if ($stmtLancamentosCategoria->execute()) {
              $stmtLancamentosCategoria->bind_result($tipoLancamentoCategoria, $nomeLancamentoCategoria, $precoLancamentoCategoria, $dataCriacaoCategoria, $dataLancamentoCategoria, $nomeBancoCategoria);
              while ($stmtLancamentosCategoria->fetch()) {
                $lancamentosCategoriaDashboard[] = ['tipo' => $tipoLancamentoCategoria, 'nome' => $nomeLancamentoCategoria, 'preco' => (float) $precoLancamentoCategoria, 'data' => $dataLancamentoCategoria ?: $dataCriacaoCategoria, 'banco' => $nomeBancoCategoria];
              }
            }
            $stmtLancamentosCategoria->close();
          }
          $classeCategoriaDashboard = $categoriaDashboard['tipo'] === 'Entrada' ? 'income' : 'expense';
          $corCategoriaCss = preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $categoriaDashboard['cor']) ? $categoriaDashboard['cor'] : ($classeCategoriaDashboard === 'income' ? '#138a60' : '#d64f5d');
        ?>
        <article class="category-recent-card <?= $classeCategoriaDashboard ?>" style="--category-accent: <?= htmlspecialchars($corCategoriaCss, ENT_QUOTES, 'UTF-8') ?>">
          <header>
            <span class="category-recent-dot" aria-hidden="true"></span>
            <div><h4><?= htmlspecialchars($categoriaDashboard['nome'], ENT_QUOTES, 'UTF-8') ?></h4><small><?= $categoriaDashboard['tipo'] === 'Entrada' ? 'Entrada' : 'Saída' ?></small></div>
            <span class="category-recent-count"><?= count($lancamentosCategoriaDashboard) ?>/6</span>
          </header>
          <?php if (!$lancamentosCategoriaDashboard): ?>
            <p class="category-recent-empty">Nenhum lançamento nesta categoria.</p>
          <?php else: ?>
            <div class="category-recent-list">
              <?php foreach ($lancamentosCategoriaDashboard as $lancamentoCategoria): ?>
                <?php
                  $tipoItemClasse = $lancamentoCategoria['tipo'] === 'Entrada' ? 'income' : ($lancamentoCategoria['tipo'] === 'Saida' ? 'expense' : 'scheduled');
                  $dataItem = strtotime((string) $lancamentoCategoria['data']);
                  $dataItemFormatada = $dataItem ? date('d/m/Y', $dataItem) : (string) $lancamentoCategoria['data'];
                ?>
                <div class="category-recent-item">
                  <div><strong><?= htmlspecialchars($lancamentoCategoria['nome'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($lancamentoCategoria['banco'] ?: 'Banco não informado', ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars($dataItemFormatada, ENT_QUOTES, 'UTF-8') ?></small></div>
                  <b class="<?= $tipoItemClasse ?>">R$ <?= number_format($lancamentoCategoria['preco'], 2, ',', '.') ?></b>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
