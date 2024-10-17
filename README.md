# ToDo Список

## Описание проекта
Реализовать ToDo список с использованием следующих технологий:
- На бекенде: PHP с фреймворком Laravel
- На фронтэнде: JavaScript / jQuery
- Для элементов интерфейса: Bootstrap

## Необходимый функционал

### 1. Сущности
- **User**
    - `email` (уникальный)
    - `password`
    - `name`
    - `api_token` (уникальный хеш `user_id + email + password`)

- **Task**
    - `id`
    - `user_id` (one to many)
    - `title` (от 3 до 20 символов)
    - `text` (до 200 символов)
    - `tags` (many to many)

- **Tags**
    - `id`
    - `title` (от 3 до 20 символов)

### 2. Регистрация / авторизация пользователей
- Возможность создания, редактирования и удаления личных списков задач и тегов.
- Для каждой задачи можно добавлять несколько тегов.
- Операции сохранения, редактирования и удаления должны происходить с помощью AJAX запросов, без перезагрузки страниц.

**Плюсы:** Реализация сортировки списка задач с помощью Drag and Drop.

### 3. CRUD API для списка задач
- Предусмотреть метод получения токена пользователя по его логину и паролю.
- По этому токену предоставляется доступ к методам API.
- Обязательно учесть валидацию данных.

## Задание 2: Вызов к API
- Реализовать вызов к API из первого задания.
- Использовать `curl` для вызова метода получения токена пользователя. Далее с его указанием вызывать методы получения списка задач и создание новой записи.

**Плюс:** Предусмотреть использование Docker для выполненных заданий.

## Описание локальной установки

1. Клонируйте репозиторий командой:
```bash
   git clone https://github.com/DmitryWebsmith/reka.git
```
2. Перейдите в директорию:
```bash
   cd reka
```
3. Выполните команды:
```bash
    docker compose build app && \
    docker compose up -d
```
4. Приложение доступно по адресу:
```bash
    http://localhost/ 
```

## Использование API:

- Получение списка задач:

```bash
  php artisan task:action index --email=ваш_емайл --password=ваш_password
```
- Получение конкретной задачи:
```bash
    php artisan task:action show --id=1 --email=ваш_емайл --password=ваш_password
```
- Создание задачи:
```bash
    php artisan task:action store  --email=ваш_емайл --password=ваш_password --data='{"task_title": "New Task", "task_text": "Task description", "tags": "tag1, tag2"}'
```
- Обновление задачи:
```bash
    php artisan task:action update  --email=ваш_емайл --password=ваш_password --id=1 --data='{"task_title": "Updated Task", "task_text": "Updated Task description", "tags": "tag1, tag2"}'
```
- Удаление задачи:
```bash
    php artisan task:action destroy --id=1 --email=ваш_емайл --password=ваш_password
```


