<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\GetTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Tag;
use App\Models\TagTask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TaskService {
    public function __construct(private int $userId)
    {
    }

    public function index(): Collection
    {
        return Task::query()
            ->where([
                'user_id' => $this->userId,
            ])
            ->with('tags')
            ->get();
    }

    public function create(StoreTaskRequest $request): JsonResponse
    {
        $task = new Task();
        $task->user_id = $this->userId;
        $task->title = $request->post('task_title');
        $task->text = $request->post('task_text');
        $task->created_at = Carbon::now();
        $task->save();

        $this->updateTags($task->id, $request->post('tags'));

        return new JsonResponse(['task_id' => $task->id], Response::HTTP_OK);
    }

    public function update(UpdateTaskRequest $request): JsonResponse
    {
        $task = Task::query()->where([
            'id' => $request->post('task_id'),
            'user_id' => $this->userId,
        ])->first();

        if ($task === null) {
            return response()->json(['message' => 'No task found for the given ID.'], 404);
        }

        $task->title = $request->post('task_title');
        $task->text = $request->post('task_text');
        $task->created_at = Carbon::now();
        $task->save();

        $this->updateTags($task->id, $request->post('tags'));

        $tasks = Task::query()
            ->with('tags')
            ->where('user_id', $this->userId)
            ->get();

        return new JsonResponse(['tasks' => $tasks], Response::HTTP_OK);
    }

    public function destroy(DeleteTaskRequest $request): JsonResponse
    {
        try {
            $deletedCount = Task::query()
                ->where([
                    'id' => $request->post('task_id'),
                    'user_id' => $this->userId,
                ])
                ->delete();

            if ($deletedCount > 0) {
                return response()->json(['message' => 'Task deleted successfully.'], 200);
            }

            return response()->json(['message' => 'No task found for the given task ID.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete task. ' . $e->getMessage()], 500);
        }
    }

    public function get(GetTaskRequest $request): JsonResponse
    {
        $task = Task::query()
            ->with('tags')
            ->where([
                'id' => $request->post('task_id'),
                'user_id' => $this->userId,
            ])
            ->first();

        if ($task === null) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        return new JsonResponse(['task' => $task], Response::HTTP_OK);
    }

    private function updateTags(int $taskId, string $tags): void
    {
        TagTask::query()
            ->where('task_id', $taskId)
            ->delete();

        $tags = explode(",", $tags);
        foreach ($tags as $tagTitle) {
            $tagTitle = trim($tagTitle);

            $tag = Tag::query()
                ->where('title', $tagTitle)
                ->first();

            if ($tag === null) {
                $tag = new Tag();
                $tag->title = $tagTitle;
                $tag->save();
            }

            $tagTask = new TagTask();
            $tagTask->tag_id = $tag->id;
            $tagTask->task_id = $taskId;
            $tagTask->save();
        }
    }
}
