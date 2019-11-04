# Teste Serasa

Foi desenvolvida uma API utilizando laravel, redis, microservice, mysql. 

## Requisitos

Antes de continuar é necessário que tenha docker, docker-compose e make.

## Instalação


A instalação é através do makefile, após clonar o repositório, basta executar:

```bash
git checkout dev
```

```bash
make up
```

## Rotas API

User
```
Cadastrar - POST    http://localhost:8000/api/v1/users
Detalhes  - GET     http://localhost:8000/api/v1/users/{id}
Atualizar - PUT     http://localhost:8000/api/v1/users/{id}
Remover   - DELETE  http://localhost:8000/api/v1/users/{id}
Listar    - GET     http://localhost:8000/api/v1/users

Listar com parametro    - GET     http://localhost:8000/api/v1/users?name={some*}

Testes
Listar    - GET     http://localhost:8000/api/v1/users-test
Detalhes  - GET     http://localhost:8000/api/v1/users-test/{id}

Rota segura através de key para comunicação
Detalhes  - GET     http://localhost:8000/api/v1/users-secure/{id}

```

Order
```
Cadastrar       - POST    http://localhost:8888/api/v1/orders
Listar          - GET     http://localhost:8888/api/v1/orders
Detalhes        - GET     http://localhost:8888/api/v1/orders/{id}
Listar por User - GET     http://localhost:8888/api/v1/orders/user/{id}
Atualizar       - PUT     http://localhost:8888/api/v1/orders/{id}
Remover         - DELETE  http://localhost:8888/api/v1/orders/{id}

Listar com parametro         - GET     http://localhost:8888/api/v1/orders?description={some*}

Testes
Listar          - GET     http://localhost:8888/api/v1/orders-test
Detalhes        - GET     http://localhost:8888/api/v1/orders-test/{id}
```

## Detalhes do projeto

Cada API foi desenvolvida de forma independente, com seu MySQL/Redis. Para testar em uma maquina
e não ocorrer conflito de portas foi gerado um MySQL/Redis compartilhado através do docker-compose 
da pasta raiz. 

Cada API possui seu Makefile local e pode ser utilizado após descomentar a configuração dos containers
locais referente ao MySQL/Redis.

## Testes Unitários

Para executar o teste unitário deve-se setar a variável de ambiente APP_ENV como testing no arquivo .env:

```
APP_ENV=testing
```

## ElasticSearch

Foi adicionado o Kibana p/ monitorar e analisar os dados do elasticsearch.

## Postman collections 

O arquivo **Serasa-teste.postman_collection.json** na raiz possui as rotas utilizadas durante o desenvolvimento