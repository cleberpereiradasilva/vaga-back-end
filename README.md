### [VAGA - Programador Back-End ]


### HABILIDADES EM:

- PHP - OO
- PHP - Procedural
- Laravel Framework
- Experiência em integração de websites
- Experiência em construções de API's REST
- Integração com API's e SDKS externas
- Git
- Composer
- Docker (opcional)


### CONHECIMENTOS DESEJÁVEIS:

- Javascript Básico
- Teste de Unidade / Integração
- Noções de estruturação e configuração de servidores

### DIFERENCIAIS:

- NodeJS

# O teste
O objetivo do teste é conhecer as habilidades em:
- Programação PHP / Laravel
- Organização (código/arquivos)
- Controle de versão
- Análise/Entendimento de requisitos
- Capricho (atenção com uris validações, modelagem, nomenclatura, ...)

## Importante
Tudo que for desenvolvido não será utilizado comercialmente e a única intenção é avaliar o conhecimento do interessado.

### Qual é o teste ?
Imagine que a empresa foi contratada para participar de um projeto. Este projeto consiste na elaboração de um endpoint.

A equipe de criação já fez o layout, a equipe de frontend fez a montagem e agora teremos a programação backend para fechar este projeto.

### O que deve ser feito ?
- [ ] Modelagem de banco de dados para clientes. Os campos do cliente são: Nome, E-mail, Telefone, user_id (Todos os campos são Obrigatórios)
- [ ] Modelagem de banco de dados para dependentes. Os campos são: Nome, E-mail, Celular, cliente_id, user_id (Todos os campos são Obrigatórios)


OBS: Sua tarefa é criar os endpoints para consumir os recursos de clientes e dependentes, usar verbos: GET, POST, PUT|PATCH, DELETE, criar politica para apenas usuário que criou um cliente possa atualizar ou apagar.

### O que devo utilizar ?
- Laravel

### Como participar ?
- Fazer um fork deste repositório
- Programar para atender os requisitos
- Fazer um merge request quando finalizar. É importante que conste no merge request as instruções para execultar a aplicação desenolvida

# Boa sorte

----
----
----

# Requisitos
docker (https://www.docker.com/get-started)

docker-compose (https://docs.docker.com/compose/)

composer (https://getcomposer.org/) se for rodar só o Laravel

# Como rodar - Com o docker-compose
+ Navegar até a pasta raiz onde se encontra o `docker-compose.yml`
+ executar o comando `docker-compose build`
+ executar o comando `docker-compose up`
+ aguardar pelo banner:

`-----  TUDO PRONTO PARA PODER USAR`

`-----   API: http://localhost:8080/api`

`-----    Mysql database: dbserver `

`----- MyAdmin: http://localhost:8081`

`-----   Mysql root pass: root1pass`



# Como rodar - Apenas o Laravel(PHP)
+ Navegar até a pasta raiz onde se encontra o `docker-compose.yml`
+ `cd src`
+ editar o arquivo `.env` setando o banco de dados
+ ainda em `src` rodar os comandos 
+ `composer update`
+ `php artisan migrate`
+ `php artisan passport:install`
+ `php artisan db:seed --class=AdminSeeder`
+ opcional `php artisan db:seed` irá popular com dados fake users, cliente e dependente
+ `php artisan serv` irá rodar em http://localhost:8000/api


# Usuário
o comando `php artisan db:seed --class=AdminSeeder` vai criar um usuario padrao:<br>
admin e senha 123123
    


----

# End points

### `/api/login`

|Verb                |Fields                     |  Paramns |
|---------------|--------------------------------|-----|
|**`POST`**	| `email`: string <br/> `password`: string | /|




### `/api/user`
|Verb           |Fields                 |Paramns|
|---------------|-----------------------|-------|
|**`POST`** | `name`: string *<br/>  `email`: string  *<br/> `password`: string  *<br/> `password_confirmation`: string  *<br/> | /	|
|**`PUT`** | `name`: string  *<br/>  `email`: string  *<br/> `password`: string <br/> `password_confirmation`: string <br/> | /[id]	|
|**`GET`**          | 			| /[id]	|
|**`GET`**          | 			| /	|
|**`DELLETE`**      | 			| /[id] |


### `/api/cliente`
|Verb           |Fields                 |Paramns|
|---------------|-----------------------|-------|
|**`POST`** | `nome`: string *<br/>  `email`: string  *<br/> `telefone`: string  *<br/> | /	|
|**`PUT`** | `nome`: string  *<br/>  `email`: string  *<br/> `telefone`: string <br/>  | /[id]	|
|**`GET`**          | 			| /[id]	|
|**`GET`**          | 			| /	|
|**`DELLETE`**      | 			| /[id] |

### `/api/dependente`
|Verb           |Fields                 |Paramns|
|---------------|-----------------------|-------|
|**`POST`** | `nome`: string *<br/>  `email`: string *<br/> `celular`: string *<br/> `cliente_id`: int *<br/>| /	|
|**`PUT`** | `nome`: string *<br/>  `email`: string *<br/> `celular`: string *<br/>  `cliente_id`: int *<br/>| /[id]	|
|**`GET`**          | 			| /[id]	|
|**`GET`**          | 			| /	|
|**`DELLETE`**      | 			| /[id] |

----

# Requisitos
+ PHP >=7.2
+ php7.2-mbstring
+ php7.2-dom
+ php7.2-pdo-sqlite
+ php7.2-gd
+ php7.2-zip
+ composer
+ phpoffice (https://phpspreadsheet.readthedocs.io)

----


## Rodar Testes
> `docker exec laravel_api php ./vendor/bin/phpunit`


## Em video

[![Em Video](http://i.imgur.com/tgtG7GS.png)](https://vimeo.com/292296677)

---


# Todo List

- [x] setar projeto Laravel
- [x] setar Dockerfile(Laravel)
- [x] setar docker-compose.yml
- [x] unit test user
- [x] unit test cliente
- [x] unit test dependente
- [x] feature test user (end point)
- [x] implementar laravel/passport
- [x] feature test cliente (end point)
- [x] feature test dependente (end point)
- [x] implementar regra de edição apenas para dono
- [x] index paginado
- [x] validar os campos obrigatórios


