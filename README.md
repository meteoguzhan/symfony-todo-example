# Symfony todo example
## Project Details

#### Prerequisites
1. PHP >= 7.1
2. MySQL
#### Design patterns
1. Strategy
2. Repository
## Project Setup
Firstly, you need to clone git repo. (Run it in your terminal)
```bash
git clone https://github.com/meteoguzhan/symfony-todo-example.git
```
After clone project, you need to install packages. (Make sure your system exists composer)
```bash
cd symfony-todo-example && composer install
```
You need to copy env file and rename it as .env
```bash
cp .env.example .env
```
Open .env file, Give your updated details of MySQL connection string.
<pre>
DATABASE_URL="mysql://root:password@127.0.0.1:3306/todo"
</pre>
If you don't have a database, you can create one.
```bash
php bin/console doctrine:database:create
```
You can create migrations and migrate.
```bash
php bin/console make:migration && php bin/console doctrine:migrations:migrate
```
You can save developer fixtures.
```bash
php bin/console doctrine:fixtures:load
```
You can get tasks lists.
```bash
php bin/console app:fetch-and-save-tasks --api-url=https://run.mocky.io/v3/27b47d79-f382-4dee-b4fe-a0976ceda9cd && 
php bin/console app:fetch-and-save-tasks --api-url=https://run.mocky.io/v3/7b0ff222-7a9c-4c54-9396-0df58e289143
```
You can test the project.
```bash
./vendor/bin/simple-phpunit
```
You can start the project. (Make sure your system exists php)
```bash
symfony server:start
```
