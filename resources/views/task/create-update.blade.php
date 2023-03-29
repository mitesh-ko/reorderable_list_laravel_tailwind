<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ $task->id ? __('Update Task') : __('Create New Task') }}
                            </h2>
                        </header>

                        <form method="POST" action="{{ $task->id ? route('tasks.update', $task->id) : route('tasks.store') }}" class="mt-6 space-y-6">
                            @csrf
                            @if($task->id)
                                @method('PATCH')
                            @endif
                            <div>
                                <x-input-label for="project_id" :value="__('Project Id')"/>
                                <x-select-input :options="$project" :selected="$task?->project?->id" class="mt-1 block w-full" name="project_id" id="project_id" autofocus/>
                                <x-input-error :messages="$errors->get('project_id')" class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Name')"/>
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $task->name ?? '') }}"/>
                                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Description')"/>
                                <x-textarea-input id="desc" name="desc" type="text" class="mt-1 block w-full" :inputData="old('desc', $task->desc ?? '')"/>
                                <x-input-error :messages="$errors->get('desc')" class="mt-2"/>
                            </div>
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
</x-app-layout>
