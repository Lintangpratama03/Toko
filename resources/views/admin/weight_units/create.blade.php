@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Weight Unit</h1>

        <form action="{{ route('admin.weight_units.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="weight">Weight/Kg</label>
                <input type="number" step="0.0001" class="form-control" id="weight" name="weight" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
