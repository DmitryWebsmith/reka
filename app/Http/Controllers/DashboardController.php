<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private TaskService $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService(Auth::id());
    }

    public function index(): View
    {
        return view('dashboard', ['tasks' => $this->taskService->index()]);
    }
}
