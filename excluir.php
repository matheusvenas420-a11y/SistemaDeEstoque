
<?php
require_once 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php?erro=id');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        header('Location: index.php?excluido=1');
    } else {
        header('Location: index.php?erro=nao_encontrado');
    }
    exit;

} catch (PDOException $e) {
    header('Location: index.php?erro=exclusao');
    exit;
}
