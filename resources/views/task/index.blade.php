<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task List') }}
        </h2>
    </x-slot>
    <script src="/asset/js/jquery-sortable.min.js"></script>
    <link rel="stylesheet" href="/asset/css/jquery-sortable.min.css">
    <script>
        $(function () {
            $("#sortable").sortable({
                axis: 'y',
                update: function (event, ui) {
                    const data = $(this).sortable('toArray');
                    axios.post($("#sortable").attr('data-url'), {"reorder": data})
                        .then(function (response) {
                            console.log(response)
                            if (response.data.ok) {
                                $("#myToast").find(".text-sm").text(response.data.message);
                                showToast();
                            }
                        })
                }
            });

            $(".delete-task").click(function () {
                if (confirm('Are you sure.')) {
                    $(this).closest('li').addClass('deleting');
                    axios.delete($(this).attr('data-url'))
                        .then(function (response) {
                            $(".deleting").remove()
                            if (response.data.ok) {
                                $("#myToast").find(".text-sm").text(response.data.message);
                                showToast();
                            }
                        })
                }
            })
        });
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section>
                    <header class="container m-auto grid grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-4">
                        <x-primary-button onclick="location.href='{{ route('tasks.create') }}'"
                                          class="bg-blue-600 text-white rounded-md mb-2 px-3 mt-7 text-center">{{ __('Create New Task') }}</x-primary-button>
                        <form id="project_task" action="{{ route('tasks.index') }}">
                            <div class="col-md-3">
                                <x-input-label for="project_id" :value="__('Select Project')"/>
                                <x-select-input :options="$project" :selected="request()->project" class="mt-1 block" name="project" id="project"
                                                onchange="document.getElementById('project_task').submit()"/>
                            </div>
                        </form>
                    </header>
                    <ul id="sortable" data-url="{{ route('changePriority') }}">
                        @foreach($task as $key => $value)
                            <li id="{{ $value->id }}" class="ui-state-default my-1 rounded-md flex relative">
                                <span class="bg-gray-500 m-2 rounded-md p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon"
                                         style="width: 1.0009765625em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1025 1024"
                                         version="1.1"><path
                                            d="M585.074876 232.47085h-73.618434 237.427243L511.120686 0.743459 275.188355 232.47085h163.800816v216.122744H218.149858v143.479602h220.839313v216.906175h146.085705V592.073196h219.120563V448.593594h-219.120563V232.47085z m-73.906225 790.04115l219.983936-213.532629H292.91146l218.257191 213.524635zM0.740049 519.478017l217.409809 216.07478V305.113993L0.740049 519.478017z m803.45539-214.364024V735.544803l219.120564-216.07478-219.120564-214.364024z"/></svg>
                                </span>
                                {{ $value->name }} / Project: {{ $value->project->name }}
                                <div class="text-right mb-2 absolute top-1 right-1">
                                    <x-primary-button class="bg-blue-600 px-3 left-0"
                                                      onclick="location.href='{{ route('tasks.edit', $value->id) }}'">{{ __('Edit') }}</x-primary-button>
                                    <x-danger-button class="mx-2 delete-task" data-url="{{ route('tasks.destroy', $value->id) }}">Delete</x-danger-button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>
        </div>
    </div>

</x-app-layout>
