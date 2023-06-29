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
                        <div class="col">
                            <select class="custom-select">
                                <option value="option1">Option 1</option>
                                <option value="option2">Option 2</option>
                                <option value="option3">Option 3</option>
                                <option value="option4">Option 4</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="custom-select">
                                <option value="option1">Option 1</option>
                                <option value="option2">Option 2</option>
                                <option value="option3">Option 3</option>
                                <option value="option4">Option 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body task_content"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup">
        <div class="popup-content container"></div>
    </div>
</x-app-layout>

<script>
    let userId = {{ auth()->user()->id ?? 'null' }};
</script>


