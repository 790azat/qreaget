<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.faqs.index', ['items' => Faq::orderBy('sort')->get()]);
    }

    public function create()
    {
        return view('admin.faqs.form', ['item' => new Faq(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        Faq::create($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('success', 'Saved');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', ['item' => $faq]);
    }

    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('success', 'Saved');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'Deleted');
    }

    private function validated(Request $request): array
    {
        $rules = ['sort' => 'nullable|integer'];
        foreach (['hy', 'ru', 'en'] as $l) {
            $req = $l === 'hy' ? 'required' : 'nullable';
            $rules["question_$l"] = "$req|string|max:300";
            $rules["answer_$l"] = "$req|string|max:5000";
        }
        $data = $request->validate($rules);
        foreach (['ru', 'en'] as $l) {
            $data["question_$l"] = $data["question_$l"] ?: $data['question_hy'];
            $data["answer_$l"] = $data["answer_$l"] ?: $data['answer_hy'];
        }
        $data['is_active'] = $request->boolean('is_active');
        $data['sort'] = (int) ($data['sort'] ?? 0);

        return $data;
    }
}
