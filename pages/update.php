<?php
session_start();

function retornarEdicaoLancamento($mensagem) {
    $_SESSION['mensagem'] = $mensagem;
    header('Location: lancamentos.php');
    exit;
}

if (empty($_SESSION['idUser'])) {
    header('Location: ../index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: lancamentos.php');
    exit;
}

require_once('../database/conexao.php');

$usuarioId = (int) $_SESSION['idUser'];
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$tipoPostado = $_POST['tipo'] ?? '';
$nomePostado = $_POST['nome'] ?? '';
$descricaoPostada = $_POST['desc'] ?? '';
$valor = $_POST['preco'] ?? '';
$dataPostada = $_POST['data_lancamento'] ?? '';
$tipo = is_string($tipoPostado) ? trim($tipoPostado) : '';
$nome = is_string($nomePostado) ? trim($nomePostado) : '';
$descricao = is_string($descricaoPostada) ? trim($descricaoPostada) : '';
$dataLancamento = is_string($dataPostada) ? trim($dataPostada) : '';
$bancoId = filter_var($_POST['banco_id'] ?? null, FILTER_VALIDATE_INT);
$categoriaId = filter_var($_POST['categoria_id'] ?? null, FILTER_VALIDATE_INT);
$csrfToken = $_POST['csrf_token'] ?? '';

if ($id === false || $id < 1) {
    retornarEdicaoLancamento('Lançamento inválido.');
}
if (!is_string($csrfToken) || empty($_SESSION['csrf_editar_lancamento']) || !hash_equals($_SESSION['csrf_editar_lancamento'], $csrfToken)) {
    retornarEdicaoLancamento('O formulário expirou. Abra novamente a edição e tente mais uma vez.');
}
if (!in_array($tipo, ['Entrada', 'Saida', 'Agendado'], true)) {
    retornarEdicaoLancamento('Selecione um tipo de transferência válido.');
}
if ($nome === '' || strlen($nome) > 255 || $descricao === '' || strlen($descricao) > 255) {
    retornarEdicaoLancamento('Preencha o nome e a descrição com até 255 caracteres.');
}
if (!is_numeric($valor) || !is_finite((float) $valor) || (float) $valor <= 0) {
    retornarEdicaoLancamento('Informe um valor maior que zero.');
}
$dataValidada = DateTime::createFromFormat('!Y-m-d', $dataLancamento);
$errosData = DateTime::getLastErrors();
if (!$dataValidada || ($errosData !== false && ($errosData['warning_count'] > 0 || $errosData['error_count'] > 0)) || $dataValidada->format('Y-m-d') !== $dataLancamento) {
    retornarEdicaoLancamento('Informe uma data de lançamento válida.');
}
if ($bancoId === false || $bancoId < 1) {
    retornarEdicaoLancamento('Selecione um banco cadastrado.');
}

try {
    $stmtBanco = $conexao->prepare('SELECT id FROM bancos WHERE id = ? AND usuario_id = ?');
    $stmtBanco->bind_param('ii', $bancoId, $usuarioId);
    $stmtBanco->execute();
    $stmtBanco->store_result();
    if ($stmtBanco->num_rows !== 1) {
        retornarEdicaoLancamento('O banco selecionado não pertence à sua conta.');
    }

    if ($tipo === 'Entrada' || $tipo === 'Saida') {
        if ($categoriaId === false || $categoriaId < 1) {
            retornarEdicaoLancamento('Selecione uma categoria compatível com o tipo de lançamento.');
        }
        $stmtCategoria = $conexao->prepare('SELECT id FROM categorias WHERE id = ? AND usuario_id = ? AND tipo = ?');
        $stmtCategoria->bind_param('iis', $categoriaId, $usuarioId, $tipo);
        $stmtCategoria->execute();
        $stmtCategoria->store_result();
        if ($stmtCategoria->num_rows !== 1) {
            retornarEdicaoLancamento('A categoria selecionada não corresponde ao tipo de lançamento.');
        }
    } else {
        $categoriaId = null;
    }

    $preco = (float) $valor;
    if ($tipo === 'Saida') {
        $preco = -abs($preco);
    }
    $nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
    $descricao = htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8');
    $dataLancamentoSql = $dataValidada->format('Y-m-d');
    $stmtUpdate = $conexao->prepare('UPDATE fluxocaixa SET tipo = ?, nome = ?, preco = ?, disc = ?, banco_id = ?, categoria_id = ?, data_lancamento = ? WHERE id = ?');
    $stmtUpdate->bind_param('ssdsiisi', $tipo, $nome, $preco, $descricao, $bancoId, $categoriaId, $dataLancamentoSql, $id);
    $salvou = $stmtUpdate->execute();
} catch (Throwable $exception) {
    $salvou = false;
}

$_SESSION['mensagem'] = $salvou
    ? 'Lançamento atualizado com sucesso.'
    : 'Não foi possível atualizar. Confirme se os scripts de bancos, categorias e lançamentos foram aplicados.';
header('Location: lancamentos.php');
exit;
?>
