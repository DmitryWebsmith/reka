<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TaskTest extends DuskTestCase
{
    use RefreshDatabase;

    public function testAddingTask()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser, User $user) {
            $browser->visit('/')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Войти')
                ->waitForLocation('/dashboard')
                ->assertSee('Список Задач');

            $browser->visit('/dashboard')
            ->type('task_title', 'Task Title')
            ->type('task_text', 'This is the text of the task.')
            ->type('tags', 'tag1, tag2')
            ->press('Добавить задачу')
            ->waitForText('Task Title', 10) // Ждем сообщения о успешном добавлении
            ->assertSee('Task Title') // Проверяем, что название задачи отображается
            ->assertSee('This is the text of the task.') // Проверяем текст задачи
            ->assertSee('tag1') // Проверяем первый тег
            ->assertSee('tag2'); // Проверяем второй тег
        });
    }
}
