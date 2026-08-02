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


## 🚀 Instalación y Despliegue

### 1. Clonar el repositorio y acceder al directorio

Abre la terminal (o Git Bash) y ejecuta los siguientes comandos para clonar el proyecto y moverte dentro de la carpeta antes de configurar o levantar los contenedores:

```bash
git clone [https://github.com/Mai-de-jerez/proyecto_PHP.git](https://github.com/Mai-de-jerez/proyecto_PHP.git)
cd proyecto_PHP


### 2. Levantar los servicios

Una vez situado dentro del directorio proyecto_PHP, ejecuta el levantamiento con Docker:

```bash
docker compose up -d --build
