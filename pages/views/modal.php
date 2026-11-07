
	<?php
		$bancosFormulario = [];
		$categoriasFormulario = [];
		$usuarioFormularioId = (int) ($_SESSION['idUser'] ?? 0);
		if (empty($_SESSION['csrf_lancamento'])) {
			$_SESSION['csrf_lancamento'] = bin2hex(random_bytes(32));
		}
		try {
			$stmtBancosFormulario = $conexao->prepare('SELECT id, nome FROM bancos WHERE usuario_id = ? ORDER BY nome');
			if ($stmtBancosFormulario) {
				$stmtBancosFormulario->bind_param('i', $usuarioFormularioId);
				if ($stmtBancosFormulario->execute()) {
					$stmtBancosFormulario->bind_result($idBancoFormulario, $nomeBancoFormulario);
					while ($stmtBancosFormulario->fetch()) {
						$bancosFormulario[] = ['id' => $idBancoFormulario, 'nome' => $nomeBancoFormulario];
					}
				}
			}
		} catch (mysqli_sql_exception $exception) {
			$bancosFormulario = [];
		}
		try {
			$stmtCategoriasFormulario = $conexao->prepare('SELECT id, nome, tipo FROM categorias WHERE usuario_id = ? ORDER BY nome');
			if ($stmtCategoriasFormulario) {
				$stmtCategoriasFormulario->bind_param('i', $usuarioFormularioId);
				if ($stmtCategoriasFormulario->execute()) {
					$stmtCategoriasFormulario->bind_result($idCategoriaFormulario, $nomeCategoriaFormulario, $tipoCategoriaFormulario);
					while ($stmtCategoriasFormulario->fetch()) {
						$categoriasFormulario[] = ['id' => $idCategoriaFormulario, 'nome' => $nomeCategoriaFormulario, 'tipo' => $tipoCategoriaFormulario];
					}
				}
			}
		} catch (mysqli_sql_exception $exception) {
			$categoriasFormulario = [];
		}
	?>

	<!-- modal de configuração -->
	<section>

      <!-- Modal Structure configuração -->
      <div id="modal-config" class="modal profile-modal">
		   <div class="modal-content">
				<h4 class="center-align">Suas Configurações</h4>

				<div class="row valign-wrapper" style="margin-top: 30px; flex-wrap: wrap;">
					<!-- Lado esquerdo: Avatar -->
					<div class="col s12 m4 center-align profile-summary">
						<div class="circle" style="width: 100px; height: 100px; background: linear-gradient(145deg, #4b8bff, #2056df); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 42px; font-weight: bold;">
							<?= strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
						</div>
						<h5 style="margin-top: 15px;"><strong> @<?= strstr($_SESSION['email'], '@', true);?></strong></h5>
					</div>

					<!-- Lado direito: Informações -->
					<div class="col s12 m8 profile-details">
						<p><strong>Nome:</strong> <?= ucwords($_SESSION['usuario']);?></p>
						<p><strong>Email:</strong> <?= $_SESSION['email'];?></p>
						<p><strong>Ultimo Login:</strong> <?= (new DateTime('America/Sao_Paulo'))->format('d/m/Y H:i:s');?></p>
						<p><strong>Status:</strong> <span class="green white-text" style="padding: 2px 10px; border-radius: 15px; font-size: 0.9rem; font-weight: 500;">Ativo</span></p>
					</div>
				</div>

				<div class="row">	
					<div class="col s12" style="display: flex; justify-content: start; align-items: start;">
						<a href="tema.php" 
						   class="waves-effect waves-light btn btn-secondary" 
						   style="margin-right: 10px;display: flex; align-items: center;width: 160px;gap: 12px;">
							<i class="material-icons"><?= $_SESSION['temaUser'] == false ? 'brightness_3' : 'brightness_5';?></i> Mudar Tema
						</a>
						<a href="./login/logout.php" 
						   onclick='return confirm("Deseja realmente sair?")' 
						   class="waves-effect waves-light btn btn-danger" 
						   style="margin-right: 10px;display: flex; align-items: center;width: 100px;gap: 12px;"
						   title="Sair"> 
							<i class="material-icons">exit_to_app</i>Sair
						</a>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-close waves-effect btn btn-secondary" >Voltar</a>
		  </div>
      </div>

    </section>

    <section>

      <!-- Modal Structure adicionar item -->
      <div id="modal-add" class="modal">
        <div class="modal-content">
          <h4>Adicionar Lançamento</h4>
				<form action="adicionar.php" method="post" class="col s12">
				<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_lancamento'], ENT_QUOTES, 'UTF-8') ?>">
			  <label><span>Tipo de transferência:</span></label><br>
			 	<div class="row">	
					<label>
					  <input class="with-gap" name="tipo" type="radio" value="Entrada" checked />
					  <span>Entrada</span>
					</label>
					<label>
					  <input class="with-gap" name="tipo" type="radio" value="Saida"/>
                  <span>Saída</span>
					</label>
					<label>
					  <input class="with-gap" name="tipo" type="radio" value="Agendado"/>
					  <span>Agendado</span>
					</label>
			   </div>
			  <div class="row">
				<div class="input-field col s12 m6">
				  <input id="item_date" type="date" name="data_lancamento" value="<?= (new DateTime('now', new DateTimeZone('America/Fortaleza')))->format('Y-m-d') ?>" required>
				  <label for="item_date" class="active">Data do lançamento</label>
				</div>
				<div class="input-field col s12 m6">
				  <select id="item_bank" name="banco_id" required <?= count($bancosFormulario) === 0 ? 'disabled' : '' ?>>
					<option value="" disabled selected><?= count($bancosFormulario) === 0 ? 'Cadastre um banco primeiro' : 'Selecione o banco' ?></option>
					<?php foreach ($bancosFormulario as $bancoFormulario): ?>
					  <option value="<?= (int) $bancoFormulario['id'] ?>"><?= htmlspecialchars($bancoFormulario['nome'], ENT_QUOTES, 'UTF-8') ?></option>
					<?php endforeach; ?>
				  </select>
				  <label for="item_bank">Banco</label>
				  <span class="helper-text"><?php if (count($bancosFormulario) === 0): ?>Cadastre um <a href="bancos.php">banco</a> primeiro.<?php endif; ?></span>
				</div>
			  </div>
			  <div class="row">
				<div class="input-field col s12">
				  <select id="item_category" name="categoria_id" required <?= count($categoriasFormulario) === 0 ? 'disabled' : '' ?>>
					<option value="" disabled selected><?= count($categoriasFormulario) === 0 ? 'Cadastre uma categoria primeiro' : 'Selecione a categoria' ?></option>
					<?php foreach ($categoriasFormulario as $categoriaFormulario): ?>
					  <option value="<?= (int) $categoriaFormulario['id'] ?>" data-tipo="<?= htmlspecialchars($categoriaFormulario['tipo'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($categoriaFormulario['nome'] . ' — ' . ($categoriaFormulario['tipo'] === 'Entrada' ? 'Entrada' : 'Saída'), ENT_QUOTES, 'UTF-8') ?></option>
					<?php endforeach; ?>
				  </select>
				  <label for="item_category">Categoria</label>
				  <span class="helper-text" id="item-category-help"><?php if (count($categoriasFormulario) === 0): ?><a href="categorias.php">Cadastrar categoria</a><?php endif; ?></span>
				</div>
			  </div>
			  <div class="row">
				<div class="input-field col s12">
				  <input placeholder="Nome do lançamento" id="item_name" type="text" class="validate" name="nome" maxlength="255" required>
				  <label for="item_name">Nome do lançamento</label>
				  </div>
			  </div>
			  <div class="row">
				<div class="input-field col s12">
				  <input placeholder="Valor do lançamento" id="item_preco" type="number" class="validate" name="preco" min="0.01" step="0.01" required>
				  <label for="item_preco">Valor</label>
				  </div>
			  </div>
			  <div class="row">
				<div class="input-field col s12">
				  <input placeholder="Descrição do lançamento" id="item_desc" type="text" class="validate" name="desc" maxlength="255" required>
				  <label for="item_desc">Descrição</label>
				  </div>
			  </div>
			  	<div class="modal-footer">
					<a href="#!" class="modal-close waves-effect btn-flat btn-secondary">Cancelar</a>
					<input class="waves-light btn" type="submit" value="Adicionar">
				</div>
			</form>
        </div>

      </div>
	</section>
	<script>
	  document.addEventListener('DOMContentLoaded', function () {
		M.FormSelect.init(document.querySelectorAll('select'));
		var categoriaSelect = document.getElementById('item_category');
		var tipoInputs = document.querySelectorAll('#modal-add input[name="tipo"]');
		if (!categoriaSelect) return;
		var todasCategorias = Array.prototype.slice.call(categoriaSelect.options).filter(function (option) { return option.value !== ''; }).map(function (option) { return option.cloneNode(true); });
		var ajudaCategoria = document.getElementById('item-category-help');
		function atualizarCategorias() {
		  var tipoSelecionado = document.querySelector('#modal-add input[name="tipo"]:checked').value;
		  var tipoCategoria = tipoSelecionado === 'Saida' ? 'Saida' : tipoSelecionado;
		  var instancia = M.FormSelect.getInstance(categoriaSelect);
		  if (instancia) instancia.destroy();
		  categoriaSelect.innerHTML = '';
		  var placeholder = document.createElement('option');
		  placeholder.value = '';
		  placeholder.disabled = true;
		  placeholder.selected = true;
		  categoriaSelect.appendChild(placeholder);
		  if (tipoSelecionado === 'Agendado') {
			placeholder.textContent = 'Categoria opcional para agendados';
			categoriaSelect.required = false;
			categoriaSelect.disabled = false;
			ajudaCategoria.textContent = 'A categoria não é obrigatória para lançamentos agendados.';
		  } else {
			placeholder.textContent = 'Selecione a categoria';
			categoriaSelect.required = true;
			var categoriasDisponiveis = 0;
			todasCategorias.forEach(function (option) {
			  if (option.dataset.tipo === tipoCategoria) {
				categoriaSelect.appendChild(option.cloneNode(true));
				categoriasDisponiveis++;
			  }
			});
			categoriaSelect.disabled = categoriasDisponiveis === 0;
			if (categoriasDisponiveis === 0) {
			  placeholder.textContent = 'Nenhuma categoria deste tipo';
			  ajudaCategoria.innerHTML = 'Cadastre uma categoria de ' + (tipoCategoria === 'Entrada' ? 'entrada' : 'saída') + ' em <a href="categorias.php">Categorias</a>.';
			} else {
			  ajudaCategoria.textContent = '';
			}
		}
		  if (!categoriaSelect.disabled) M.FormSelect.init(categoriaSelect);
		}
		tipoInputs.forEach(function (input) { input.addEventListener('change', atualizarCategorias); });
		atualizarCategorias();

		var categoriaEdicao = document.getElementById('edit-item-category');
		var tiposEdicao = document.querySelectorAll('.edit-transaction-form input[name="tipo"]');
		if (categoriaEdicao) {
		  var categoriasEdicao = Array.prototype.slice.call(categoriaEdicao.options).filter(function (option) { return option.value !== ''; }).map(function (option) { return option.cloneNode(true); });
		  var ajudaCategoriaEdicao = document.getElementById('edit-category-help');
		  function atualizarCategoriasEdicao() {
			var tipoEdicao = document.querySelector('.edit-transaction-form input[name="tipo"]:checked').value;
			var instanciaEdicao = M.FormSelect.getInstance(categoriaEdicao);
			if (instanciaEdicao) instanciaEdicao.destroy();
			categoriaEdicao.innerHTML = '';
			var placeholderEdicao = document.createElement('option');
			placeholderEdicao.value = '';
			placeholderEdicao.disabled = true;
			placeholderEdicao.selected = true;
			if (tipoEdicao === 'Agendado') {
			  placeholderEdicao.textContent = 'Categoria opcional para agendados';
			  categoriaEdicao.appendChild(placeholderEdicao);
			  categoriaEdicao.required = false;
			  categoriaEdicao.disabled = false;
			  ajudaCategoriaEdicao.textContent = 'A categoria não é obrigatória para lançamentos agendados.';
			} else {
			  var tipoCategoriaEdicao = tipoEdicao === 'Saida' ? 'Saida' : 'Entrada';
			  var categoriaAtual = categoriaEdicao.dataset.selected;
			  var categoriasDisponiveisEdicao = 0;
			  placeholderEdicao.textContent = 'Selecione a categoria';
			  categoriaEdicao.appendChild(placeholderEdicao);
			  categoriasEdicao.forEach(function (option) {
				if (option.dataset.tipo === tipoCategoriaEdicao) {
				  var copia = option.cloneNode(true);
				  if (copia.value === categoriaAtual) {
					copia.selected = true;
					placeholderEdicao.selected = false;
				  }
				  categoriaEdicao.appendChild(copia);
				  categoriasDisponiveisEdicao++;
				}
			  });
			  categoriaEdicao.required = true;
			  categoriaEdicao.disabled = categoriasDisponiveisEdicao === 0;
			  if (categoriasDisponiveisEdicao === 0) {
				placeholderEdicao.textContent = 'Nenhuma categoria deste tipo';
				ajudaCategoriaEdicao.innerHTML = 'Cadastre uma categoria em <a href="categorias.php">Categorias</a>.';
			  } else {
				ajudaCategoriaEdicao.textContent = '';
			  }
			}
			if (!categoriaEdicao.disabled) M.FormSelect.init(categoriaEdicao);
		  }
		  tiposEdicao.forEach(function (input) { input.addEventListener('change', atualizarCategoriasEdicao); });
		  atualizarCategoriasEdicao();
		}
	  });
	</script>
