<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index()
    {
        return view('admin.articles.index', ['items' => Article::latest('published_at')->paginate(30)]);
    }

    public function create()
    {
        return view('admin.articles.form', ['item' => new Article(['is_published' => true, 'published_at' => now()])]);
    }

    public function store(Request $request)
    {
        Article::create($this->validated($request));

        return redirect()->route('admin.articles.index')->with('success', 'Saved');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', ['item' => $article]);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($this->validated($request, $article));

        return redirect()->route('admin.articles.index')->with('success', 'Saved');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return back()->with('success', 'Deleted');
    }

    private function validated(Request $request, ?Article $a = null): array
    {
        $rules = [
            'slug' => ['nullable', 'alpha_dash', 'max:120', Rule::unique('articles')->ignore($a?->id)],
            'image_url' => 'nullable|url|max:500',
            'published_at' => 'nullable|date',
        ];
        foreach (['hy', 'ru', 'en'] as $l) {
            $rules["title_$l"] = ($l === 'hy' ? 'required' : 'nullable').'|string|max:250';
            $rules["excerpt_$l"] = 'nullable|string|max:1000';
            $rules["body_$l"] = 'nullable|string|max:60000';
        }
        $data = $request->validate($rules);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title_en'] ?: $data['title_ru'] ?: '').'-'.Str::lower(Str::random(4));
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['published_at'] ?? now();
        foreach (['ru', 'en'] as $l) {
            $data["title_$l"] = $data["title_$l"] ?: $data['title_hy'];
        }

        return $data;
    }
}
