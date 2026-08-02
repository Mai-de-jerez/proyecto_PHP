# 🎵 Proyecto PHP: Sonido Interior

**Desarrollado por:** María del Carmen Martín Rodríguez ([@Mai-de-jerez](https://github.com/Mai-de-jerez))

---

## 📌 Descripción del Proyecto

**Sonido Interior** es una aplicación web full-stack desarrollada en PHP orientada a la gestión de usuarios y la difusión de experiencias sonoras con cuencos de cuarzo y cuencos tibetanos.

El sistema integra una arquitectura modular que incluye autenticación segura, gestión de sesiones y un mecanismo de recuperación de contraseñas mediante tokens temporales y envío de correo electrónico. 

Para facilitar el desarrollo y despliegue, la aplicación se encuentra totalmente **containerizada con Docker**, aislando en servicios independientes el servidor web Apache, la base de datos relacional (MariaDB) y el receptor de correos de prueba en entorno local (Mailpit).

## 🛠️ Requisitos Previos

Según el sistema operativo que utilices, necesitas tener instalado lo siguiente:

* **En Windows / macOS:** 
  * [Docker Desktop](https://www.docker.com/products/docker-desktop/) (incluye Docker Engine y Docker Compose).
* **En Linux (Ubuntu / Debian):** 
  * Docker Engine (`docker.io`) y el plugin de Docker Compose (`docker-compose-v2`).
  * *Comando de instalación rápido en Ubuntu:*
    ```bash
    sudo apt update && sudo apt install -y git docker.io docker-compose-v2
    ```
* **Común a todos:** [Git](https://git-scm.com/)


## ✉️ Verificación de Correos de Prueba (Mailpit)

El entorno Docker incluye **Mailpit** como servidor SMTP de desarrollo para interceptar todos los correos enviados por la aplicación (como los enlaces de recuperación de contraseña) sin necesidad de mandar emails reales.

### Acceso a la interfaz web de Mailpit:

Una vez levantados los contenedores (`docker compose up -d`), abre tu navegador y entra a:

👉 **[http://localhost:8025](http://localhost:8025)**

*(Desde este panel web podrás ver de forma instantánea cualquier correo emitido por la plataforma durante las pruebas locales).*


## 🚀 Instalación y Despliegue

### 1. Clonar el repositorio y acceder al directorio

Abre la terminal (o Git Bash) y ejecuta los siguientes comandos para clonar el proyecto y moverte dentro de la carpeta antes de configurar o levantar los contenedores:

```bash
git clone https://github.com/Mai-de-jerez/proyecto_PHP.git
cd proyecto_PHP
```


### 2. Levantar los servicios

Una vez situado dentro del directorio proyecto_PHP, ejecuta el levantamiento con Docker:

```bash
docker compose up -d --build
```

### 3. Verificación del estado:

Comprobar con docker compose ps que los 3 contenedores están en verde y activos:

-sonido-interior-web

-sonido-interior-db

-sonido-interior-mailpit

Si quieres confirmar desde la consola de Ubuntu que los 3 contenedores están levantados y activos:

```bash
docker compose ps
```

### 4. URLs de acceso en el navegador:

📱 Aplicación Web: http://localhost:8083/sonido-interior/

✉️ Servidor Mailpit (Bandeja de correo de pruebas): http://localhost:8025

### 5. Solución al problema del puerto 3306 ocupado

```bash
sudo systemctl stop mysql
```

O si fuera mariadb:
```bash
sudo systemctl stop mariadb
```
Luego para parar lo que haya quedado a medias
Ejecuta en la terminal:

```bash
docker compose down
```
Y vuelve a levantar:

```bash
docker compose up -d
```

Si el contenedor sonido-interior-db aparece parado o con error (Exited), mira sus registros para ver qué le pasa:

```bash
docker compose logs db
```

