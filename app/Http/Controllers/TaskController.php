<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    private Collection $project;

    public function __construct()
    {
        $this->project = Cache::remember('projects', 36000, function () {
            return Project::get(['name', 'id']);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $task = Task::with('project')->orderBy('created_at')->orderBy('priority')->where(function ($q) use ($request) {
            if ($request->project) {
                $id = Project::where('name', $request->project)->first('id')->id;
                $q->where('project_id', $id);
            }
        })->get();
        return view('task.index', ['project' => $this->project->pluck('name', 'name'), 'task' => $task]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task();
        return view('task.create-update', ['project' => $this->project->pluck('name', 'id'), 'task' => $task]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'name'       => 'required|max:255',
            'project_id' => 'required|numeric',
            'desc'       => 'nullable'
        ]);
        if(Task::create($validData)){
            return redirect()->route('tasks.index')->with('message', 'New task created successfully.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('task.create-update', ['project' => $this->project->pluck('name', 'id'), 'task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('task.create-update', ['project' => $this->project->pluck('name', 'id'), 'task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validData = $request->validate([
            'name'       => 'required|max:255',
            'project_id' => 'required|numeric',
            'desc'       => 'nullable'
        ]);
        if($task->update($validData)) {
            return redirect()->route('tasks.index')->with('message', 'Task updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task) {
            $task->delete();
            return response()->json(['ok' => true, 'message' => 'Task deleted successfully.']);
        } else {
            return response()->json(['ok' => false, 'message' => 'Something went wrong!']);
        }
    }

    public function changePriority(Request $request)
    {
        try {
            foreach ($request->reorder as $i => $id) {
                Task::where('id', $id)->update(['priority' => ++$i]);
            }
            return response()->json(['ok' => true, 'message' => 'Task Priority updated successfully.']);
        } catch (Exception $e) {
            info($e);
            return response()->json(['ok' => true, 'message' => 'Something went wrong!']);
        }

    }
}
