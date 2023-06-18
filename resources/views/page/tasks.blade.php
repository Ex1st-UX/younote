@extends('index')

@section('title', 'youNote')

@section('content')
    <div class="container">
        <div class="tooltip">
            <span class="tooltip-text">Это подсказка</span>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Tasks</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group mt-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Task 1
                                <span class="badge badge-primary">Tag 1</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Task 2
                                <span class="badge badge-secondary">Tag 2</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Task 3
                                <span class="badge badge-success">Tag 3</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup -->
    <div class="popup">
        <div class="popup-content">
            <!-- Другие элементы здесь -->
            <h2>Popup Title</h2>
            <p>Popup Description</p>
            <div class="tags">
                <span class="badge badge-primary">Tag 1</span>
                <span class="badge badge-secondary">Tag 2</span>
                <span class="badge badge-success">Tag 3</span>
            </div>
        </div>
    </div>
@endsection
