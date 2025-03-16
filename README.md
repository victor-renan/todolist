# Tasklist

Sistema para gerenciamento de tarefas com Laravel e Vue.js

### Requerimentos

```
PHP >= 8.3
Laravel >= 12.0
MySQL >= 15.0
Node.js >= 18.0
```

## Executando localmente

Entre na pasta `/backend` e copie o conteúdo do arquivo `.env.example` para um novo arquivo chamado `.env`. Tudo isso pode ser feito usando o seguinte comando:

### Backend

```bash
cd backend
cp .env.example >> .env
```

Crie um banco de dados no **MySQL** e coloque suas credenciais de usuário, nome do banco, host e porta no arquivo `.env`.

```
...

# Host
DB_HOST=127.0.0.1

# Porta
DB_PORT=3306

# Nome do Banco
DB_DATABASE=laravel

# Usuário
DB_USERNAME=root

# Senha
DB_PASSWORD=password 

...
```

> [!WARNING]
> O usuário precisa ter as devidas permissões para acessar ao banco.

Em seguida, é necessário executar as migrações do Laravel no banco configurado. Para isso, execute o seguinte comando:

```bash
php artisan migrate
```

Depois, basta servir a API com o seguinte comando:

```bash
php artisan serve
```

### Frontend

Semelhantemente, é necessário entrar na pasta `/frontend` e copiar a `.env.example`. Estando ainda no diretório `/backend`, execute o comando abaixo para entrar na pasta e fazer a cópia:

```bash
cd ../frontend
cp .env.example >> .env
```

Copie o link da API gerado no comando `artisan serve` e cole na variável `VITE_API_URL` passando o endpoint `/api`, como feito a seguir:

```
VITE_BACKEND_URL = "http://localhost:8000/api"
```

Depois execute o comando para rodar o frontend:

```
npm run dev
```

Por fim, basta abrir o link do frontend no seu navegador de preferência.