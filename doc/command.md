# Commands

## Docker

```sh
docker exec -it fpm sh
```

## Laravel

### Route

```sh
php artisan route:list
```

### DB

```sh
php artisan migrate:refresh
php artisan db:seed
```

### Test

```sh
./vendor/bin/phpunit
```

### Cache

```sh
php artisan cache:clear
php artisan config:clear
php artisan route:clear

composer dump-autoload
php artisan clear-compiled
php artisan optimize
php artisan config:cache
```
