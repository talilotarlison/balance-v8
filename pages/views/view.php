<?php
include_once __DIR__ . '/../../utils/updateController.php';
$tipoVisualizado = $arrItem[0] ?? '';
?>
<section class="container horizontal" aria-label="Detalhes do lançamento">
  <div id="view-modal" class="card-panel white lighten-1" style="width: 60vw; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 7; display: <?= $ehVisualizacao && !empty($arrItem) ? 'block' : 'none' ?>;">
    <h4>Detalhes do lançamento</h4>
    <?php if (!empty($arrItem)): ?>
      <div class="row transaction-type-fieldset">
        <div class="col s12"><strong>Tipo de transferência</strong></div>
        <?php foreach (['Entrada' => 'Entrada', 'Saida' => 'Saída', 'Agendado' => 'Agendado'] as $valorTipoVisualizacao => $rotuloTipoVisualizacao): ?>
          <label class="col s4">
            <input class="with-gap" type="radio" value="<?= $valorTipoVisualizacao ?>" <?= $tipoVisualizado === $valorTipoVisualizacao ? 'checked' : '' ?> disabled>
            <span><?= $rotuloTipoVisualizacao ?></span>
          </label>
        <?php endforeach; ?>
      </div>

      <div class="row">
        <div class="input-field col s12 m6">
          <input id="view-item-name" type="text" value="<?= htmlspecialchars((string) $arrItem[1], ENT_QUOTES, 'UTF-8') ?>" disabled>
          <label for="view-item-name" class="active">Nome do lançamento</label>
        </div>
        <div class="input-field col s12 m6">
          <input id="view-item-price" type="text" value="R$ <?= htmlspecialchars(number_format((float) $arrItem[2], 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?>" disabled>
          <label for="view-item-price" class="active">Valor</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12 m6">
          <input id="view-item-date" type="text" value="<?= !empty($arrItem[4]) ? htmlspecialchars(date('d/m/Y', strtotime($arrItem[4])), ENT_QUOTES, 'UTF-8') : 'Não informada' ?>" disabled>
          <label for="view-item-date" class="active">Data do lançamento</label>
        </div>
        <div class="input-field col s12 m6">
          <input id="view-item-bank" type="text" value="<?= htmlspecialchars((string) ($arrItem[7] ?? 'Não associado'), ENT_QUOTES, 'UTF-8') ?>" disabled>
          <label for="view-item-bank" class="active">Banco</label>
        </div>
        <div class="input-field col s12 m6">
          <input id="view-item-category" type="text" value="<?= htmlspecialchars((string) ($arrItem[8] ?? 'Não associada'), ENT_QUOTES, 'UTF-8') ?>" disabled>
          <label for="view-item-category" class="active">Categoria</label>
        </div>
        <div class="input-field col s12 m6">
          <input id="view-item-created" type="text" value="<?= htmlspecialchars((string) ($arrItem[9] ?? ''), ENT_QUOTES, 'UTF-8') ?>" disabled>
          <label for="view-item-created" class="active">Data de criação</label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <textarea id="view-item-description" class="materialize-textarea" disabled><?= htmlspecialchars((string) $arrItem[3], ENT_QUOTES, 'UTF-8') ?></textarea>
          <label for="view-item-description" class="active">Descrição</label>
        </div>
      </div>
    <?php else: ?>
      <p>Lançamento não encontrado.</p>
    <?php endif; ?>
    <div class="view-modal-actions">
      <button class="btn btn-secondary" type="button" onclick="bntVoltar()">Voltar</button>
    </div>
  </div>
</section>
