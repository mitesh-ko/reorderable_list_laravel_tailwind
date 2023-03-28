<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task List') }}
        </h2>
        <form id="project_task" action="{{ route('tasks.index') }}">
            <div class="col-md-3">
                <label>Select Project</label>
                <select class="form-control" name="project" onchange='document.getElementById("project_task").submit()'>
                    <option value="">Select a project</option>
                    @foreach($project as $key => $value)
                        <option value="{{ $value->name }}" @selected(old('project', request()->project) == $value->name)>{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </x-slot>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script>
        $(function () {
            $("#sortable").sortable({
                axis: 'y',
                update: function (event, ui) {
                    const data = $(this).sortable('serialize');
                    $.ajax({
                        type: 'POST',
                        data: {"reorder": data},
                        url: $("#sortable").attr('data-url'),
                        success: function (response) {
                            if (response.ok) {
                                $(".transaction-message").html(`<span class="alert alert-info">${response.message}</span>`)
                            }
                        }
                    });
                }
            });

            $(".delete-task").click(function () {
                if(confirm('Are you sure.')) {
                    $.ajax({
                        type: 'DELETE',
                        url: $(this).attr('data-url'),
                        success: function (response) {
                            if (response.ok) {
                                $(".transaction-message").html(`<span class="alert alert-info">${response.message}</span>`)
                            }
                        }
                    });
                }
            })
        });
    </script>
    <div class="container-fluid mt-2">

        <div class="card my-2">
            <div class="card-header">
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add New Task</a>
            </div>
            <div class="card-body">
                <ul id="sortable" data-url="{{ route('changePriority') }}">
                    @foreach($task as $key => $value)
                        <li id="item_{{ $value->id }}" class="ui-state-default">
                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                            {{ $value->name }} / Project: {{ $value->project->name }}
                            <div class="text-right">
                                <a class="btn btn-primary btn-sm mx-2 mb-2 text-white" href="{{ route('tasks.edit', $value->id) }}">Edit</a>
                                <span class="btn btn-danger btn-sm mx-2 mb-2 delete-task" data-url="{{ route('tasks.destroy', $value->id) }}">Delete</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</x-app-layout>
