<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
    {
        $this->hasPermisstion('view');

        if ($request->ajax()) {

            $query = Post::with('category');

            if ($request->filled('category')) {
                $category = $request->get('category');
                $query->whereHas("category", function ($q) use ($category) {
                    $q->where("id", $category);
                });
            }

            if ($request->filled('status')) {
                $status = $request->get('status');
                $query->where("status", $status);
            }

            return DataTables::eloquent($query)
                ->orderColumn('category', function ($q, $order) {
                    $q->orderBy(
                        Category::select('title')->whereColumn('categories.id', 'posts.category_id'),
                        $order
                    );
                })
                ->editColumn('status', function ($model) {
                    return $model->status ?
                        '<span class="badge badge-success">Active</span>' :
                        '<span class="badge badge-danger">InActive</span>';
                })
                ->addColumn('category', function ($model) {
                    return $model->category?->title;
                })
                ->addColumn('action', function ($model) {
                    return dtButtons([
                        'edit' => [
                            'url' => route("admin.posts.edit", [$model->id]),
                            'title' => 'Edit Post',
                            'can' => 'posts.edit',
                        ],
                        'delete' => [
                            'url' => route("admin.posts.destroy", [$model->id]),
                            'title' => 'Delete Post',
                            'can' => 'posts.delete',
                            'data-method' => 'DELETE',
                        ]
                    ]);
                })->rawColumns(['status'], true)->toJson();
        }

        $html = $builder->columns([
            Column::make('id')->title('#'),
            Column::make('title'),
            Column::make('category')->title('Category')->width('auto'),
            Column::make('status')->title('Status')->width('auto'),
            Column::make('action')->width(150)->addClass('text-center')->orderable(false),
        ])->orderBy('0', 'desc')->responsive()->autoWidth();

        $categories = Category::where('status', 1)->get();

        return view('admin.posts.index', compact('html', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->hasPermisstion('create');

        $categories = Category::where('status', 1)->get();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->hasPermisstion('create');

        $request->validate([
            'category_id' => ['required'],
            'title' => ['required'],
            'slug' => ['required', Rule::unique('posts', 'slug')],
            'image' => ['required', 'string']
        ]);

        $post = new Post();

        $post->category_id = $request->input('category_id');
        $post->title = $request->input('title');
        $post->slug = Str::slug($request->input('slug'));
        $post->description = $request->input('description');
        $post->content = $request->input('content');

        $post->meta_title = $request->input('meta_title');
        $post->meta_keyword = $request->input('meta_keyword');
        $post->meta_description = $request->input('meta_description');

        if ($request->exists('status')) {
            $post->status = $request->input('status');
        }

        if ($request->has('image')) {
            $post->image = $request->input('image');
        }
        if ($request->has('banner')) {
            $post->banner = $request->input('banner');
        }

        $post->save();

        if ($post->id) {


            alert_message('Post created successfully.', 'success');
            return redirect()->route('admin.posts.edit', $post->id);
        }

        alert_message('Post not created successfully.');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.posts.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->hasPermisstion('edit');

        $post = Post::where('id', $id)->first();

        $categories = Category::where('status', 1)->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->hasPermisstion('edit');

        $request->validate([
            'title' => ['required'],
            'slug' => ['required', Rule::unique('posts', 'slug')->ignore($id)],
            'image' => ['required', 'string']
        ]);

        $post = Post::where('id', $id)->first();

        $post->category_id = $request->input('category_id');
        $post->title = $request->input('title');
        $post->slug = Str::slug($request->input('slug'));
        $post->description = $request->input('description');
        $post->content = $request->input('content');

        $post->meta_title = $request->input('meta_title');
        $post->meta_keyword = $request->input('meta_keyword');
        $post->meta_description = $request->input('meta_description');

        if ($request->exists('status')) {
            $post->status = $request->input('status');
        }

        if ($request->has('image')) {
            $post->image = $request->input('image');
        }
        if ($request->has('banner')) {
            $post->banner = $request->input('banner');
        }

        $post->save();

        alert_message('Post saved successfully.', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->hasPermisstion('delete');

        Post::where('id', $id)->delete();

        alert_message('Post deleted successfully.');

        return redirect()->route('admin.posts.index');
    }
}
