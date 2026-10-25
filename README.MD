# Sistema de Controle de Estoque

Aplicação web simples para cadastrar e gerenciar produtos usando PHP e MariaDB. O projeto foi desenvolvido para uma atividade prática de desenvolvimento de sistemas.

## Funcionalidades

- Cadastrar, editar e excluir produtos.
- Digitar um código de produto ou gerar um automaticamente.
- Impedir que dois produtos usem o mesmo código.
- Pesquisar produtos por ID, nome ou código.
- Exibir 10 produtos por página, com navegação entre páginas.
- Mostrar mensagens após operações de cadastro, edição e exclusão.

## Tecnologias

- PHP com PDO para acesso ao banco de dados.
- MariaDB para armazenar os produtos.
- HTML e CSS para as páginas e estilos.
- XAMPP para executar o Apache e o MariaDB localmente.
- DBeaver para criar e consultar o banco de dados.

## Requisitos

- XAMPP instalado, com Apache e MySQL/MariaDB.
- DBeaver instalado para executar o script de criação do banco.
- A extensão PDO MySQL habilitada no PHP do XAMPP.

## Instalação e execução

### 1. Colocar o projeto no diretório do XAMPP

Copie a pasta do projeto para:

```text
C:\xampp\htdocs\SistemaDeEstoque
```

O arquivo inicial do sistema é `index.php`.

### 2. Iniciar os serviços

Abra o Painel de Controle do XAMPP e inicie:

1. **Apache**
2. **MySQL** (o serviço de banco de dados MariaDB do XAMPP)

### 3. Criar o banco e a tabela

No DBeaver, conecte ao MariaDB local. Abra um editor SQL nessa conexão e execute:

```sql
CREATE DATABASE sistema_estoque
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sistema_estoque;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL DEFAULT 0
);
```

`codigo` tem a regra `UNIQUE`, que impede códigos repetidos no banco. Se o banco e a tabela já existirem, não execute novamente o comando `CREATE DATABASE` ou `CREATE TABLE`.

### 4. Conferir a conexão do PHP

Abra `config/conexao.php` e confira os dados usados para conectar ao banco:

```php
$host = '127.0.0.1';
$banco = 'sistema_estoque';
$usuario = 'root';
$senha = '';
```

Esses são os valores padrão usados neste projeto no XAMPP. Se o seu MariaDB tiver outra porta, usuário ou senha, ajuste os dados nesse arquivo.

### 5. Abrir o sistema

No navegador, acesse:

```text
http://localhost/SistemaDeEstoque/
```

Se o Apache estiver configurado para usar a porta 81, acesse:

```text
http://localhost:81/SistemaDeEstoque/
```

## Como usar

1. Clique em **Cadastrar Produto**.
2. Digite um código ou clique em **Gerar código**.
3. Preencha nome, preço e quantidade e salve.
4. Na listagem, use a busca por ID, nome ou código.
5. Use **Anterior** e **Próxima** para navegar pelos resultados; cada página mostra até 10 produtos.
6. Use **Editar** para alterar um produto ou **Excluir** para removê-lo.

Se um código já estiver associado a outro produto, o sistema informa que ele está em uso. Os produtos são armazenados no banco de dados e continuam disponíveis depois que o navegador é fechado.

## Estrutura dos arquivos principais

- `index.php`: lista, pesquisa e pagina os produtos.
- `cadastro.php`: apresenta e processa o formulário de cadastro.
- `editar.php`: apresenta e processa o formulário de edição.
- `excluir.php`: remove um produto.
- `config/conexao.php`: configura a conexão com o MariaDB.
- `style.css`: define a aparência das páginas.

## Verificação rápida

Para conferir se a instalação está funcionando:

1. Cadastre um produto e veja se ele aparece na listagem.
2. Tente cadastrar outro produto com o mesmo código e confirme que o sistema impede a duplicidade.
3. Pesquise o produto por ID, nome e código.
4. Se houver mais de 10 produtos, navegue para a próxima página.
5. Edite um produto e depois exclua um produto de teste.