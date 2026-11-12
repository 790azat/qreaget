<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Department;
use App\Models\Faq;

class PageController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'departments' => $this->departments(),
            'articles' => $this->safe(fn () => Article::published()->take(3)->get()),
            'faqs' => $this->safe(fn () => Faq::active()->get()),
        ]);
    }

    public function services()
    {
        return view('site.services', ['departments' => $this->departments()]);
    }

    public function service(string $slug)
    {
        $department = Department::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('site.service', [
            'department' => $department,
            'departments' => $this->departments(),
        ]);
    }

    public function articles()
    {
        return view('site.articles', ['articles' => Article::published()->paginate(9)]);
    }

    public function article(string $slug)
    {
        $article = Article::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('site.article', [
            'article' => $article,
            'more' => Article::published()->where('id', '!=', $article->id)->take(3)->get(),
        ]);
    }

    public function contact()
    {
        return view('site.contact', ['departments' => $this->departments()]);
    }

    public function lang(string $locale)
    {
        if (array_key_exists($locale, config('qreaget.locales'))) {
            session(['locale' => $locale]);
            cookie()->queue('locale', $locale, 60 * 24 * 365);
        }

        return redirect()->back(fallback: route('home'));
    }

    private function departments()
    {
        return $this->safe(fn () => Department::active()->get());
    }

    private function safe(callable $fn)
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }
}
