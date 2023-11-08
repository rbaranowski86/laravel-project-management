## To build the project run commands on a system with git, docker, php (including required extensions) and composer:
```shell
git clone https://github.com/rbaranowski86/laravel-project-management
cd laravel-project-management
cp .env.testing .env
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html  laravelsail/php82-composer:latest composer install --ignore-platform-reqs
docker-compose down 
./vendor/bin/sail up -d
./vendor/bin/sail artisan passport:install
./vendor/bin/sail artisan migrate:fresh --seed 
./vendor/bin/sail  test
```
### To setup notification queue with 3 retries for task assigned:
```shell
./vendor/bin/sail  artisan queue:work --tries=3  >> /dev/null 2>&1
```
### To run schedule to receive project notifications
```shell
./vendor/bin/sail  artisan schedule:run >> /dev/null 2>&1
```
### To run tests:
```shell
./vendor/bin/sail  test
```
