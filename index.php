<?php

header('Content-Type: application/json; charset=utf-8');

require_once("_autoload.php");

// TESTE

$crud = new App\Models\Crud;

$path = explode("/", @$_GET["path"]);
$http_method = $_SERVER['REQUEST_METHOD'];


if ($http_method == "GET") {

    /** 
     * Dados de um produto ou fornecedor específico
     * Caminho: /produto/{id_produto} ou /fornecedor/{id_fornecedor}
     */

    if (preg_match('/^\w+\/\d+$/', $_GET["path"])) {

        $result = $crud->Read(
            $table = @$path[0],
            $conditions = ["id" => @$path[1]],
            $fields = ["id", "nome", "cadastro"]
        );
    }

    /** 
     * Dados de todos os produtos ou fornecedores
     * Caminho: /produtos ou /fornecedores
     */

    if (ctype_alpha($_GET["path"])) {

        if ($path[0] == "produtos") {
            $table = "produto";
        }
        if ($path[0] == "fornecedores") {
            $table = "fornecedor";
        }

        if (
            $http_method == "GET"
            && (@$path[0] == "produtos" || @$path[0] == "fornecedores")
        ) {
            $result = $crud->Read(
                $table = $table,
                $conditions = null,
                $fields = ["id", "nome", "cadastro"]
            );
        }
    }

    /** 
     * Produtos que um fornecedor especifico fornece
     * Caminho: /fornecedor/{id_fornecedor}/produtos
     */

    if (preg_match('/^fornecedor\/\d+\/produtos$/', $_GET["path"])) {

        $result = $crud->Read(
            $table = "fornecedor_produto",
            $conditions = ["id_fornecedor" => @$path[1]],
            $fields = ["id_produto", "valor", "cadastro"]
        );
    }

    /** 
     * Fornecedores que fornecem um produto específico
     * Caminho: /produto/{id_produto}/fornecedores
     */

    if (preg_match('/^produto\/\d+\/fornecedores$/', $_GET["path"])) {

        $result = $crud->Read(
            $table = "fornecedor_produto",
            $conditions = ["id_produto" => @$path[1]],
            $fields = ["id_fornecedor", "valor", "cadastro"]
        );
    }
}

