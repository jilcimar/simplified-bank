# Documentação  📖


### POST /transaction
Endpoint para transferência de dinheiro entre os usuários.
```json
{
    "value" : 100.00,
    "payer" : 4,
    "payee" : 15
}
```
Resposta: 
```json
{
    "data": {
        "uuid": "1096223c-40dc-4afd-ae8c-5efd88c0a1c92023-02-21-11-02-49-45",
        "payer": "payer@email.com",
        "payee": "payee@email.com",
        "value": 100,
        "created_at": "21\/02\/2023 11:45:49"
    }
}
```
| Atributo | Descrição                                                                                  | Obrigatório | Tipo    | Valor padrão |
|----------|--------------------------------------------------------------------------------------------|-------------|---------|--------------|
| value    | Valor em centavos da transferência                                                         |    ✅         | integer | -            |
|     payer     | ID do usuário "pagador" da transferência, deverá ser do tipo Person                        |     ✅           |     integer    | -            |
|     payee     | ID do usuário "recebedor" da transferência, poderá ser de qualquer tipo, Company ou Person |     ✅           |     integer    | -            |

> É obrigatório ter um token de autenticação - Bearer Token

### POST /login
Endpoint para login do usuário.
```json
{
    "email": "carlos@example.com.br",
    "password": "qySOOsa9ANQJ2W9@"
}
```
Resposta: 
```json
{
    "token": "1|49u1dVh2Fslwn0Hk6d6slV4WzNgZc6zV37sxAOSc"
}
```
| Atributo | Descrição                                                                                  | Obrigatório | Tipo   | Valor padrão |
|----------|--------------------------------------------------------------------------------------------|-------------|--------|--------------|
| email    | E-mail do usuário cadastrado                                                               |    ✅         | string | -            |
| password    | Senha do usuário cadastrado                                                                |     ✅           | string | -            |
> É obrigatório ter um token de autenticação - Bearer Token

### POST /users
Endpoint para cadastro de usuário.
```json
{
    "name": "carlos",
    "email": "carlos@example.com.br",
    "password": "qySOOsa9ANQJ2W9@",
    "password_confirmation" :"qySOOsa9ANQJ2W9@",
    "type": "person",
    "cpf": "46508612093",
    "cnpj": null
}
```
Resposta: 
```json
{
	"name": "carlos",
	"email": "carlos@example.com.br",
	"type": "person",
	"updated_at": "2023-02-20T13:23:53.000000Z",
	"created_at": "2023-02-20T13:23:53.000000Z"
}
```

| Atributo              | Descrição                                                                                                                                 | Obrigatório | Tipo   | Valor padrão |
|-----------------------|-------------------------------------------------------------------------------------------------------------------------------------------|-------------|--------|--------------|
| name                  | Nome do usuário                                                                                                                           |    ✅         | string | -            |
| email                 | Email do usuário                                                                                                                          |     ✅         | string | -            |
| password              | Senha do usuário, que deverá ter: <ul> <li>8 ou mais caracteres</li> <li>caracteres especiais</li> <li>Números</li> <li>Símbolos</li></ul> |     ✅         | string | -            |
| password_confirmation | Confirmação da senha, deve ser igual ao atributo `password`                                                                               |     ✅         | string | -            |
| type                  | Tipo do usuário, `Person` para pessoa física e `Company` para pessoa jurídica                                                             |     ✅         | string | -            |
| cpf                   | CPF do usuário, obrigatório quando o `type` é `Person`                                                                                    |     ❌          | string | -            |
| cnpj                  | CNPJ do usuário,  obrigatório quando o `type` é `Company`                              |     ❌          | string | -            |
> Não é obrigatório ter um token de autenticação

### GET /users
Listagem de todos os usuários cadastrados.
```json
{
    "data": [
        {
            "id": 1,
            "name": "User Payer 1",
            "email": "payer@email.com"
        },
        {
            "id": 2,
            "name": "User Payee 1",
            "email": "payee@email.com"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/api\/users?page=1",
        "last": "http:\/\/localhost\/api\/users?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http:\/\/localhost\/api\/users?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http:\/\/localhost\/api\/users",
        "per_page": 5,
        "to": 2,
        "total": 2
    }
}
```
