@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Weight Unit</h1>

        <form action="{{ route('admin.weight_units.update', $weightUnit) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $weightUnit->name }}"
                    required>
            </div>
            <div class="form-group">
                <label for="weight">Weight/Kg</label>
                <input type="number" step="0.0001" class="form-control" id="weight" name="weight"
                    value="{{ $weightUnit->weight }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
