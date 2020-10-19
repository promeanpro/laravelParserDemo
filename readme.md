

Laravel Parser Demo
============================



```
cp .env.example .env
```


Start application
-----------------

```
docker-compose up -d
```

Stop application
----------------

```
docker-compose down -v
```
Generate application key
------------------------
```
docker-compose exec app php artisan key:generate
```

Install dependencies
--------------------

```
docker-compose exec yourMicroserviceName-app composer install --prefer-dist -o
```

Migrate
-------
```
docker-compose exec yourMicroserviceName-app php artisan migrate
```



Run tests
---------
```
 docker-compose exec yourMicroserviceName-app php vendor/bin/phpunit 
```
