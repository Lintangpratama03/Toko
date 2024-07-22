<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeightUnit;
use Illuminate\Http\Request;

class WeightUnitController extends Controller
{
    public function index()
    {
        $weightUnits = WeightUnit::all();
        return view('admin.weight_units.index', compact('weightUnits'));
    }

    public function create()
    {
        return view('admin.weight_units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0',
        ]);

        WeightUnit::create($request->all());

        return redirect()->route('admin.weight_units.index')->with('success', 'Weight unit created successfully.');
    }

    public function edit(WeightUnit $weightUnit)
    {
        return view('admin.weight_units.edit', compact('weightUnit'));
    }

    public function update(Request $request, WeightUnit $weightUnit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0',
        ]);

        $weightUnit->update($request->all());

        return redirect()->route('admin.weight_units.index')->with('success', 'Weight unit updated successfully.');
    }

    public function destroy(WeightUnit $weightUnit)
    {
        $weightUnit->delete();

        return redirect()->route('admin.weight_units.index')->with('success', 'Weight unit deleted successfully.');
    }
}
