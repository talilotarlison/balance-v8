<?php
session_start();
if (empty($_SESSION['idUser'])) {
    header('Location: ../index.php');
    exit;
}

require_once('../database/conexao.php');
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$usuarioId = (int) $_SESSION['idUser'];

if ($id === false || $id < 1) {
    $_SESSION['mensagem'] = 'ID de lançamento inválido.';
} else {
    try {
        $stmtDelete = $conexao->prepare('DELETE FROM fluxocaixa WHERE id = ? AND usuario_id = ?');
        $stmtDelete->bind_param('ii', $id, $usuarioId);
        if ($stmtDelete->execute() && $stmtDelete->affected_rows === 1) {
            $_SESSION['mensagem'] = 'Lançamento excluído com sucesso.';
        } else {
            $_SESSION['mensagem'] = 'Lançamento não encontrado na sua conta.';
        }
    } catch (Throwable $exception) {
        $_SESSION['mensagem'] = 'Não foi possível excluir este lançamento.';
    }
}

header('Location: lancamentos.php');
exit;
?>
