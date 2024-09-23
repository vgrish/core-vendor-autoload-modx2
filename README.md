Пакет для MODX Revolution V.2

Подключает ```MODX_CORE_PATH . 'vendor/autoload.php'``` и инициализирует подключение 
```'bootstrap.php'``` в сторонних пакетах. 

## Установка пакета
```
composer require vgrish/core-vendor-autoload-modx2 --update-no-dev
composer exec core-vendor-autoload-modx2 install
```

## Удаление пакета
```
composer exec core-vendor-autoload-modx2 remove
composer remove vgrish/core-vendor-autoload-modx2
```