## API Restful de cadastro de carros em Laravel com Autenticação jwt + documentação em swagger + testes

### Como rodar

- Duplique o arquivo `.env.example` e renomeie para `.env`
- Execute o comando `composer install`
- Execute o comando `php artisan key:generate`
- Execute o comando `php artisan jwt:secret`
- Para criar o container do MySQL, execute `docker compose up -d`
- Criando o banco de dados `php artisan migrate`
- Gerando o swagger `php artisan l5-swagger:generate`
- Rodando a aplicação `php artisan serve`

Acesse `http://localhost:8000/api/documentation` para usar o Swagger e testar a API :). Crie um usuário, faça login com o mesmo, e autentique ele no Swagger. Em seguida, é possível testar as rotas em `cars`.

### Como rodar os testes
- Execute o comando `php artisan test`
