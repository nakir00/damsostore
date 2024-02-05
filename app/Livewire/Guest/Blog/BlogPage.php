<?php

namespace App\Livewire\Guest\Blog;

use App\Models\Article;
use App\Models\Page;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Tags\Tag;

use function Pest\Laravel\get;

#[Layout('layouts.app')]
class BlogPage extends Component
{
    use WithPagination;

    #[Title('Page d\'accueil')]
    public function render()
    {
        
        return view('livewire.guest.blog.blog-page')->with([
            'tags'=>Tag::all(),
            'articles'=>Article::query()->latest()->paginate(16),
            'top'=>Article::query()->with('tags','miniatureImage')->latest()->take(1)->get()->first()
        ]);
    }
}
