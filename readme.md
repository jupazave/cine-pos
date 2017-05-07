# Escena Descubierta

## Requerimientos

Para poder correr el proyecto es necesario lo siguiente

* php
* git
* composer
* phpunit (Si no usaras homestead)
* mysql (Si no usaras homestead)
* vagrant*
* homestead*

\* Recomendado

## Instalacion

Clonar el repositorio de github

```bash
git clone git@github.com:jupazave/cine-pos.git
```

instalar las dependencias con

```bash
composer install
```

Es recomendable el uso de Homestead para una mejor fiabilidad del entorno de desarrollo
a continuacion se mostrara una configuracion del archivo Homestead.yaml para tomar de base

```text
ip: "192.168.10.10"
memory: 2048
cpus: 1
provider: virtualbox

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa

folders:
    - map: ~/Code/cinepos
      to: /home/vagrant/cinepos

sites:
    - map: cinepos.app
      to: /home/vagrant/cinepos/Laravel/public

databases:
    - cinepos
```

### Si la base de datos esta vacia

Correr el siguiente comando para crear todas las tablas de la base de datos

```bash
php artisan migrate --seed
```

### Si la base de datos esta llena

Con el siguiente comando recreara la base de datos con sus seeders 

```bash
php artisan migrate:refresh --seed
```

## Pruebas

Para correr las pruebas es necesario saber donde esta ubicada la base de datos, si la base de datos
esta ubicada en la maquina Homestead, lo mejor es correrlas desde ahi, en caso de que no se este
utilizando correrlas normalmente

### Postman

La raiz del proyecto incluye el archivo `postman.json` para importar a postman las solicitudes ya implementadas,
esta utiliza como host el definido en el Homestead.yaml, en este caso el host es `cinepos.app`

#### Con Homestead

Entrar a la maquina homestead por ssh

```bash
vagrant ssh
```

ahora ingresar a la carpeta del proyecto en la consola, una vez dentro 
usar el siguiente comando para correr las pruebas automatizadas

```bash
phpunit
```
o
```bash
vendor/bin/phpunit
```

#### Con maquina local

Ingresar a la carpeta del proyecto y correr las pruebas con el siguiente comando

```bash
vendor/bin/phpunit
```
