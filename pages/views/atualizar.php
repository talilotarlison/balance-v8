<?php
$tipoLancamentoAtual = $arrItem[0] ?? 'Entrada';
$dataLancamentoAtual = !empty($arrItem[4]) ? $arrItem[4] : '';
$idLancamentoAtual = (int) ($idItemSolicitado ?? 0);
?>
<section class="container horizontal edit-transaction-page">
  <div class="edit-transaction-card">
    <span class="section-eyebrow">Lançamentos</span>
    <h2>Editar lançamento</h2>
    <?php if (empty($arrItem)): ?>
      <p class="bank-feedback bank-feedback-error" role="alert">Lançamento não encontrado.</p>
    <?php else: ?>
      <form action="update.php" method="post" class="edit-transaction-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_editar_lancamento'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="id" value="<?= $idLancamentoAtual ?>">

        <fieldset class="transaction-type-fieldset">
          <legend>Tipo de transferência</legend>
          <?php foreach (['Entrada' => 'Entrada', 'Saida' => 'Saída', 'Agendado' => 'Agendado'] as $valorTipo => $rotuloTipo): ?>
            <label>
              <input class="with-gap" name="tipo" type="radio" value="<?= $valorTipo ?>" <?= $tipoLancamentoAtual === $valorTipo ? 'checked' : '' ?> required>
              <span><?= $rotuloTipo ?></span>
            </label>
          <?php endforeach; ?>
        </fieldset>

        <div class="row">
          <div class="input-field col s12 m6">
            <input id="edit-item-name" name="nome" type="text" maxlength="255" value="<?= htmlspecialchars((string) ($arrItem[1] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            <label for="edit-item-name" class="active">Nome do lançamento</label>
          </div>
          <div class="input-field col s12 m6">
            <input id="edit-item-price" name="preco" type="number" min="0.01" step="0.01" value="<?= htmlspecialchars((string) abs((float) ($arrItem[2] ?? 0)), ENT_QUOTES, 'UTF-8') ?>" required>
            <label for="edit-item-price" class="active">Valor</label>
          </div>
        </div>

        <div class="row">
          <div class="input-field col s12 m4">
            <input id="edit-item-date" name="data_lancamento" type="date" value="<?= htmlspecialchars($dataLancamentoAtual, ENT_QUOTES, 'UTF-8') ?>" required>
            <label for="edit-item-date" class="active">Data do lançamento</label>
          </div>
          <div class="input-field col s12 m4">
            <select id="edit-item-bank" name="banco_id" required <?= count($bancosFormulario) === 0 ? 'disabled' : '' ?>>
              <option value="" disabled <?= empty($arrItem[5]) ? 'selected' : '' ?>><?= count($bancosFormulario) === 0 ? 'Cadastre um banco primeiro' : 'Selecione o banco' ?></option>
              <?php foreach ($bancosFormulario as $bancoEdicao): ?>
                <option value="<?= (int) $bancoEdicao['id'] ?>" <?= (int) ($arrItem[5] ?? 0) === (int) $bancoEdicao['id'] ? 'selected' : '' ?>><?= htmlspecialchars($bancoEdicao['nome'], ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
            <label for="edit-item-bank">Banco</label>
            <?php if (count($bancosFormulario) === 0): ?><span class="helper-text">Cadastre um <a href="bancos.php">banco</a> primeiro.</span><?php endif; ?>
          </div>
          <div class="input-field col s12 m4">
            <select id="edit-item-category" name="categoria_id" data-selected="<?= (int) ($arrItem[6] ?? 0) ?>" required <?= count($categoriasFormulario) === 0 ? 'disabled' : '' ?>>
              <option value="" disabled <?= empty($arrItem[6]) ? 'selected' : '' ?>>Selecione a categoria</option>
              <?php foreach ($categoriasFormulario as $categoriaEdicao): ?>
                <option value="<?= (int) $categoriaEdicao['id'] ?>" data-tipo="<?= htmlspecialchars($categoriaEdicao['tipo'], ENT_QUOTES, 'UTF-8') ?>" <?= (int) ($arrItem[6] ?? 0) === (int) $categoriaEdicao['id'] ? 'selected' : '' ?>><?= htmlspecialchars($categoriaEdicao['nome'] . ' — ' . ($categoriaEdicao['tipo'] === 'Entrada' ? 'Entrada' : 'Saída'), ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
            <label for="edit-item-category">Categoria</label>
            <span class="helper-text" id="edit-category-help"><?php if (count($categoriasFormulario) === 0): ?><a href="categorias.php">Cadastrar categoria</a><?php endif; ?></span>
          </div>
        </div>

        <div class="row">
          <div class="input-field col s12">
            <textarea id="edit-item-description" name="desc" class="materialize-textarea" maxlength="255" required><?= htmlspecialchars((string) ($arrItem[3] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
            <label for="edit-item-description" class="active">Descrição</label>
          </div>
        </div>
        <div class="edit-transaction-actions">
          <a class="btn btn-secondary" href="lancamentos.php">Cancelar</a>
          <button class="btn" type="submit"><i class="material-icons left">save</i>Salvar alterações</button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</section>
