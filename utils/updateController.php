<?php
include_once __DIR__ . '/../database/conexao.php';

$arrItem = [];
$bancosFormulario = [];
$categoriasFormulario = [];
$idItemSolicitado = filter_var($_GET['id'] ?? $_GET['view'] ?? null, FILTER_VALIDATE_INT);
$ehVisualizacao = isset($_GET['view']);
$usuarioLancamentoId = (int) ($_SESSION['idUser'] ?? 0);

if ($idItemSolicitado !== false && $idItemSolicitado !== null && $idItemSolicitado > 0 && $usuarioLancamentoId > 0) {
    try {
        $stmtItem = $conexao->prepare('SELECT f.tipo, f.nome, f.preco, f.disc, f.data_lancamento, f.banco_id, f.categoria_id, f.data, b.nome, c.nome FROM fluxocaixa f LEFT JOIN bancos b ON b.id = f.banco_id AND b.usuario_id = f.usuario_id LEFT JOIN categorias c ON c.id = f.categoria_id AND c.usuario_id = f.usuario_id WHERE f.id = ? AND f.usuario_id = ?');
        $stmtItem->bind_param('ii', $idItemSolicitado, $usuarioLancamentoId);
        $stmtItem->execute();
        $stmtItem->store_result();
        $stmtItem->bind_result($itemTipo, $itemNome, $itemPreco, $itemDescricao, $itemDataLancamento, $itemBancoId, $itemCategoriaId, $itemDataCriacao, $itemBancoNome, $itemCategoriaNome);
        if ($stmtItem->fetch()) {
            $arrItem = [0 => $itemTipo, 1 => $itemNome, 2 => $itemPreco, 3 => $itemDescricao, 4 => $itemDataLancamento, 5 => $itemBancoId, 6 => $itemCategoriaId, 7 => $itemBancoNome, 8 => $itemCategoriaNome, 9 => $itemDataCriacao];
        }
    } catch (Throwable $exception) {
        $arrItem = [];
    }

    if (!$ehVisualizacao && isset($_SESSION['idUser'])) {
        $usuarioFormularioId = (int) $_SESSION['idUser'];
        if (empty($_SESSION['csrf_editar_lancamento'])) {
            $_SESSION['csrf_editar_lancamento'] = bin2hex(random_bytes(32));
        }
        try {
            $stmtBancosEdicao = $conexao->prepare('SELECT id, nome FROM bancos WHERE usuario_id = ? ORDER BY nome');
            if ($stmtBancosEdicao) {
                $stmtBancosEdicao->bind_param('i', $usuarioFormularioId);
                $stmtBancosEdicao->execute();
                $stmtBancosEdicao->bind_result($idBancoEdicao, $nomeBancoEdicao);
                while ($stmtBancosEdicao->fetch()) {
                    $bancosFormulario[] = ['id' => $idBancoEdicao, 'nome' => $nomeBancoEdicao];
                }
            }
        } catch (Throwable $exception) {
            $bancosFormulario = [];
        }
        try {
            $stmtCategoriasEdicao = $conexao->prepare('SELECT id, nome, tipo FROM categorias WHERE usuario_id = ? ORDER BY nome');
            if ($stmtCategoriasEdicao) {
                $stmtCategoriasEdicao->bind_param('i', $usuarioFormularioId);
                $stmtCategoriasEdicao->execute();
                $stmtCategoriasEdicao->bind_result($idCategoriaEdicao, $nomeCategoriaEdicao, $tipoCategoriaEdicao);
                while ($stmtCategoriasEdicao->fetch()) {
                    $categoriasFormulario[] = ['id' => $idCategoriaEdicao, 'nome' => $nomeCategoriaEdicao, 'tipo' => $tipoCategoriaEdicao];
                }
            }
        } catch (Throwable $exception) {
            $categoriasFormulario = [];
        }
    }
}
?>