if ($http_method == "POST") {

    /** 
     * Criar um novo produto ou um novo fornecedor
     * Caminho: /produto/novo ou /fornecedor/novo
     */

    if (preg_match('/^.+\/novo$/', @$_GET["path"])) {

        if (@$_POST["nome"] !== '' && isset($_POST["nome"])) {

            $create = $crud->Create(
                $table = @$path[0],
                $values = ["nome" => @$_POST["nome"]]
            );

            if ($create !== 0) {
                $result = [
                    "id" => $create,
                    "tabela" => @$path[0],
                    "mensagem" => "Item adicionado com sucesso"
                ];
            } else {
                $result = [
                    "codigo" => 3,
                    "mensagem" => "Falha ao adicionar item"
                ];
            }
        } else {

            $result = [
                "codigo" => 3,
                "mensagem" => "Falha ao adicionar item. O campo 'nome' não pode ficar em branco"
            ];
        }
    }

    /** 
     * Editar um produto ou um fornecedor
     * Caminho: /produto/{id_produto}/editar ou /fornecedor/{id_fornecedor}/editar
     */

    if (preg_match('/^[A-Za-z]+\/[0-9]+\/editar$/', @$_GET["path"])) {

        if (@$_POST["nome"] !== '' && isset($_POST["nome"])) {

            $update = $crud->Update(
                $table = @$path[0],
                $values = ["nome" => @$_POST["nome"]],
                $conditions = ["id" => $path[1]]
            );

            if ($update !== 0) {
                $result = [
                    "id" => $path[1],
                    "tabela" => @$path[0],
                    "mensagem" => "Item editado com sucesso"
                ];
            } else {
                $result = [
                    "codigo" => 3,
                    "mensagem" => "Falha ao editar item"
                ];
            }
        } else {

            $result = [
                "codigo" => 3,
                "mensagem" => "Falha ao adicionar item. O campo 'nome' não pode ficar em branco"
            ];
        }
    }

    /** 
     * Excluir um produto ou um fornecedor
     * Caminho: /produto/{id_produto}/excluir ou /fornecedor/{id_fornecedor}/excluir
     */

    if (preg_match('/^[A-Za-z]+\/[0-9]+\/excluir$/', @$_GET["path"])) {

        $relacao = count($crud->Read(
            $table = "fornecedor_produto",
            $conditions = ["id_" . @$path[0] => @$path[1]],
            $fields = ["cadastro"]
        ));

        if ($relacao == 0) {

            $delete = $crud->Delete(
                $table = @$path[0],
                $conditions = ["id" => $path[1]]
            );

            if ($delete !== 0) {
                $result = [
                    "id" => $path[1],
                    "tabela" => @$path[0],
                    "mensagem" => "Item excluido com sucesso"
                ];
            } else {
                $result = [
                    "codigo" => 3,
                    "mensagem" => "Falha ao excluir item"
                ];
            }
        } else {

            $result = [
                "codigo" => 3,
                "mensagem" => "Falha. Existe uma referência a este item na tabela 'fornecedor_produto'"
            ];
        }
    }

    /** 
     * Cadastrar fornecedor e valor para um determinado produto
     * Caminho: /fornecedor_produto
     */

    if (preg_match('/^fornecedor_produto$/', @$_GET["path"])) {

        $produto = count($crud->Read(
            $table = "produto",
            $conditions = ["id" => @$_POST["id_produto"]],
            $fields = ["id"]
        ));

        $fornecedor = count($crud->Read(
            $table = "fornecedor",
            $conditions = ["id" => @$_POST["id_fornecedor"]],
            $fields = ["id"]
        ));

        $relacao = count($crud->Read(
            $table = "fornecedor_produto",
            $conditions = ["id_produto" => @$_POST["id_produto"], "id_fornecedor" => @$_POST["id_fornecedor"]],
            $fields = ["cadastro"]
        ));

        if ($produto > 0 && $fornecedor > 0 && $relacao == 0) {

            $create = $crud->Create(
                $table = @$path[0],
                $values = [
                    "id_produto" => @$_POST["id_produto"],
                    "id_fornecedor" => @$_POST["id_fornecedor"],
                    "valor" => @$_POST["valor"]
                ]
            );

            if ($create !== 0) {
                $result = [
                    "id_produto" => @$_POST["id_produto"],
                    "id_fornecedor" => @$_POST["id_fornecedor"],
                    "valor" => @$_POST["valor"],
                    "mensagem" => "Fornecedor e valor cadastrados com sucesso"
                ];
            } else {
                $result = [
                    "codigo" => 3,
                    "mensagem" => "Falha ao cadastrar fornecedor e valor"
                ];
            }
        } else {

            if ($produto == 0 && $fornecedor == 0) {
                $result = [
                    "codigo" => 3,
                    "mensagem" => "Falha. Produto e/ou o fornecedor informado não existe"
                ];
            }

            if ($relacao == 1) {
                $result = [
                    "codigo" => 3,
                    "mensagem" => "Falha. O produto informado já possui um cadastro atrelado a este fornecedor"
                ];
            }
        }
    }

    /** 
     * Editar o valor de um produto fornecido por um determinado fornecedor
     * Caminho: /fornecedor_produto/{id_fornecedor}/{id_produto}/editar
     */

    if (preg_match('/^fornecedor_produto\/[0-9]+\/[0-9]+\/editar$/', @$_GET["path"])) {


        $update = $crud->Update(
            $table = @$path[0],
            $values = ["valor" => @$_POST["valor"]],
            $conditions = ["id_fornecedor" => $path[1], "id_produto" => $path[2]]
        );

        if ($update !== 0) {
            $result = [
                "id_fornecedor" => $path[1],
                "id_produto" => $path[2],
                "valor" => @$_POST["valor"],
                "mensagem" => "Valor alterado com sucesso"
            ];
        } else {
            $result = [
                "codigo" => 3,
                "mensagem" => "Falha ao alterar o valor"
            ];
        }
    }
}

//

if (is_null(@$result)) {
    $result = [
        "codigo" => 1,
        "mensagem" => "Método inválido"
    ];
}

if (count($result) == 0) {
    $result = [
        "código" => 2,
        "mensagem" => "Nenhum resultado encontrado para o parâmetro informado"
    ];
}

echo json_encode($result);
