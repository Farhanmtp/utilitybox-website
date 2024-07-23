<?php

namespace App\Http\Controllers\Admin;

use App\Models\PowwrSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class PowwrSupplierController extends Controller
{
    public function index(Request $request, Builder $builder)
    {
        $this->hasPermisstion('view');

        $model = PowwrSupplier::query();

        if ($request->ajax()) {
            return DataTables::eloquent($model)
                ->editColumn('logo', function ($model) {
                    if ($model->logo_url) {
                        return '<img src="' . $model->logo_url . '" class="thumb" />';
                    }
                })
                ->editColumn('status', function ($model) {
                    return $model->status ?
                        '<span class="badge badge-success">Active</span>' :
                        '<span class="badge badge-danger">InActive</span>';
                })->addColumn('action', function ($model) {
                    return dtButtons([
                        'edit' => [
                            'url' => route("admin.suppliers.edit", [$model->id]),
                            'title' => 'Edit Supplier',
                            'can' => 'suppliers.edit',
                        ],
                        'delete' => [
                            'url' => route("admin.suppliers.destroy", [$model->id]),
                            'title' => 'Delete Supplier',
                            'can' => 'suppliers.delete',
                            'data-method' => 'DELETE',
                        ]
                    ]);
                })->rawColumns(['status'], true)->toJson();
        }

        $html = $builder->columns([
            Column::make('logo')->orderable(false),
            Column::make('name'),
            Column::make('powwr_id'),
            Column::make('status'),
            Column::make('action')->addClass('text-center')->orderable(false),
        ])->orderBy(1, 'ASC')->searchDelay(300);

        return view('admin.suppliers.index', compact('html'));
    }

    public function create()
    {
        $this->hasPermisstion('create');

        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $this->hasPermisstion('create');

        $request->validate([
            'name' => ['required'],
            'powwr_id' => ['required'],
        ], [
            'name.required' => 'Supplier name is required',
            'name.powwr_id' => 'Supplier id is required'
        ]);

        $supplier = new PowwrSupplier();

        $supplier->name = $request->name;
        $supplier->powwr_id = $request->powwr_id;
        $supplier->status = $request->status;
        $supplier->supplier_type = $request->supplier_type;

        if ($request->hasFile('logo')) {
            $supplier->logo = $request->file('logo');
        }

        $supplier->save();

        alert_message('Supplier created successfully.', 'success');

        return redirect()->route('admin.suppliers.edit', $supplier->id);
    }

    public function edit($id)
    {
        $this->hasPermisstion('edit');

        $supplier = PowwrSupplier::where('id', $id)->first();


        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request)
    {
        $this->hasPermisstion('edit');

        $request->validate([
            'name' => ['required'],
            'powwr_id' => ['required'],
        ], [
            'name.required' => 'Supplier name is required',
            'name.powwr_id' => 'Supplier id is required'
        ]);

        $supplier = PowwrSupplier::where('id', $request->input('id'))->first();

        $supplier->name = $request->name;
        $supplier->powwr_id = $request->powwr_id;
        $supplier->status = $request->status;
        $supplier->supplier_type = $request->supplier_type;

        if ($request->hasFile('logo')) {
            $supplier->logo = $request->file('logo');
        }

        $supplier->save();

        alert_message('Supplier updated successfully.', 'success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->hasPermisstion('delete');

        $supplier = PowwrSupplier::where('id', $id)->first();

        $supplier->delete();

        alert_message('Supplier deleted successfully.', 'success');

        return redirect()->route('admin.suppliers.index');
    }
}
