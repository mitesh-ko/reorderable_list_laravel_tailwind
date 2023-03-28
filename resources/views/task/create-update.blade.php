<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task List') }}
        </h2>
    </x-slot>

    <div class="container-fluid mt-2">
        <div class="card">
            <div class="card-body">
                <div class="col-md-6">
                    <form action="{{ $task->id ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
                        @csrf
                        @if($task->id)
                            @method('PATCH')
                        @endif
                        <div class="mt-2">
                            <label for="project_id" class="form-label">Project</label>
                            <select class="form-control @error('project_id') is-invalid @enderror" name="project_id" id="project_id">
                                <option value="">Select a project</option>
                                @foreach($project as $key => $value)
                                    <option value="{{ $value->id }}" @selected(old('project', $task?->project?->id) == $value->id)>{{ $value->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">@error('project_id') {{ $message }} @enderror</div>
                        </div>
                        <div class="mt-2">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $task->name ?? '') }}">
                            <div class="invalid-feedback">@error('name') {{ $message }} @enderror</div>
                        </div>
                        <div class="mt-2">
                            <label for="desc" class="form-label">desc</label>
                            <textarea name="desc" id="desc" class="form-control @error('desc') is-invalid @enderror">{{ old('name', $task->name ?? '') }}</textarea>
                            <div class="invalid-feedback">@error('desc') {{ $message }} @enderror</div>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
