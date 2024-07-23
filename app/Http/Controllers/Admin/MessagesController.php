<?php

namespace App\Http\Controllers\Admin;

use App\Models\Messages;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
    {
        $this->hasPermisstion('view');

        if ($request->ajax()) {

            $query = Messages::query();

            if ($request->filled('type')) {
                $type = $request->get('type');
                $query->where("type", $type);
            }

            return DataTables::eloquent($query)
                ->editColumn('type', function ($model) {
                    return ucwords(str_replace(['_', '-'], ' ', $model->type))
                        . ($model->sub_type ? ucwords(str_replace(['_', '-'], ' ', $model->sub_type)) : '');
                })
                ->addColumn('action', function ($model) {
                    return dtButtons([
                        'view' => [
                            'url' => route("admin.messages.show", [$model->id]),
                            'title' => 'View Message',
                            'can' => 'forms.view',
                        ],
                        'delete' => [
                            'url' => route("admin.messages.destroy", [$model->id]),
                            'title' => 'Delete Message',
                            'can' => 'forms.delete',
                            'data-method' => 'DELETE',
                        ]
                    ]);
                })->rawColumns(['status'], true)->toJson();
        }

        $html = $builder->columns([
            Column::make('id')->title('#'),
            Column::make('first_name'),
            Column::make('last_name'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('created_at')->title('Created'),
            Column::make('type')->title('Type')->width('auto'),
            Column::make('action')->width(150)->addClass('text-center')->orderable(false),
        ])->orderBy('0', 'desc')->responsive()->autoWidth();

        $types = Messages::select('type')->whereNotNull('type')->groupBy('type')->pluck('type');
        $sub_types = Messages::select('sub_type')->whereNotNull('sub_type')->groupBy('sub_type')->pluck('sub_type');

        return view('admin.messages.index', compact('html', 'types', 'sub_types'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Messages $message)
    {
        return view('admin.messages.view', ['message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->hasPermisstion('delete');

        Messages::where('id', $id)->delete();

        alert_message('Message deleted successfully.');

        return redirect()->route('admin.messages.index');
    }
}
