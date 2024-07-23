<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
    {
        $this->hasPermisstion('view');


        if ($request->ajax()) {

            $query = Category::withCount('posts');

            return DataTables::eloquent($query)
                ->orderColumn('posts_count', function ($q, $order) {
                    $q->orderBy('posts_count', $order);
                })
                ->editColumn('image', function ($model) {
                    return $model->image_url ? '<img src="' . $model->image_url . '" class="thumb" />' : '';
                })
                ->addColumn('posts_count', function ($model) {
                    return $model->posts_count;
                })
                ->addColumn('action', function ($model) {
                    return dtButtons([
                        'edit' => [
                            'url' => route("admin.categories.edit", [$model->id]),
                            'title' => 'Edit Category',
                            'can' => 'categories.edit',
                        ],
                        'delete' => [
                            'url' => route("admin.categories.destroy", [$model->id]),
                            'title' => 'Delete Category',
                            'can' => 'categories.delete',
                            'data-method' => 'DELETE',
                        ]
                    ]);
                })->toJson();
        }

        $html = $builder->columns([
            Column::make('image')->title('')->orderable(false),
            Column::make('title'),
            Column::make('posts_count')->title('Posts')->width(100),
            Column::make('action')->width(150)->addClass('text-center')->orderable(false),
        ])->orderBy(1, 'ASC');

        return view('admin.categories.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->hasPermisstion('create');

        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->hasPermisstion('create');

        $request->validate([
            'title' => ['required'],
            'slug' => ['required', Rule::unique('categories', 'slug')],
            'image' => ['nullable', 'string']
        ]);

        $category = new Category();

        $category->title = $request->input('title');
        $category->slug = Str::slug($request->input('slug'));
        $category->description = $request->input('description');
        $category->meta_title = $request->input('meta_title');
        $category->meta_keyword = $request->input('meta_keyword');
        $category->meta_description = $request->input('meta_description');

        if ($request->exists('status')) {
            $category->status = $request->input('status');
        }

        if ($request->has('image')) {
            $category->image = $request->input('image');
        }

        $category->save();

        if ($category->id) {


            alert_message('Category created successfully.', 'success');
            return redirect()->route('admin.categories.edit', $category->id);
        }

        alert_message('Category not created successfully.');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.categories.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->hasPermisstion('edit');

        $category = Category::where('id', $id)->first();

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->hasPermisstion('edit');

        $request->validate([
            'title' => ['required'],
            'slug' => ['required', Rule::unique('categories', 'slug')->ignore($id)],
            'image' => ['nullable', 'string']
        ]);

        $category = Category::where('id', $id)->first();

        $category->title = $request->input('title');
        $category->slug = Str::slug($request->input('slug'));
        $category->description = $request->input('description');
        $category->meta_title = $request->input('meta_title');
        $category->meta_keyword = $request->input('meta_keyword');
        $category->meta_description = $request->input('meta_description');

        if ($request->exists('status')) {
            $category->status = $request->input('status');
        }

        if ($request->has('image')) {
            $category->image = $request->input('image');
        }

        $category->save();

        alert_message('Category saved successfully.', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->hasPermisstion('delete');

        Category::where('id', $id)->delete();

        alert_message('Category deleted successfully.');

        redirect()->route('admin.categories.index');
    }
}
