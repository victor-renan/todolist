# Tasklist

Sistema para gerenciamento de tarefas com Laravel e Vue.js

### Sumário

1. [Requerimentos](#requerimentos)
2. [Executando Localmente](#executando-localmente)
3. [Executando com Docker](#executando-com-docker)


### Requerimentos

#### Versões

```
PHP >= 8.3
Laravel >= 12.0
MySQL >= 15.0
Node.js >= 18.0
```

#### Extensões do PHP

```
gd
dom
fileinfo
pdo_mysql
mysqli
pdo_sqlite
sqlite3
```

## Executando localmente

Clone este repositório, entre na pasta `/backend` e copie o conteúdo do arquivo `.env.example` para um novo arquivo chamado `.env`. Isso pode ser feito usando o seguinte comando:

### Backend

```bash
git clone https://github.com/victor-renan/todolist && cd todolist
cd backend
cp .env.example .env
```

Em seguida, crie um banco de dados no **MySQL**, ou use um já existente, colocando suas credenciais de usuário, nome do banco, host e porta no arquivo `.env`.

```
...

DB_HOST=<host>
DB_PORT=<porta>
DB_DATABASE=<nome_do_banco>
DB_USERNAME=<usuário>
DB_PASSWORD=<senha>

...
```

> [!WARNING]
> O usuário precisa ter as devidas permissões para acessar ao banco.

Agora, execute o composer para instalar as dependências do projeto:

```bash
composer update
```

Em seguida, é necessário executar as migrações do Laravel no banco configurado. Para isso, execute o seguinte comando:

```bash
php artisan migrate
```

Depois, basta executar o comando abaixo e a API será exposta, geralmente em http://127.0.0.1:8000:

```bash
php artisan serve
```
> [!INFO]
> Se a porta 8000 já estiver sendo usada, será usada a 8001 e assim por diante

### Frontend

Minimizando o terminal do backend, crie um novo terminal na pasta raiz do repositório. Depois, entre na pasta `/frontend` e copie o conteúdo de `.env.example` para `.env`. Para isso, execute o comando abaixo:

```bash
cd frontend
cp .env.example .env
```

Copie o link da API gerado no comando `artisan serve` e cole na variável `VITE_API_URL` passando o endpoint `/api`, como feito a seguir:

```
VITE_BACKEND_URL = "http://localhost:8000/api"
```

Depois basta instalar as dependências e executar o comando para rodar o frontend:

```
npm install
npm run dev
```

Por fim, basta abrir o link do frontend, geralmente http://localhost:5173, no seu navegador de preferência.

### Informações Adicionais

Se você não quer criar todos as tarefas manualmente para testar, depois de ter registrado um usuário, entre na pasta `/backend` com outro terminal e rode o comando:

```bash
php artisan db:seed
```

Isso gerará um conjunto de tarefas para o usuário recém-criado.

## Executando com Docker

Clone este repositório, e rode o seguinte comando para criar os arquivos `.env` na pasta `/frontend` e `/backend`:

```bash
git clone https://github.com/victor-renan/todolist && cd todolist
cp frontend/.env.example frontend/.env
cp backend/.env.example backend/.env
```

Em seguida, abra um novo terminal e execute o docker-compose na pasta `/backend`. Adicionalmente execute os comandos para as migrations e geração de chave criptográfica:

```bash
cd backend && sudo docker-compose up -d
sudo docker exec -it php-fpm php artisan migrate
sudo docker exec -it php-fpm php artisan key:generate
```

De mesmo modo, abra outro terminal e execute o docker-compose na pasta `/frontend`:

```bash
cd frontend && sudo docker-compose up -d --build
```

Depois basta abrir o link http://localhost:5555/ em seu navegador de preferência.