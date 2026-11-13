<?php

namespace App\Http\Controllers;

use App\Enums\PostCategory;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PostController extends Controller
{
    /**
     * The number of posts shown on each page.
     */
    public const PER_PAGE = 9;

    /**
     * Show the paginated list of the published posts.
     */
    public function index(): View
    {
        $posts = Post::query()
            ->published()
            ->select(['id', 'title', 'slug', 'excerpt', 'category', 'cover_image', 'published_at'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE);

        return view('pages.news', [
            'posts' => $posts,
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Show a single published post.
     */
    public function show(Post $post): View
    {
        abort_unless(
            $post->isPubliclyVisible(),
            404,
            'مطلب مورد نظر یافت نشد یا دیگر در دسترس نیست. لطفاً فهرست اخبار را مشاهده کنید.',
        );

        return view('pages.news.show', [
            'post' => $post,
        ]);
    }

    /**
     * Return the list of posts as a partial view for the AJAX pagination.
     */
    public function more(Request $request): View
    {
        $page = max(1, $request->integer('page', 2));

        $posts = Post::query()
            ->published()
            ->select(['id', 'title', 'slug', 'excerpt', 'category', 'cover_image', 'published_at'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE, ['*'], 'page', $page);

        return view('partials.posts.list', [
            'posts' => $posts,
        ]);
    }

    /**
     * The categories of every published post, sorted by their Persian label.
     *
     * @return Collection<int, PostCategory>
     */
    protected function categories(): Collection
    {
        return Post::query()
            ->published()
            ->distinct()
            ->pluck('category')
            ->filter(fn (mixed $category): bool => $category instanceof PostCategory)
            ->unique(fn (PostCategory $category): string => $category->value)
            ->sortBy(fn (PostCategory $category): string => $category->label())
            ->values();
    }
}
