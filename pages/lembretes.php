<?php
session_start();

if (empty($_SESSION['idUser'])) {
    header('Location: ../index.php');
    exit;
}

require_once('../database/conexao.php');

$usuarioId = (int) $_SESSION['idUser'];
$csrfKey = 'csrf_lembretes';
if (empty($_SESSION[$csrfKey])) {
    $_SESSION[$csrfKey] = bin2hex(random_bytes(32));
}

$colunasNecessarias = ['id', 'usuario_id', 'nome', 'descricao', 'email', 'telefone', 'url', 'data_prevista', 'visto'];
$colunasLinks = [];
try {
    $resultadoColunas = $conexao->query('SHOW COLUMNS FROM links');
    if ($resultadoColunas) {
        while ($coluna = $resultadoColunas->fetch_assoc()) $colunasLinks[] = $coluna['Field'];
    }
} catch (Throwable $exception) {
    $colunasLinks = [];
}
$tabelaPronta = count(array_diff($colunasNecessarias, $colunasLinks)) === 0;

$erro = '';
$nomeFormulario = '';
$descricaoFormulario = '';
$emailFormulario = '';
$telefoneFormulario = '';
$urlFormulario = '';
$dataFormulario = '';
$lembretes = [];

function escaparLembrete($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function redirecionarLembretes() {
    header('Location: lembretes.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenPostado = $_POST['csrf_token'] ?? '';
    if (!$tabelaPronta) {
        $erro = 'A tabela links precisa da migração database/scripts/lembretes.sql.';
    } elseif (!is_string($tokenPostado) || !hash_equals($_SESSION[$csrfKey], $tokenPostado)) {
        $erro = 'O formulário expirou. Atualize a página e tente novamente.';
    } elseif (($_POST['acao'] ?? '') === 'excluir') {
        $idLembrete = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($idLembrete === false || $idLembrete < 1) {
            $_SESSION['mensagemErro'] = 'Lembrete inválido.';
        } else {
            try {
                $stmtExcluir = $conexao->prepare('DELETE FROM links WHERE id = ? AND usuario_id = ?');
                $stmtExcluir->bind_param('ii', $idLembrete, $usuarioId);
                if ($stmtExcluir->execute() && $stmtExcluir->affected_rows === 1) {
                    $_SESSION['mensagem'] = 'Lembrete excluído com sucesso.';
                } else {
                    $_SESSION['mensagemErro'] = 'Lembrete não encontrado na sua conta.';
                }
            } catch (Throwable $exception) {
                $_SESSION['mensagemErro'] = 'Não foi possível excluir o lembrete.';
            }
        }
        redirecionarLembretes();
    } elseif (($_POST['acao'] ?? '') === 'concluir') {
        $idLembrete = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($idLembrete === false || $idLembrete < 1) {
            $_SESSION['mensagemErro'] = 'Lembrete inválido.';
        } else {
            try {
                $stmtConcluir = $conexao->prepare('UPDATE links SET visto = IF(visto = 1, 0, 1) WHERE id = ? AND usuario_id = ?');
                $stmtConcluir->bind_param('ii', $idLembrete, $usuarioId);
                if ($stmtConcluir->execute() && $stmtConcluir->affected_rows === 1) {
                    $_SESSION['mensagem'] = 'Status do lembrete atualizado.';
                } else {
                    $_SESSION['mensagemErro'] = 'Lembrete não encontrado na sua conta.';
                }
            } catch (Throwable $exception) {
                $_SESSION['mensagemErro'] = 'Não foi possível atualizar o lembrete.';
            }
        }
        redirecionarLembretes();
    } elseif (($_POST['acao'] ?? '') === 'criar') {
        $nomeFormulario = trim((string) ($_POST['nome'] ?? ''));
        $descricaoFormulario = trim((string) ($_POST['descricao'] ?? ''));
        $emailFormulario = trim((string) ($_POST['email'] ?? ''));
        $telefoneFormulario = trim((string) ($_POST['telefone'] ?? ''));
        $urlFormulario = trim((string) ($_POST['url'] ?? ''));
        $dataFormulario = trim((string) ($_POST['data_prevista'] ?? ''));

        if ($nomeFormulario === '' || strlen($nomeFormulario) > 255) {
            $erro = 'Informe o nome do lembrete com até 255 caracteres.';
        } elseif (strlen($descricaoFormulario) > 2000) {
            $erro = 'A descrição deve ter no máximo 2.000 caracteres.';
        } elseif ($emailFormulario !== '' && (strlen($emailFormulario) > 255 || !filter_var($emailFormulario, FILTER_VALIDATE_EMAIL))) {
            $erro = 'Informe um email válido ou deixe o campo vazio.';
        } elseif ($telefoneFormulario !== '' && (strlen($telefoneFormulario) > 30 || !preg_match('/^[0-9+().\s-]+$/', $telefoneFormulario))) {
            $erro = 'Informe um telefone válido com até 30 caracteres.';
        } elseif ($urlFormulario !== '' && (strlen($urlFormulario) > 255 || !filter_var($urlFormulario, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//i', $urlFormulario))) {
            $erro = 'Informe uma URL completa iniciada por http:// ou https://.';
        } else {
            $dataValidada = DateTime::createFromFormat('!Y-m-d', $dataFormulario);
            $errosData = DateTime::getLastErrors();
            if (!$dataValidada || ($errosData !== false && ($errosData['warning_count'] > 0 || $errosData['error_count'] > 0)) || $dataValidada->format('Y-m-d') !== $dataFormulario) {
                $erro = 'Informe uma data prevista válida.';
            }
        }

        if ($erro === '') {
            $emailDb = $emailFormulario === '' ? null : $emailFormulario;
            $telefoneDb = $telefoneFormulario === '' ? null : $telefoneFormulario;
            $urlDb = $urlFormulario;
            $dataDb = $dataValidada->format('Y-m-d');
            $visto = 0;
            try {
                $stmtCriar = $conexao->prepare('INSERT INTO links (usuario_id, nome, descricao, email, telefone, url, data_prevista, visto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                $stmtCriar->bind_param('issssssi', $usuarioId, $nomeFormulario, $descricaoFormulario, $emailDb, $telefoneDb, $urlDb, $dataDb, $visto);
                if ($stmtCriar->execute()) {
                    $_SESSION['mensagem'] = 'Lembrete cadastrado com sucesso.';
                    redirecionarLembretes();
                }
                $erro = 'Não foi possível salvar o lembrete.';
            } catch (Throwable $exception) {
                $erro = 'Não foi possível salvar o lembrete. Verifique a estrutura da tabela links.';
            }
        }
    }
}

if ($tabelaPronta) {
    try {
        $stmtLembretes = $conexao->prepare('SELECT id, nome, descricao, email, telefone, url, data_prevista, visto FROM links WHERE usuario_id = ? ORDER BY visto ASC, data_prevista ASC, id DESC');
        $stmtLembretes->bind_param('i', $usuarioId);
        if ($stmtLembretes->execute()) {
            $stmtLembretes->bind_result($lembreteId, $lembreteNome, $lembreteDescricao, $lembreteEmail, $lembreteTelefone, $lembreteUrl, $lembreteData, $lembreteVisto);
            while ($stmtLembretes->fetch()) {
                $lembretes[] = ['id' => (int) $lembreteId, 'nome' => $lembreteNome, 'descricao' => $lembreteDescricao, 'email' => $lembreteEmail, 'telefone' => $lembreteTelefone, 'url' => $lembreteUrl, 'data' => $lembreteData, 'concluido' => (bool) $lembreteVisto];
            }
        }
    } catch (Throwable $exception) {
        $erro = 'Não foi possível carregar os lembretes.';
    }
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
  <link rel="stylesheet" href="../assets/css/styles.css?v=20261107-26">
  <title>Balance - Lembretes</title>
</head>
<body class="<?= (int) ($_SESSION['temaUser'] ?? 0) === 1 ? 'theme-dark' : 'theme-light' ?>">
  <?php include('./views/header.php'); ?>
  <main class="banks-page reminders-page">
    <div class="container banks-heading">
      <div><span class="section-eyebrow">Organização pessoal</span><h2>Lembretes</h2><p>Acompanhe tarefas e compromissos futuros.</p></div>
    </div>

    <section class="container bank-form-card" aria-labelledby="reminder-form-title">
      <h3 id="reminder-form-title"><i class="material-icons" aria-hidden="true">add_alert</i>Novo lembrete</h3>
      <?php if (!$tabelaPronta): ?>
        <p class="bank-feedback bank-feedback-error" role="alert">A tabela <code>links</code> ainda precisa ser atualizada. Faça backup e execute <code>database/scripts/lembretes.sql</code>.</p>
      <?php endif; ?>
      <?php if ($erro !== ''): ?><p class="bank-feedback bank-feedback-error" role="alert"><?= escaparLembrete($erro) ?></p><?php endif; ?>
      <?php if (!empty($_SESSION['mensagem'])): ?><p class="bank-feedback bank-feedback-success" role="status"><?= escaparLembrete($_SESSION['mensagem']) ?></p><?php unset($_SESSION['mensagem']); endif; ?>
      <?php if (!empty($_SESSION['mensagemErro'])): ?><p class="bank-feedback bank-feedback-error" role="alert"><?= escaparLembrete($_SESSION['mensagemErro']) ?></p><?php unset($_SESSION['mensagemErro']); endif; ?>

      <form method="post" action="lembretes.php" class="reminder-form">
        <input type="hidden" name="csrf_token" value="<?= escaparLembrete($_SESSION[$csrfKey]) ?>">
        <input type="hidden" name="acao" value="criar">
        <div class="row">
          <div class="input-field col s12 m6">
            <input id="reminder-name" name="nome" type="text" maxlength="255" value="<?= escaparLembrete($nomeFormulario) ?>" required <?= !$tabelaPronta ? 'disabled' : '' ?>>
            <label for="reminder-name" class="<?= $nomeFormulario !== '' ? 'active' : '' ?>">Nome do lembrete</label>
          </div>
          <div class="input-field col s12 m6">
            <input id="reminder-date" name="data_prevista" type="date" value="<?= escaparLembrete($dataFormulario) ?>" required <?= !$tabelaPronta ? 'disabled' : '' ?>>
            <label for="reminder-date" class="active">Data prevista</label>
          </div>
          <div class="input-field col s12">
            <textarea id="reminder-description" name="descricao" class="materialize-textarea" maxlength="2000" <?= !$tabelaPronta ? 'disabled' : '' ?>><?= escaparLembrete($descricaoFormulario) ?></textarea>
            <label for="reminder-description" class="<?= $descricaoFormulario !== '' ? 'active' : '' ?>">Descrição (opcional)</label>
          </div>
          <div class="input-field col s12 m6">
            <input id="reminder-email" name="email" type="email" maxlength="255" value="<?= escaparLembrete($emailFormulario) ?>" <?= !$tabelaPronta ? 'disabled' : '' ?>>
            <label for="reminder-email" class="<?= $emailFormulario !== '' ? 'active' : '' ?>">Email (opcional)</label>
          </div>
          <div class="input-field col s12 m6">
            <input id="reminder-phone" name="telefone" type="tel" maxlength="30" value="<?= escaparLembrete($telefoneFormulario) ?>" <?= !$tabelaPronta ? 'disabled' : '' ?>>
            <label for="reminder-phone" class="<?= $telefoneFormulario !== '' ? 'active' : '' ?>">Telefone (opcional)</label>
          </div>
          <div class="input-field col s12">
            <input id="reminder-url" name="url" type="url" maxlength="255" value="<?= escaparLembrete($urlFormulario) ?>" placeholder="https://" <?= !$tabelaPronta ? 'disabled' : '' ?>>
            <label for="reminder-url" class="<?= $urlFormulario !== '' ? 'active' : '' ?>">URL de acesso (opcional)</label>
          </div>
        </div>
        <div class="bank-form-actions"><button class="btn waves-effect waves-light" type="submit" <?= !$tabelaPronta ? 'disabled' : '' ?>><i class="material-icons left">save</i>Salvar lembrete</button></div>
      </form>
    </section>

    <section class="container bank-list-section reminders-list-section" aria-labelledby="reminders-list-title">
      <div class="bank-list-heading"><h3 id="reminders-list-title">Seus lembretes</h3><span><?= count($lembretes) ?> <?= count($lembretes) === 1 ? 'lembrete' : 'lembretes' ?></span></div>
      <?php if (!$tabelaPronta || !$lembretes): ?>
        <div class="bank-empty"><i class="material-icons" aria-hidden="true">notifications_none</i><p><?= $tabelaPronta ? 'Nenhum lembrete cadastrado ainda.' : 'Os lembretes aparecerão aqui após a migração do banco.' ?></p></div>
      <?php else: ?>
        <div class="reminder-grid">
          <?php foreach ($lembretes as $lembrete): ?>
            <article class="reminder-card <?= $lembrete['concluido'] ? 'is-completed' : '' ?>">
              <div class="reminder-card-heading">
                <span class="reminder-status <?= $lembrete['concluido'] ? 'completed' : 'pending' ?>"><i class="material-icons" aria-hidden="true"><?= $lembrete['concluido'] ? 'check_circle' : 'schedule' ?></i><?= $lembrete['concluido'] ? 'Concluído' : 'Pendente' ?></span>
                <span class="reminder-id">#<?= $lembrete['id'] ?></span>
                <time datetime="<?= escaparLembrete($lembrete['data']) ?>"><i class="material-icons" aria-hidden="true">event</i><?= escaparLembrete(date('d/m/Y', strtotime($lembrete['data']))) ?></time>
              </div>
              <h4><?= escaparLembrete($lembrete['nome']) ?></h4>
              <?php if (!empty($lembrete['descricao'])): ?><p class="reminder-description"><?= nl2br(escaparLembrete($lembrete['descricao'])) ?></p><?php endif; ?>
              <?php if (!empty($lembrete['email'])): ?><p><i class="material-icons" aria-hidden="true">email</i><?= escaparLembrete($lembrete['email']) ?></p><?php endif; ?>
              <?php if (!empty($lembrete['telefone'])): ?><p><i class="material-icons" aria-hidden="true">phone</i><?= escaparLembrete($lembrete['telefone']) ?></p><?php endif; ?>
              <?php if (!empty($lembrete['url'])): ?><p><i class="material-icons" aria-hidden="true">link</i><a href="<?= escaparLembrete($lembrete['url']) ?>" target="_blank" rel="noopener noreferrer">Abrir link</a></p><?php endif; ?>
              <div class="reminder-actions">
              <form method="post" action="lembretes.php" class="reminder-status-form">
                <input type="hidden" name="csrf_token" value="<?= escaparLembrete($_SESSION[$csrfKey]) ?>">
                <input type="hidden" name="acao" value="concluir">
                <input type="hidden" name="id" value="<?= $lembrete['id'] ?>">
                <button class="btn <?= $lembrete['concluido'] ? 'btn-secondary' : 'btn-success' ?>" type="submit"><i class="material-icons left" aria-hidden="true"><?= $lembrete['concluido'] ? 'undo' : 'check' ?></i><?= $lembrete['concluido'] ? 'Reabrir' : 'Concluir' ?></button>
              </form>
              <form method="post" action="lembretes.php" class="reminder-delete-form" onsubmit="return confirm('Excluir este lembrete? Essa ação não pode ser desfeita.');">
                <input type="hidden" name="csrf_token" value="<?= escaparLembrete($_SESSION[$csrfKey]) ?>">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" value="<?= $lembrete['id'] ?>">
                <button class="btn btn-danger reminder-delete-button" type="submit" aria-label="Excluir lembrete <?= escaparLembrete($lembrete['nome']) ?>" title="Excluir lembrete"><i class="material-icons" aria-hidden="true">delete</i></button>
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
