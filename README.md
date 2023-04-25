# CRUD em PHP com arquitetura MVC

O objetivo deste projeto é fornecer uma solução prática para realização de CRUD (Create, Read, Update & Delete) em banco de dados relacionais. 

Para facilitar o uso, foi desenvolvida dentro do projeto uma API Rest, que consome os métodos projetados utilizando a arquitetura MVC. Desta forma, é possível reaproveitar o sistema em plataformas diversas, como em um aplicativo mobile, por exemplo, devendo o desenvolvedor apenas realizar a consulta da API, sem necessidade de conexão direta com o banco de dados e sem precisar criar toda a estrutura de consulta novamente.

## Métodos da API

````
POST: /produto/novo
````
_Este método serve para adicionar um novo produto ao banco. É necessário passar através do body o valor correspondente ao campo 'nome'._

````
GET: /produto/{id_produto}
````
_Este método serve para retornar os dados de um produto do banco. É necessário substituir o {id_produto} pelo id do produto cadastrado._

````
POST: /produto/{id_produto}/editar
````
_Este método serve para editar os dados de um produto do banco. É necessário substituir o {id_produto} pelo id do produto cadastrado. Também é necessário passar o valor do campo 'nome' através do body._

````
POST: /produto/{id_produto}/excluir
````
_Este método serve para excluir um produto do banco. É necessário substituir o {id_produto} pelo id do produto cadastrado._

````
GET: /produto/{id_produto}/fornecedores
````
_Este método serve para retornar os fornecedores que fornecem um determinado produto. É necessário substituir o {id_produto} pelo id do produto cadastrado._

````
POST: /fornecedor/novo
````
_Este método serve para adicionar um novo fornecedor ao banco. É necessário passar através do body o valor correspondente ao campo 'nome'._

````
GET: /fornecedor/{id_fornecedor}
````
_Este método serve para retornar os dados de um fornecedor do banco. É necessário substituir o {id_fornecedor} pelo id do fornecedor cadastrado._

````
POST: /fornecedor/{id_fornecedor}/editar
````
_Este método serve para editar os dados de um fornecedor do banco. É necessário substituir o {id_fornecedor} pelo id do fornecedor cadastrado. Também é necessário passar o valor do campo 'nome' através do body._

````
POST: /fornecedor/{id_fornecedor}/excluir
````
_Este método serve para excluir um fornecedor do banco. É necessário substituir o {id_fornecedor} pelo id do fornecedor cadastrado._

````
GET: /fornecedor/{id_fornecedor}/produtos
````
_Este método serve para retornar os produtos que um fornecedor fornece. É necessário substituir o {id_fornecedor} pelo id do fornecedor cadastrado._

````
POST: /fornecedor_produto
````
_Este método serve para cadastrar o valor e um fornecedor para um determinado produto. É necessário passar através do body os valores para os campos 'fornecedor_id', 'produto_id' e 'valor'._

````
POST: /fornecedor_produto/{id_fornecedor}/{id_produto}/editar
````
_Este método serve para editar o valor de um produto fornecido por determinado fornecedor. É necessário passar através do body o valor para o campo 'valor'. Também é necessário substituir os parâmetros {id_produto} e {id_fornecedor} pelos valores correspondentes._

````
POST: /fornecedor_produto/{id_fornecedor}/{id_produto}/excluir
````
_Este método serve para excluir o registro de valor de um determinado produto fornecido por algum fornecedor. É necessário substituir os parâmetros {id_fornecedor} e {id_produto} pelos valores correspondentes._

