<?php
session_start();

if (empty($_SESSION['idUser'])) {
    header('Location: ../index.php');
    exit;
}

require_once('../database/conexao.php');

$usuarioId = (int) $_SESSION['idUser'];
$tiposPermitidos = ['Conta corrente', 'Poupança', 'Conta digital', 'Investimentos', 'Carteira','Dinheiro', 'Outro'];
$form = [
    'nome' => '',
    'tipo' => 'Conta corrente',
    'cor' => '#246BFE',
    'descricao' => ''
];
$erro = '';

if (empty($_SESSION['csrf_bancos'])) {
    $_SESSION['csrf_bancos'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {
    $idBancoExcluir = filter_var($_POST['banco_id'] ?? null, FILTER_VALIDATE_INT);
    $csrfExcluir = $_POST['csrf_token'] ?? '';
    $erroExclusao = '';
    $excluiuBanco = false;

    if (!is_string($csrfExcluir) || !hash_equals($_SESSION['csrf_bancos'], $csrfExcluir)) {
        $erroExclusao = 'A sessão expirou. Atualize a página e tente novamente.';
    } elseif ($idBancoExcluir === false || $idBancoExcluir < 1) {
        $erroExclusao = 'Banco inválido.';
    } else {
        try {
            $conexao->begin_transaction();
            $stmtBancoAlvo = $conexao->prepare('SELECT id FROM bancos WHERE id = ? AND usuario_id = ? FOR UPDATE');
            $stmtBancoAlvo->bind_param('ii', $idBancoExcluir, $usuarioId);
            $stmtBancoAlvo->execute();
            $stmtBancoAlvo->store_result();
            $bancoPertenceUsuario = $stmtBancoAlvo->num_rows === 1;
            $stmtBancoAlvo->close();

            if (!$bancoPertenceUsuario) {
                $erroExclusao = 'Banco não encontrado na sua conta.';
                $conexao->rollback();
            } else {
                $stmtUsoBanco = $conexao->prepare('SELECT id FROM fluxocaixa WHERE banco_id = ? AND usuario_id = ? LIMIT 1 FOR UPDATE');
                $stmtUsoBanco->bind_param('ii', $idBancoExcluir, $usuarioId);
                $stmtUsoBanco->execute();
                $stmtUsoBanco->store_result();
                $bancoEmUso = $stmtUsoBanco->num_rows > 0;
                $stmtUsoBanco->close();

                if ($bancoEmUso) {
                    $erroExclusao = 'Este banco não pode ser excluído porque possui lançamentos associados.';
                    $conexao->rollback();
                } else {
                    $stmtExcluirBanco = $conexao->prepare('DELETE FROM bancos WHERE id = ? AND usuario_id = ?');
                    $stmtExcluirBanco->bind_param('ii', $idBancoExcluir, $usuarioId);
                    $excluiuBanco = $stmtExcluirBanco->execute() && $stmtExcluirBanco->affected_rows === 1;
                    $stmtExcluirBanco->close();
                    if ($excluiuBanco) $conexao->commit();
                    else $conexao->rollback();
                }
            }
        } catch (Throwable $exception) {
            try { $conexao->rollback(); } catch (Throwable $ignorado) {}
            $erroExclusao = 'Não foi possível excluir o banco. Tente novamente.';
        }
    }

    if ($excluiuBanco) $_SESSION['mensagem'] = 'Banco excluído com sucesso.';
    elseif ($erroExclusao !== '') $_SESSION['mensagemErro'] = $erroExclusao;
    header('Location: bancos.php');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['nome'] = trim($_POST['nome'] ?? '');
    $form['tipo'] = trim($_POST['tipo'] ?? '');
    $form['cor'] = trim($_POST['cor'] ?? '');
    $form['descricao'] = trim($_POST['descricao'] ?? '');
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!is_string($csrfToken) || !hash_equals($_SESSION['csrf_bancos'], $csrfToken)) {
        $erro = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    } elseif ($form['nome'] === '' || strlen($form['nome']) > 120) {
        $erro = 'Informe o nome do banco (até 120 caracteres).';
    } elseif (!in_array($form['tipo'], $tiposPermitidos, true)) {
        $erro = 'Selecione um tipo de banco válido.';
    } elseif (!preg_match('/^#[0-9A-Fa-f]{6}$/', $form['cor'])) {
        $erro = 'Escolha uma cor válida.';
    } elseif (strlen($form['descricao']) > 1000) {
        $erro = 'A descrição deve ter até 1000 caracteres.';
    } else {
        $salvouBanco = false;
        try {
            $stmt = $conexao->prepare('INSERT INTO bancos (usuario_id, nome, tipo, cor, descricao) VALUES (?, ?, ?, ?, ?)');
            if ($stmt) {
                $descricao = $form['descricao'] === '' ? null : $form['descricao'];
                $stmt->bind_param('issss', $usuarioId, $form['nome'], $form['tipo'], $form['cor'], $descricao);
                $salvouBanco = $stmt->execute();
            }
        } catch (mysqli_sql_exception $exception) {
            $salvouBanco = false;
        }
        if ($salvouBanco) {
            $_SESSION['mensagem'] = 'Banco cadastrado com sucesso.';
            header('Location: bancos.php');
            exit;
        }
        $erro = 'Não foi possível salvar o banco. Confirme se a tabela bancos foi criada pelo script SQL.';
    }
}

$bancos = [];
$erroTabela = false;
try {
    $stmtBancos = $conexao->prepare('SELECT id, nome, tipo, cor, descricao, criado_em FROM bancos WHERE usuario_id = ? ORDER BY criado_em DESC, id DESC');
    if ($stmtBancos) {
        $stmtBancos->bind_param('i', $usuarioId);
        if ($stmtBancos->execute()) {
            $stmtBancos->bind_result($bancoId, $bancoNome, $bancoTipo, $bancoCor, $bancoDescricao, $bancoCriadoEm);
            while ($stmtBancos->fetch()) {
                $bancos[] = [
                    'id' => $bancoId,
                    'nome' => $bancoNome,
                    'tipo' => $bancoTipo,
                    'cor' => $bancoCor,
                    'descricao' => $bancoDescricao,
                    'criado_em' => $bancoCriadoEm
                ];
            }
        }
    } else {
        $erroTabela = true;
    }
} catch (mysqli_sql_exception $exception) {
    $erroTabela = true;
}

function escaparBanco($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function textoBancoSobreCor($hex) {
    $hex = ltrim($hex, '#');
    $rgb = [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    $luminancia = (0.299 * $rgb[0]) + (0.587 * $rgb[1]) + (0.114 * $rgb[2]);
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
  <title>Balance - Bancos</title>
</head>
<body class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">
  <?php include('./views/header.php'); ?>
  <main class="banks-page">
    <div class="container banks-heading">
      <div><span class="section-eyebrow">Organização financeira</span><h2>Bancos</h2><p>Cadastre e organize suas contas e instituições.</p></div>
    </div>

    <section class="container bank-form-card" aria-labelledby="bank-form-title">
      <h3 id="bank-form-title"><i class="material-icons" aria-hidden="true">add_business</i>Adicionar banco</h3>
      <?php if ($erro !== ''): ?><p class="bank-feedback bank-feedback-error" role="alert"><?= escaparBanco($erro) ?></p><?php endif; ?>
      <?php if ($erroTabela): ?>
        <p class="bank-feedback bank-feedback-error" role="alert">A tabela de bancos ainda não está disponível. Execute <code>database/scripts/bancos.sql</code> no MySQL e atualize esta página.</p>
      <?php endif; ?>
      <?php if (!empty($_SESSION['mensagem'])): ?>
        <p class="bank-feedback bank-feedback-success" role="status"><?= escaparBanco($_SESSION['mensagem']) ?></p>
        <?php unset($_SESSION['mensagem']); ?>
      <?php endif; ?>
      <?php if (!empty($_SESSION['mensagemErro'])): ?>
        <p class="bank-feedback bank-feedback-error" role="alert"><?= escaparBanco($_SESSION['mensagemErro']) ?></p>
        <?php unset($_SESSION['mensagemErro']); ?>
      <?php endif; ?>

      <form method="post" action="bancos.php">
        <input type="hidden" name="csrf_token" value="<?= escaparBanco($_SESSION['csrf_bancos']) ?>">
        <div class="row">
          <div class="input-field col s12 m6">
            <input id="bank-name" name="nome" type="text" maxlength="120" value="<?= escaparBanco($form['nome']) ?>" required>
            <label for="bank-name" class="<?= $form['nome'] !== '' ? 'active' : '' ?>">Nome do banco</label>
          </div>
          <div class="input-field col s12 m6">
            <select id="bank-type" name="tipo" required>
              <?php foreach ($tiposPermitidos as $tipo): ?>
                <option value="<?= escaparBanco($tipo) ?>" <?= $form['tipo'] === $tipo ? 'selected' : '' ?>><?= escaparBanco($tipo) ?></option>
              <?php endforeach; ?>
            </select>
            <label for="bank-type">Tipo de conta</label>
          </div>
          <div class="input-field col s12 m4 bank-color-field">
            <label for="bank-color">Cor do cartão</label>
            <input id="bank-color" name="cor" type="color" value="<?= escaparBanco($form['cor']) ?>" aria-label="Escolher cor do cartão">
          </div>
          <div class="input-field col s12 m8">
            <textarea id="bank-description" name="descricao" class="materialize-textarea" maxlength="1000"><?= escaparBanco($form['descricao']) ?></textarea>
            <label for="bank-description" class="<?= $form['descricao'] !== '' ? 'active' : '' ?>">Descrição (opcional)</label>
          </div>
        </div>
        <div class="bank-form-actions"><button class="btn waves-effect waves-light" type="submit"><i class="material-icons left">save</i>Salvar banco</button></div>
      </form>
    </section>

    <section class="container bank-list-section" aria-labelledby="bank-list-title">
      <div class="bank-list-heading"><h3 id="bank-list-title">Seus bancos</h3><span><?= count($bancos) ?> <?= count($bancos) === 1 ? 'banco cadastrado' : 'bancos cadastrados' ?></span></div>
      <?php if (count($bancos) === 0): ?>
        <div class="bank-empty"><i class="material-icons" aria-hidden="true">account_balance</i><p>Nenhum banco cadastrado ainda.</p></div>
      <?php else: ?>
        <div class="bank-grid">
          <?php foreach ($bancos as $banco):
            $corValida = preg_match('/^#[0-9A-Fa-f]{6}$/', $banco['cor']) ? $banco['cor'] : '#246BFE';
            $corTexto = textoBancoSobreCor($corValida);
          ?>
            <article class="bank-card" style="--bank-card-color: <?= escaparBanco($corValida) ?>; --bank-card-text: <?= escaparBanco($corTexto) ?>;">
              <div class="bank-card-top"><span class="bank-card-icon"><i class="material-icons" aria-hidden="true">account_balance</i></span><span class="bank-card-type"><?= escaparBanco($banco['tipo']) ?></span></div>
              <h4><?= escaparBanco($banco['nome']) ?></h4>
              <?php if (!empty($banco['descricao'])): ?><p class="bank-card-description"><?= nl2br(escaparBanco($banco['descricao'])) ?></p><?php endif; ?>
              <div class="bank-card-date"><i class="material-icons" aria-hidden="true">event</i>Criado em <?= escaparBanco(date('d/m/Y', strtotime($banco['criado_em']))) ?></div>
              <div class="bank-card-actions">
                <form method="post" action="bancos.php" onsubmit="return confirm('Excluir este banco? Essa ação não pode ser desfeita.');">
                  <input type="hidden" name="csrf_token" value="<?= escaparBanco($_SESSION['csrf_bancos']) ?>">
                  <input type="hidden" name="acao" value="excluir">
                  <input type="hidden" name="banco_id" value="<?= (int) $banco['id'] ?>">
                  <button class="btn btn-danger bank-card-delete waves-effect waves-light" type="submit" aria-label="Excluir banco <?= escaparBanco($banco['nome']) ?>" title="Excluir banco"><i class="material-icons" aria-hidden="true">delete</i></button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
  <?php include('./views/modal.php'); ?>
  <?php include('./views/footer.php'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      M.Sidenav.init(document.querySelectorAll('.sidenav'));
      M.Modal.init(document.querySelectorAll('.modal'));
    });
  </script>
</body>
</html>
