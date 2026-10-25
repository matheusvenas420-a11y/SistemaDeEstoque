<?php
require_once 'config/conexao.php';

function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit('Produto inválido.');
}

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    exit('Produto não encontrado.');
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $precoTexto = str_replace(',', '.', $_POST['preco'] ?? '');
    $quantidadeTexto = $_POST['quantidade'] ?? '';

    $precoValido = is_numeric($precoTexto)
        && is_finite((float) $precoTexto)
        && (float) $precoTexto >= 0;

    $quantidade = filter_var($quantidadeTexto, FILTER_VALIDATE_INT);

    if (
        $codigo === '' || strlen($codigo) > 30 ||
        $nome === '' || strlen($nome) > 100 ||
        !$precoValido || (float) $precoTexto > 99999999.99 ||
        $quantidade === false || $quantidade < 0
    ) {
        $mensagem = 'Preencha os campos corretamente.';
    } else {
        $verificarCodigo = $pdo->prepare(
            "SELECT id FROM produtos WHERE codigo = ? AND id <> ?"
        );
        $verificarCodigo->execute([$codigo, $id]);

        if ($verificarCodigo->fetch()) {
            $mensagem = 'Esse código já está sendo usado por outro produto.';
        } else {
            try {
                $sql = "UPDATE produtos
                        SET codigo = ?, nome = ?, descricao = ?,
                            preco = ?, quantidade = ?
                        WHERE id = ?";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $codigo,
                    $nome,
                    $descricao,
                    $precoTexto,
                    $quantidade,
                    $id
                ]);

                header('Location: index.php?editado=1');
                exit;
            } catch (PDOException $e) {
                $mensagem = $e->getCode() === '23000'
                    ? 'Esse código já está sendo usado por outro produto.'
                    : 'Não foi possível atualizar o produto.';
            }
        }
    }

    $produto['codigo'] = $codigo;
    $produto['nome'] = $nome;
    $produto['descricao'] = $descricao;
    $produto['preco'] = $precoTexto;
    $produto['quantidade'] = $quantidadeTexto;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="form-container">
    <h1>Editar Produto</h1>

    <?php if ($mensagem !== ''): ?>
        <div class="mensagem-erro">
            <?= escapar($mensagem) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="codigo">Código do produto</label>
            <input type="text" id="codigo" name="codigo"
                   maxlength="30" required
                   placeholder="Digite o código do produto"
                   value="<?= escapar($produto['codigo']) ?>">
        </div>

        <div class="form-group">
            <label for="nome">Nome do produto</label>
            <input type="text" id="nome" name="nome"
                   maxlength="100" required
                   placeholder="Digite o nome do produto"
                   value="<?= escapar($produto['nome']) ?>">
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao"
                      placeholder="Descreva o produto"><?= escapar($produto['descricao']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="preco">Preço (R$)</label>
            <input type="text" id="preco" name="preco"
                   inputmode="decimal" required
                   placeholder="Ex.: 29,90"
                   value="<?= escapar($produto['preco']) ?>">
        </div>

        <div class="form-group">
            <label for="quantidade">Quantidade em estoque</label>
            <input type="number" id="quantidade" name="quantidade"
                   min="0" required
                   placeholder="Digite a quantidade"
                   value="<?= escapar($produto['quantidade']) ?>">
        </div>

        <div class="form-actions">
            <button type="submit">Salvar alterações</button>
            <a href="index.php" class="btn-voltar">Voltar</a>
        </div>
    </form>
</main>

</body>
</html>
