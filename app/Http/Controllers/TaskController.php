<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

/**
 * Class TaskController
 * @package App\Http\Controllers
 *
 * Controller for managing tasks.
 */
class TaskController extends Controller
{
    /**
     * Display a listing of tasks with optional status filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status');

        // Apply status filter if provided
        $tasks = Task::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->paginate(5);

        // Pass tasks and the selected filter status to the view
        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'selectedStatus' => $status,  // Pass selected status to the frontend
        ]);
    }

    /**
     * Show the form for creating a new task.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',  // Title (required, must be a string, no longer than 255 characters)
            'description' => 'required|string',  // Description (required,  must be a string)
            'status' => 'required|in:pending,in_progress,completed',  // Status (required, must be one of the defined values)
            'due_date' => 'required|date',  // Due date (required, valid date format)
        ]);

        // If validation fails, re-render the 'Create Task' page, passing the errors and old input data
        if ($validator->fails()) {
            return Inertia::render('Tasks/Create', [
                'errors' => $validator->errors(),
                'old' => $request->all(),
            ]);
        }

        // If validation passes, create a new Task using the validated data
        Task::create($validator->validated());

        // Redirect to the task index page with a success message
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Inertia\Response
     */
    public function show(Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param  \App\Models\Task  $task
     * @return \Inertia\Response
     */
    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', ['task' => $task]);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Task $task)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',  // Title (required, must be a string, no longer than 255 characters)
            'description' => 'required|string',  // Description (required,  must be a string)
            'status' => 'required|in:pending,in_progress,completed',  // Status (required, must be one of the defined values)
            'due_date' => 'required|date',  // Due date (required, valid date format)
        ]);

        // If validation fails, re-render the 'Edit Task' page, passing the task data, errors, and old input data
        if ($validator->fails()) {
            return Inertia::render('Tasks/Edit', [
                'task' => $task,
                'errors' => $validator->errors(),
                'old' => $request->all(),
            ]);
        }

        // If validation passes, update the existing task with the validated data
        $task->update($validator->validated());

        // Redirect to the task index page with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }


    /**
     * Remove the specified task from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
