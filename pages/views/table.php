<section class="itens">
  <h4 class="center-align" style="margin-bottom: 1.5rem;">Todos os Lançamentos</h4>
  <?php
    $usuarioFiltroLancamento = (int) ($_SESSION['idUser'] ?? 0);
    $bancosFiltroLancamento = [];
    $stmtBancosFiltro = $conexao->prepare('SELECT id, nome FROM bancos WHERE usuario_id = ? ORDER BY nome, id');
    if ($stmtBancosFiltro) {
      $stmtBancosFiltro->bind_param('i', $usuarioFiltroLancamento);
      if ($stmtBancosFiltro->execute()) {
        $stmtBancosFiltro->bind_result($idBancoDisponivel, $nomeBancoDisponivel);
        while ($stmtBancosFiltro->fetch()) $bancosFiltroLancamento[] = ['id' => (int) $idBancoDisponivel, 'nome' => $nomeBancoDisponivel];
      }
      $stmtBancosFiltro->close();
    }

    $tiposLancamentoValidos = ['Entrada', 'Saida', 'Agendado'];
    $filtroTipoLancamento = $_GET['tipo'] ?? '';
    $filtroBancoId = filter_var($_GET['banco_id'] ?? 0, FILTER_VALIDATE_INT);
    if (!is_string($filtroTipoLancamento) || !in_array($filtroTipoLancamento, $tiposLancamentoValidos, true)) $filtroTipoLancamento = '';
    $idsBancosPermitidos = array_column($bancosFiltroLancamento, 'id');
    if ($filtroBancoId === false || $filtroBancoId < 0 || ($filtroBancoId > 0 && !in_array($filtroBancoId, $idsBancosPermitidos, true))) $filtroBancoId = 0;
  ?>
  <form method="get" action="lancamentos.php" class="transaction-filters">
    <div class="transaction-filters-heading">
      <div><span class="section-eyebrow">Refinar listagem</span><h5>Filtros</h5></div>
      <a class="btn-flat" href="lancamentos.php">Limpar filtros</a>
    </div>
    <div class="transaction-filters-fields">
      <div class="input-field">
        <select name="tipo" id="filter-transaction-type">
          <option value="" <?= $filtroTipoLancamento === '' ? 'selected' : '' ?>>Todos os tipos</option>
          <option value="Entrada" <?= $filtroTipoLancamento === 'Entrada' ? 'selected' : '' ?>>Entrada</option>
          <option value="Saida" <?= $filtroTipoLancamento === 'Saida' ? 'selected' : '' ?>>Saída</option>
          <option value="Agendado" <?= $filtroTipoLancamento === 'Agendado' ? 'selected' : '' ?>>Agendado</option>
        </select>
        <label for="filter-transaction-type">Tipo de lançamento</label>
      </div>
      <div class="input-field">
        <select name="banco_id" id="filter-bank-type">
          <option value="0" <?= $filtroBancoId === 0 ? 'selected' : '' ?>>Todos os bancos</option>
          <?php foreach ($bancosFiltroLancamento as $bancoOpcao): ?>
            <option value="<?= $bancoOpcao['id'] ?>" <?= $filtroBancoId === $bancoOpcao['id'] ? 'selected' : '' ?>><?= htmlspecialchars($bancoOpcao['nome'], ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
        <label for="filter-bank-type">Tipo do Banco</label>
      </div>
      <button class="btn" type="submit"><i class="material-icons left">filter_list</i>Filtrar</button>
    </div>
  </form>
  <!--Table com os dados-->
  <table class="striped centered responsive-table">
    <thead>
      <tr>
        <th>Status</th>
        <th>Item Nome</th>
        <th>Item Preço</th>
        <th>Descrição</th>
        <th>Banco</th>
        <th>Data</th>
        <th>Operação</th>
      </tr>
    </thead>

    <tbody>
    <?php 
      $stmtLancamentosFiltrados = $conexao->prepare("SELECT f.*, b.nome AS banco_nome FROM fluxocaixa f LEFT JOIN bancos b ON b.id = f.banco_id AND b.usuario_id = f.usuario_id WHERE f.usuario_id = ? AND (? = '' OR f.tipo = ?) AND (? = 0 OR (b.id = ? AND b.usuario_id = ?)) ORDER BY f.id DESC");
      $resultado = false;
      if ($stmtLancamentosFiltrados) {
        $stmtLancamentosFiltrados->bind_param('issiii', $usuarioFiltroLancamento, $filtroTipoLancamento, $filtroTipoLancamento, $filtroBancoId, $filtroBancoId, $usuarioFiltroLancamento);
        if ($stmtLancamentosFiltrados->execute()) $resultado = $stmtLancamentosFiltrados->get_result();
      }
      $row = $resultado ? mysqli_num_rows($resultado) : 0;

      if($row === 0) {
    ?>
        <tr><td colspan="7">Nenhum lançamento encontrado com esses filtros.</td></tr>
    <?php 
      } else {
        while ($linha = mysqli_fetch_assoc($resultado)) {
    ?>
        <tr>
          <td>
            <?php
              if ($linha['tipo'] == 'Saida') {
                echo "<div class='code-status'><span class='saida code-tipe'></span>" . $linha['tipo'] . "</div>";
              } else if ($linha['tipo'] == 'Entrada') {
                echo "<div class='code-status'><span class='entrada code-tipe'></span>" . $linha['tipo'] . "</div>";
              } else if ($linha['tipo'] == 'Agendado') {
                echo "<div class='code-status'><span class='agendado code-tipe'></span>" . $linha['tipo'] . "</div>";
              }
            ?>
          </td>
          <td><?= htmlspecialchars(mb_strimwidth((string) $linha['nome'], 0, 23, '…'), ENT_QUOTES, 'UTF-8') ?></td>
          <td>R$ <?= number_format($linha['preco'], 2, ',', ' ') ?></td>
          <td><?= htmlspecialchars(mb_strimwidth((string) $linha['disc'], 0, 23, '…'), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars((string) ($linha['banco_nome'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= !empty($linha['data_lancamento']) ? date('d/m/Y', strtotime($linha['data_lancamento'])) : htmlspecialchars((string) $linha['data'], ENT_QUOTES, 'UTF-8') ?></td>
          <td>
            <a href="#modal<?= $linha['id'] ?>" class="waves-effect btn btn-danger modal-trigger">
              <i class="material-icons">delete</i>
            </a>
            <a href="./atualizar.php?id=<?= $linha['id'] ?>" class="btn btn-edit waves-effect">
              <i class="material-icons">edit</i>
            </a>
            <a href="lancamentos.php?view=<?= $linha['id'] ?>" class="btn btn-view waves-effect">
              <i class="material-icons">visibility</i>
            </a>
          </td>
        </tr>

        <div id="modal<?= $linha['id'] ?>" class="modal">
          <div class="modal-content">
            <h4>Excluir item: <?= htmlspecialchars((string) $linha['nome'], ENT_QUOTES, 'UTF-8') ?></h4>
            <p>Tem certeza que quer excluir?</p>
          </div>
          <div class="modal-footer">
            <a href="./delete.php?id=<?= $linha['id'] ?>" class="modal-close waves-effect btn btn-danger">Aceitar</a>
            <a href="#!" class="modal-close waves-effect btn-flat btn-secondary">Cancelar</a>
          </div>
        </div>
    <?php 
        }
      }
    ?>
    </tbody>
  </table>
</section>
