<?php
session_start();

if (empty($_SESSION['idUser'])) {
    header('Location: ../index.php');
    exit;
}

require_once('../database/conexao.php');

$usuarioId = (int) $_SESSION['idUser'];
$tiposPermitidos = ['Entrada', 'Saida', 'Agendado'];
$coresPadrao = ['Entrada' => '#138A64', 'Saida' => '#D94F61', 'Agendado' => '#E8B84F'];
$form = ['nome' => '', 'tipo' => 'Entrada', 'cor' => $coresPadrao['Entrada']];
$erro = '';
$suportaAgendadoCategoria = false;
try {
    $resultadoColunaTipo = $conexao->query("SHOW COLUMNS FROM categorias LIKE 'tipo'");
    if ($resultadoColunaTipo && ($colunaTipoCategoria = $resultadoColunaTipo->fetch_assoc())) {
        $suportaAgendadoCategoria = stripos($colunaTipoCategoria['Type'], 'Agendado') !== false;
    }
} catch (Throwable $exception) {
    $suportaAgendadoCategoria = false;
}

if (empty($_SESSION['csrf_categorias'])) {
    $_SESSION['csrf_categorias'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {
    $idCategoriaExcluir = filter_var($_POST['categoria_id'] ?? null, FILTER_VALIDATE_INT);
    $csrfExcluir = $_POST['csrf_token'] ?? '';
    $erroExclusao = '';
    $excluiuCategoria = false;

    if (!is_string($csrfExcluir) || !hash_equals($_SESSION['csrf_categorias'], $csrfExcluir)) {
        $erroExclusao = 'A sessão expirou. Atualize a página e tente novamente.';
    } elseif ($idCategoriaExcluir === false || $idCategoriaExcluir < 1) {
        $erroExclusao = 'Categoria inválida.';
    } else {
        try {
            $conexao->begin_transaction();
            $stmtCategoriaAlvo = $conexao->prepare('SELECT id FROM categorias WHERE id = ? AND usuario_id = ? FOR UPDATE');
            $stmtCategoriaAlvo->bind_param('ii', $idCategoriaExcluir, $usuarioId);
            $stmtCategoriaAlvo->execute();
            $stmtCategoriaAlvo->store_result();
            $categoriaPertenceUsuario = $stmtCategoriaAlvo->num_rows === 1;
            $stmtCategoriaAlvo->close();

            if (!$categoriaPertenceUsuario) {
                $erroExclusao = 'Categoria não encontrada na sua conta.';
                $conexao->rollback();
            } else {
                $stmtUsoCategoria = $conexao->prepare('SELECT id FROM fluxocaixa WHERE categoria_id = ? AND usuario_id = ? LIMIT 1 FOR UPDATE');
                $stmtUsoCategoria->bind_param('ii', $idCategoriaExcluir, $usuarioId);
                $stmtUsoCategoria->execute();
                $stmtUsoCategoria->store_result();
                $categoriaEmUso = $stmtUsoCategoria->num_rows > 0;
                $stmtUsoCategoria->close();

                if ($categoriaEmUso) {
                    $erroExclusao = 'Esta categoria não pode ser excluída porque possui lançamentos associados.';
                    $conexao->rollback();
                } else {
                    $stmtExcluirCategoria = $conexao->prepare('DELETE FROM categorias WHERE id = ? AND usuario_id = ?');
                    $stmtExcluirCategoria->bind_param('ii', $idCategoriaExcluir, $usuarioId);
                    $excluiuCategoria = $stmtExcluirCategoria->execute() && $stmtExcluirCategoria->affected_rows === 1;
                    $stmtExcluirCategoria->close();
                    if ($excluiuCategoria) $conexao->commit();
                    else $conexao->rollback();
                }
            }
        } catch (Throwable $exception) {
            try { $conexao->rollback(); } catch (Throwable $ignorado) {}
            $erroExclusao = 'Não foi possível excluir a categoria. Tente novamente.';
        }
    }

    if ($excluiuCategoria) $_SESSION['mensagem'] = 'Categoria excluída com sucesso.';
    elseif ($erroExclusao !== '') $_SESSION['mensagemErro'] = $erroExclusao;
    header('Location: categorias.php');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['nome'] = trim($_POST['nome'] ?? '');
    $form['tipo'] = trim($_POST['tipo'] ?? '');
    $form['cor'] = $coresPadrao[$form['tipo']] ?? $coresPadrao['Entrada'];
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!is_string($csrfToken) || !hash_equals($_SESSION['csrf_categorias'], $csrfToken)) {
        $erro = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    } elseif ($form['nome'] === '' || strlen($form['nome']) > 120) {
        $erro = 'Informe o nome da categoria (até 120 caracteres).';
    } elseif (!in_array($form['tipo'], $tiposPermitidos, true)) {
        $erro = 'Selecione Entrada, Saída ou Agendado.';
    } elseif ($form['tipo'] === 'Agendado' && !$suportaAgendadoCategoria) {
        $erro = 'O banco ainda não aceita categorias agendadas. Faça backup e execute database/scripts/categorias_agendado.sql antes de tentar novamente.';
    } else {
        $salvouCategoria = false;
        try {
            $stmt = $conexao->prepare('INSERT INTO categorias (usuario_id, nome, tipo, cor) VALUES (?, ?, ?, ?)');
            if ($stmt) {
                $stmt->bind_param('isss', $usuarioId, $form['nome'], $form['tipo'], $form['cor']);
                $salvouCategoria = $stmt->execute();
            }
        } catch (mysqli_sql_exception $exception) {
            $salvouCategoria = false;
        }

        if ($salvouCategoria) {
            $_SESSION['mensagem'] = 'Categoria cadastrada com sucesso.';
            header('Location: categorias.php');
            exit;
        }
        $erro = 'Não foi possível salvar a categoria. Para habilitar tipos de categoria agendada em uma base existente, aplique database/scripts/categorias_agendado.sql.';
    }
}

$categorias = ['Entrada' => [], 'Saida' => [], 'Agendado' => []];
$erroTabela = false;
try {
    $stmtCategorias = $conexao->prepare('SELECT id, nome, tipo, cor, criado_em FROM categorias WHERE usuario_id = ? ORDER BY tipo, nome');
    if ($stmtCategorias) {
        $stmtCategorias->bind_param('i', $usuarioId);
        if ($stmtCategorias->execute()) {
            $stmtCategorias->bind_result($categoriaId, $categoriaNome, $categoriaTipo, $categoriaCor, $categoriaCriadoEm);
            while ($stmtCategorias->fetch()) {
                if (isset($categorias[$categoriaTipo])) {
                    $categorias[$categoriaTipo][] = [
                        'id' => $categoriaId,
                        'nome' => $categoriaNome,
                        'tipo' => $categoriaTipo,
                        'cor' => $categoriaCor,
                        'criado_em' => $categoriaCriadoEm
                    ];
                }
            }
        }
    } else {
        $erroTabela = true;
    }
} catch (mysqli_sql_exception $exception) {
    $erroTabela = true;
}

function escaparCategoria($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function textoCategoriaSobreCor($hex) {
    $hex = ltrim($hex, '#');
    $luminancia = (0.299 * hexdec(substr($hex, 0, 2))) + (0.587 * hexdec(substr($hex, 2, 2))) + (0.114 * hexdec(substr($hex, 4, 2)));
    return $luminancia > 160 ? '#18243b' : '#ffffff';
}
?>
<!DOCTYPE html>
<html lang="pt-br" class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#246bfe">
  <link rel="icon" type="image/svg+xml" href="../assets/img/favicon.svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css?v=20261107-22">
  <title>Balance - Categorias</title>
</head>
<body class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">
  <?php include('./views/header.php'); ?>
  <main class="banks-page categories-page">
    <div class="container banks-heading">
      <div><span class="section-eyebrow">Organização financeira</span><h2>Categorias</h2><p>Separe entradas, saídas e lançamentos agendados por categoria.</p></div>
    </div>

    <section class="container bank-form-card" aria-labelledby="category-form-title">
      <h3 id="category-form-title"><i class="material-icons" aria-hidden="true">add_circle_outline</i>Adicionar categoria</h3>
      <?php if ($erro !== ''): ?><p class="bank-feedback bank-feedback-error" role="alert"><?= escaparCategoria($erro) ?></p><?php endif; ?>
      <?php if ($erroTabela): ?>
        <p class="bank-feedback bank-feedback-error" role="alert">A tabela de categorias ainda não está disponível. Execute os scripts de categoria em <code>database/scripts/</code> no MySQL e atualize esta página.</p>
      <?php endif; ?>
      <?php if (!empty($_SESSION['mensagem'])): ?>
        <p class="bank-feedback bank-feedback-success" role="status"><?= escaparCategoria($_SESSION['mensagem']) ?></p>
        <?php unset($_SESSION['mensagem']); ?>
      <?php endif; ?>
      <?php if (!empty($_SESSION['mensagemErro'])): ?>
        <p class="bank-feedback bank-feedback-error" role="alert"><?= escaparCategoria($_SESSION['mensagemErro']) ?></p>
        <?php unset($_SESSION['mensagemErro']); ?>
      <?php endif; ?>

      <form method="post" action="categorias.php">
        <input type="hidden" name="csrf_token" value="<?= escaparCategoria($_SESSION['csrf_categorias']) ?>">
        <div class="row">
          <div class="input-field col s12 m5">
            <input id="category-name" name="nome" type="text" maxlength="120" value="<?= escaparCategoria($form['nome']) ?>" required>
            <label for="category-name" class="<?= $form['nome'] !== '' ? 'active' : '' ?>">Nome da categoria</label>
          </div>
          <div class="input-field col s12 m4">
            <select id="category-type" name="tipo" required>
              <option value="Entrada" <?= $form['tipo'] === 'Entrada' ? 'selected' : '' ?>>Entrada</option>
              <option value="Saida" <?= $form['tipo'] === 'Saida' ? 'selected' : '' ?>>Saída</option>
              <option value="Agendado" <?= $form['tipo'] === 'Agendado' ? 'selected' : '' ?>>Agendado</option>
            </select>
            <label for="category-type">Tipo</label>
          </div>
          <div class="input-field col s12 m3 bank-color-field">
            <label for="category-color">Cor da categoria</label>
            <input id="category-color" class="category-color-locked" type="color" value="<?= escaparCategoria($form['cor']) ?>" data-income-color="<?= $coresPadrao['Entrada'] ?>" data-expense-color="<?= $coresPadrao['Saida'] ?>" data-scheduled-color="<?= $coresPadrao['Agendado'] ?>" aria-label="Cor padronizada para o tipo selecionado" disabled>
          </div>
        </div>
        <p class="category-color-notice"><i class="material-icons" aria-hidden="true">info_outline</i>A cor é definida automaticamente: verde para entradas, vermelho para saídas e âmbar para agendados.</p>
        <div class="bank-form-actions"><button class="btn waves-effect waves-light" type="submit"><i class="material-icons left">save</i>Salvar categoria</button></div>
      </form>
    </section>

    <section class="container bank-list-section categories-list" aria-label="Categorias cadastradas">
      <?php foreach ($categorias as $tipoCategoria => $listaCategorias):
        $corTipoPadrao = $coresPadrao[$tipoCategoria];
        $classeTipo = $tipoCategoria === 'Entrada' ? 'income' : ($tipoCategoria === 'Saida' ? 'expense' : 'scheduled');
      ?>
        <section class="category-group <?= $classeTipo ?>" aria-labelledby="category-<?= $classeTipo ?>-title">
          <div class="bank-list-heading"><h3 id="category-<?= $classeTipo ?>-title"><span class="category-type-dot"></span><?= $tipoCategoria === 'Entrada' ? 'Entradas' : ($tipoCategoria === 'Saida' ? 'Saídas' : 'Agendados') ?></h3><span><?= count($listaCategorias) ?> <?= count($listaCategorias) === 1 ? 'categoria' : 'categorias' ?></span></div>
          <?php if (count($listaCategorias) === 0): ?>
            <div class="bank-empty category-empty"><i class="material-icons" aria-hidden="true"><?= $tipoCategoria === 'Entrada' ? 'north_east' : ($tipoCategoria === 'Saida' ? 'south_west' : 'schedule') ?></i><p>Nenhuma categoria de <?= strtolower($tipoCategoria) ?> cadastrada.</p></div>
          <?php else: ?>
            <div class="bank-grid">
              <?php foreach ($listaCategorias as $categoria):
                $corCategoria = preg_match('/^#[0-9A-Fa-f]{6}$/', $categoria['cor']) ? $categoria['cor'] : $corTipoPadrao;
                $corTextoCategoria = textoCategoriaSobreCor($corCategoria);
              ?>
                <article class="bank-card category-card" style="--bank-card-color: <?= escaparCategoria($corCategoria) ?>; --bank-card-text: <?= escaparCategoria($corTextoCategoria) ?>;">
                  <div class="bank-card-top"><span class="bank-card-icon"><i class="material-icons" aria-hidden="true"><?= $tipoCategoria === 'Entrada' ? 'north_east' : ($tipoCategoria === 'Saida' ? 'south_west' : 'schedule') ?></i></span><span class="bank-card-type"><?= escaparCategoria($tipoCategoria) ?></span></div>
                  <h4><?= escaparCategoria($categoria['nome']) ?></h4>
                  <div class="bank-card-date"><i class="material-icons" aria-hidden="true">event</i>Adicionada em <?= escaparCategoria(date('d/m/Y', strtotime($categoria['criado_em']))) ?></div>
                  <div class="bank-card-actions">
                    <form method="post" action="categorias.php" onsubmit="return confirm('Excluir esta categoria? Essa ação não pode ser desfeita.');">
                      <input type="hidden" name="csrf_token" value="<?= escaparCategoria($_SESSION['csrf_categorias']) ?>">
                      <input type="hidden" name="acao" value="excluir">
                      <input type="hidden" name="categoria_id" value="<?= (int) $categoria['id'] ?>">
                      <button class="btn btn-danger bank-card-delete waves-effect waves-light" type="submit" aria-label="Excluir categoria <?= escaparCategoria($categoria['nome']) ?>" title="Excluir categoria"><i class="material-icons" aria-hidden="true">delete</i></button>
                    </form>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>
      <?php endforeach; ?>
    </section>
  </main>
  <?php include('./views/modal.php'); ?>
  <?php include('./views/footer.php'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      M.Sidenav.init(document.querySelectorAll('.sidenav'));
      M.Modal.init(document.querySelectorAll('.modal'));
      var tipoCategoria = document.getElementById('category-type');
      var corCategoria = document.getElementById('category-color');
      tipoCategoria.addEventListener('change', function () {
        corCategoria.value = tipoCategoria.value === 'Entrada' ? corCategoria.dataset.incomeColor : (tipoCategoria.value === 'Saida' ? corCategoria.dataset.expenseColor : corCategoria.dataset.scheduledColor);
      });
    });
  </script>
</body>
</html>
