@section('title', 'Tasks')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Tasks</h4>
                    </div>
                    <button type="button" class="btn btn-success task_add__button">Add task</button>
                    <div class="select-wrapper">
                        <select class="custom-select" name="sort" id="sort__select">
                            <option value="title">Sort by name</option>
                            <option value="created_at" selected>Sort by date</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-outline-dark task_filter__button">FILTER</button>
                    <div class="card-body task_content"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup">
        <div class="popup-content container"></div>
    </div>
</x-app-layout>


