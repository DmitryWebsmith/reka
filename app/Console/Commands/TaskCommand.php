<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class TaskCommand extends Command
{
    protected $signature = 'task:action {action} {--id= : ID задачи (для show, update, destroy)} {--data= : Данные для создания или обновления задачи (json)} {--email= : Email для аутентификации} {--password= : Пароль для аутентификации}';
    protected $description = 'Взаимодействие с задачами через TaskController';

    public function handle()
    {
        $action = $this->argument('action');

        $token = $this->getToken($this->option('email'), $this->option('password'));
        if (!$token) {
            return; // Завершаем выполнение, если токен не получен
        }

        $url = config("app.url") . "/api/tasks";

        switch ($action) {
            case 'index':
                $this->callApi($url, 'GET', $token);
                break;

            case 'show':
                $id = $this->option('id');
                if (!$id) {
                    $this->error('ID задачи обязательно');
                    return;
                }
                $this->callApi("$url/$id", 'GET', $token);
                break;

            case 'store':
                $data = $this->option('data');
                if (!$data) {
                    $this->error('Данные для создания задачи обязательны');
                    return;
                }
                $this->callApi($url, 'POST', $token, $data);
                break;

            case 'update':
                $id = $this->option('id');
                $data = $this->option('data');
                if (!$id || !$data) {
                    $this->error('ID задачи и данные для обновления обязательно');
                    return;
                }
                $this->callApi("$url/$id", 'PUT', $token, $data);
                break;

            case 'destroy':
                $id = $this->option('id');
                if (!$id) {
                    $this->error('ID задачи обязательно');
                    return;
                }
                $this->callApi("$url/$id", 'DELETE', $token);
                break;

            default:
                $this->error('Неизвестное действие. Доступные действия: index, show, store, update, destroy.');
                break;
        }
    }

    private function getToken($email, $password)
    {
        $client = new Client();
        $response = $client->post(config("app.url") . "/api/token", [
            'json' => [
                'email' => $email,
                'password' => $password
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody(), true);
            return $body['token'] ?? null; // Предполагаем, что токен передается в формате JSON
        } else {
            $this->error("Не удалось получить токен. Статус: " . $response->getStatusCode());
            return null;
        }
    }

    private function callApi($url, $method, $token = null, $data = null)
    {
        $client = new Client();

        $options = [
            'http_errors' => false,
        ];

        // Добавляем токен в заголовки, если он предоставлен
        if ($token) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $token,
            ];
        }

        if ($data) {
            $options['json'] = json_decode($data, true);
        }

        $response = $client->request($method, $url, $options);

        $this->info("Response Status: " . $response->getStatusCode());
        $this->info("Response Body: " . $response->getBody());
    }
}
