## 🖥 Requirements

The following tools are required in order to start the installation.

* PHP 8.2 or higher
* Database (eg: MySQL, MariaDB)
* Web Server (eg: Nginx, Apache)
* Local Development (Valet for Mac or Laragon for Windows)

## 🚀 Installation

1. Clone the repository with `git clone`
2. Copy __.env.example__ file to __.env__ and edit database credentials there

    ```shell
    cp .env.example .env
    ```

3. Install composer packages

    ```shell
    composer install
    ```

4. Install npm packages and compile files

    ```shell
    npm install
    ```

   For **Development**:
    ```shell
    npm run dev
    ```

   For **Production**:
    ```shell
    npm run build
    ```

5. Generate `APP_KEY` in **.env**

    ```shell
    php artisan key:generate
    ```

6. Running migrations and all database seeds

    ```shell
    php artisan migrate
    ```

You can now visit the app in your browser by
visiting [https://trainer-recruitment-task-api.test](http://trainer-recruitment-task-api.test)

## 📡 Available APIs

### Authentication
- **Login**: [POST] `/api/login`
- **Register**: [POST] `/api/register`
- **Logout**: [GET] `/api/logout`

### Tasks
- **List All Task**: [GET] `/api/tasks`
- **Create Task**: [POST] `/api/tasks`
- **View Task**: [GET] `/api/tasks/{id}`
- **Update Task**: [PUT] `/api/tasks/{id}`
- **Delete Task**: [DELETE] `/api/tasks/{id}`
