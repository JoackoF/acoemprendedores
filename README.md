# ACOEMPRENDEDORES

ACOEMPRENDEDORES es una aplicación web para la gestión de empleados, clientes, productos financieros y transacciones. Está construida con PHP, MySQL y Docker.

## Requisitos

- Docker y Docker Compose instalados.

## Instalación

1.  Clona este repositorio:

   git clone https://github.com/JoackoF/acoemprendedores.git
   cd acoemprendedores

2.  Construye y levanta los contenedores:

    docker-compose up --build

3.  Accede a la aplicación en tu navegador en http://localhost:8080.

Estructura del Proyecto

- src/: Código fuente de la aplicación.
- sql/init.sql: Script para inicializar la base de datos.
- docker-compose.yaml: Configuración de Docker Compose.
- Dockerfile: Configuración del contenedor PHP con Apache.