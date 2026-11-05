<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('admin.departments.index', ['items' => Department::withCount('serviceRequests')->orderBy('sort')->get()]);
    }

    public function create()
    {
        return view('admin.departments.form', ['item' => new Department(['is_active' => true, 'icon' => 'scale'])]);
    }

    public function store(Request $request)
    {
        Department::create($this->validated($request));

        return redirect()->route('admin.departments.index')->with('success', 'Saved');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.form', ['item' => $department]);
    }

    public function update(Request $request, Department $department)
    {
        $department->update($this->validated($request, $department));

        return redirect()->route('admin.departments.index')->with('success', 'Saved');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return back()->with('success', 'Deleted');
    }

    private function validated(Request $request, ?Department $d = null): array
    {
        $rules = [
            'slug' => ['nullable', 'alpha_dash', 'max:80', Rule::unique('departments')->ignore($d?->id)],
            'icon' => 'required|string|max:40',
            'sort' => 'nullable|integer',
        ];
        foreach (['hy', 'ru', 'en'] as $l) {
            $rules["name_$l"] = ($l === 'hy' ? 'required' : 'nullable').'|string|max:200';
            $rules["description_$l"] = 'nullable|string|max:5000';
        }
        $data = $request->validate($rules);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name_en'] ?: $data['name_ru'] ?: 'dept-'.Str::random(5));
        $data['is_active'] = $request->boolean('is_active');
        $data['sort'] = (int) ($data['sort'] ?? 0);
        foreach (['ru', 'en'] as $l) {
            $data["name_$l"] = $data["name_$l"] ?: $data['name_hy'];
        }

        return $data;
    }
}
