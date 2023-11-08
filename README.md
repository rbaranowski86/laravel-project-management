## To build the project run commands on a system with php (including required extensions) and composer:
```shell
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed 
./vendor/bin/sail artisan passport:install
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
