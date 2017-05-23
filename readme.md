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

## Instalación

Clonar el repositorio de github

```bash
git clone git@github.com:jupazave/cine-pos.git
```

Instalar las dependencias con

```bash
composer install

```

Es recomendable el uso de Homestead para una mejor fiabilidad del entorno de desarrollo a continuación se mostrará una configuración del archivo Homestead.yaml para tomar de base

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

Para mantener congurencia, usaremos una base de datos con el nombre 

```text
cinepos
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

A partir de este punto, puedes empezar a consultar el API en el host

```text
http://cinepos.app/
```

## Pruebas

Para correr las pruebas es necesario saber donde esta ubicada la base de datos, si la base de datos esta ubicada en la maquina Homestead, lo mejor es correrlas en la misma, en caso de que no se este utilizando correrlas normalmente.

### Postman

En a raíz del proyecto se incluye el archivo `postman.json`. Para utilizarlas, mantendremos el mismo host que en el archivo `Homestead.yaml`, es decir `cinepos.app`

#### Con Homestead

Entrar a la maquina homestead por ssh

```bash
vagrant ssh
```

Ahora ingresar a la carpeta del proyecto en la consola, una vez dentro, podremos usar el siguiente comando para correr las pruebas automatizadas

```bash
vendor/bin/phpunit
```

#### Con maquina local

Ingresar a la carpeta del proyecto y correr las pruebas con el siguiente comando

```bash
vendor/bin/phpunit
```

### Code Coverage 

Al correr las pruebas deberia tirar un error como el siguiente

```bash
PHPUnit 5.7.19 by Sebastian Bergmann and contributors.

Error:         No code coverage driver is available
```

El error no afecta a la ejecucion de las pruebas, pero para poder hacer `code coverage` es necesario habilitar un modulo de php ya instalado por defecto en Homestead, para poder habilitarlo usar el siguiente comando

```bash
sudo phpdismod xdebug
```

Si ejecutamos las pruebas de nuevo las pruebas, podremos observar como al terminar nos responde con un
 
```bash
Generating code coverage report in HTML format ... done
``` 

Generando una carpeta `folder` en la raiz del proyecto en la que estara de manera detallada el `code coverage` del proyecto

##### ADVERTENCIA

Utilizar el modulo `xdebug` disminuye el rendimiento hasta 3 o 5 veces los programas como `composer` o `phpunit` razon por la cual el modulo esta desactivado por defecto, para poder desactivarlo de nuevo utilizar el siguiente comando
 
 ```bash
 sudo phpdismod xdebug
 ```