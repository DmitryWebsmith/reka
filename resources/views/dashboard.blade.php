@extends('layouts.app')

@section('css')
    <style>
        .full-height {
            height: 100vh; /* высота экрана */
        }
    </style>
@endsection

@section('content')
    <div class="container full-height d-flex flex-column align-items-center justify-content-start mt-5">
        <div class="row">
            <div class="col-12" style="min-width: 800px">
                <h2 class="mb-4">Список Задач</h2>

                <form id="task-form" class="mb-4">
                    <div class="form-row">
                        <div class="col mb-3">
                            <input id="task-title" type="text" class="form-control" placeholder="Введите название задачи" required>
                        </div>
                        <div class="col mb-3">
                            <input id="task-input" type="text" class="form-control" placeholder="Введите задачу" required>
                        </div>
                        <div class="col mb-3">
                            <input id="tag-input" type="text" class="form-control" placeholder="Введите теги (через запятую)" required>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" type="submit">Добавить задачу</button>
                            <button class="btn btn-primary" onclick="logout()">Выход</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Наименование задачи</th>
                        <th>Задача</th>
                        <th>Теги</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody id="task-list">
                        @if($tasks->count() > 0)
                            @foreach($tasks as $task)
                                <tr>
                                    <td contenteditable="true">{{ $task->title }}</td>
                                    <td contenteditable="true">{{ $task->text }}</td>
                                    <td contenteditable="true">
                                        @foreach($task->tags as $tag)
                                            @if ($loop->last)
                                                {{ $tag->title }}
                                            @else
                                                {{ $tag->title }},
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" onclick="removeTask(this, {{ $task->id }})">Удалить</button>
                                        <button class="btn btn-primary btn-sm" onclick="showUpdateTaskModal({{ $task->id }})">Изменить</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>

    <!-- Модальное окно -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Изменить задачу</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="taskId">
                        <div class="form-group mb-3">
                            <label for="taskTitle">Название задачи</label>
                            <input type="text" class="form-control" id="taskTitle" placeholder="Введите название задачи" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="taskText">Задача</label>
                            <textarea class="form-control" id="taskText" rows="3" placeholder="Введите описание задачи" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="taskTags">Теги</label>
                            <input type="text" class="form-control" id="taskTags" placeholder="Введите теги (через запятую)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" onclick="updateTask()">Сохранить задачу</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const taskForm = document.getElementById('task-form');
        const taskList = document.getElementById('task-list');

        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const taskTitle = document.getElementById('task-title');
            const taskInput = document.getElementById('task-input');
            const tagInput = document.getElementById('tag-input');

            addTaskToDatabase(taskTitle.value, taskInput.value, tagInput.value);

            taskTitle.value = '';
            taskInput.value = '';
            tagInput.value = '';
        });

        function addTaskToTable(taskTitle, taskText, tags, taskId) {
            const tr = document.createElement('tr');

            tr.innerHTML = `
            <td contenteditable="true">${taskTitle}</td>
            <td contenteditable="true">${taskText}</td>
            <td contenteditable="true">${tags}</td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="removeTask(this, ${taskId})">Удалить</button>
                <button class="btn btn-primary btn-sm" onclick="showUpdateTaskModal(${taskId})">Изменить</button>
            </td>
        `;

            taskList.appendChild(tr);
        }

        function addTaskToDatabase(taskTitle, taskText, tags) {
            $.ajax({
                url: '/dashboard/task',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    task_title: taskTitle,
                    task_text: taskText,
                    tags: tags
                }
            })
                .done(function(response) {
                    addTaskToTable(taskTitle, taskText, tags, response.task_id);
                })
                .fail(function(xhr, status, error) {
                    console.error(xhr);
                    alert(xhr.responseJSON.message);
                });
        }

        function removeTask(button, taskId) {
            $.ajax({
                url: '/dashboard/task/'+taskId,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            })
                .done(function(response) {
                    const row = button.closest('tr');
                    row.remove();
                })
                .fail(function(xhr, status, error) {
                    console.error(xhr);
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Произошла ошибка при удалении задачи.');
                    }
                });
        }

        function showUpdateTaskModal(taskId) {
            $.ajax({
                url: '/dashboard/task/'+taskId,
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            })
                .done(function(response) {
                    $('#taskTitle').val(response.task.title)
                    $('#taskText').val(response.task.text)
                    $('#taskId').val(response.task.id)

                    $('#taskTags').val(convertTagsArrayToTagsString(response.task.tags))
                    $('#myModal').modal('show');
                })
                .fail(function(xhr, status, error) {
                    console.error(xhr);
                    alert(xhr.responseJSON.message);
                });
        }

        // конвертирует массив тегов в строку
        function convertTagsArrayToTagsString(tagsList) {
            let tags = ''
            let count = 1
            for (const tag of tagsList) {
                if (count === tagsList.length) {
                    tags += tag.title
                } else {
                    tags += tag.title+', '
                }

                count++
            }

            return tags
        }

        function updateTask() {
            $.ajax({
                url: '/dashboard/task/'+$('#taskId').val(),
                type: 'PUT',
                data: {
                    task_title: $('#taskTitle').val(),
                    task_text: $('#taskText').val(),
                    tags: $('#taskTags').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            })
                .done(function(response) {
                    $('#myModal').modal('hide');
                    redrawTaskTable()
                })
                .fail(function(xhr, status, error) {
                    console.error(xhr);
                    alert(xhr.responseJSON.message);
                });
        }

        function redrawTaskTable() {
            $.ajax({
                url:  '/dashboard/task/',
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            })
                .done(function(response) {
                    $('#task-list').empty();
                    for (const task of response.task) {
                        let tags = convertTagsArrayToTagsString(task.tags)
                        addTaskToTable(task.title, task.text, tags, task.id)
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error(xhr);
                    alert(xhr.responseJSON.message);
                });

        }

        function logout() {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = '_token';
            hiddenInput.value = csrfToken;

            form.appendChild(hiddenInput);

            // Добавляем форму в документ и отправляем ее
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection
