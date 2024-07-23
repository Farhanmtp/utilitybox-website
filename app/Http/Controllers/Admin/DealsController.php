<?php

namespace App\Http\Controllers\Admin;

use App\Models\PowwrDeals;
use App\Models\PowwrSupplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class DealsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $builder)
    {
        $this->hasPermisstion('view');


        if ($request->ajax()) {


            $query = PowwrDeals::with(['supplier']);

            /*if ($supplier = $request->get('category')) {
                $query->whereHas("supplier", function ($q) use ($supplier) {
                    $q->where("id", $supplier);
                });
            }*/

            /*if ($status = $request->get('status')) {
                $query->where("status", $status);
            }*/

            return DataTables::eloquent($query)
                ->orderColumn('supplier', function ($q, $order) {
                    $q->orderBy(
                        PowwrSupplier::select('name')->whereColumn('powwr_suppliers.powwr_id', 'powwr_deals.supplierId'),
                        $order
                    );
                })
                ->orderColumn('customer', function ($q, $order) {
                    $q->orderBy('customer->firstName', $order);
                })
                ->orderColumn('email', function ($q, $order) {
                    $q->orderBy('customer->email', $order);
                })
                /*->editColumn('status', function ($model) {
                    return $model->status ?
                        '<span class="badge badge-success">Active</span>' :
                        '<span class="badge badge-danger">InActive</span>';
                })*/
                ->addColumn('supplier', function ($model) {
                    return $model->contract['currentSupplierName'] ?? '';
                    //return $model->supplier?->name;
                })
                ->addColumn('new_supplier', function ($model) {
                    return $model->contract['newSupplierName'] ?? '';
                })
                ->addColumn('customer', function ($model) {
                    return $model->customer['firstName'] ?? '';
                })
                ->addColumn('email', function ($model) {
                    return $model->customer['email'] ?? '';
                })
                ->addColumn('action', function ($model) {
                    return dtButtons([
                        'edit' => [
                            'url' => route("admin.deals.edit", [$model->id]),
                            'title' => 'Edit Deal',
                            'can' => 'deals.edit',
                        ],
                        'delete' => [
                            'url' => route("admin.deals.destroy", [$model->id]),
                            'title' => 'Delete Deal',
                            'can' => 'deals.delete',
                            'data-method' => 'DELETE',
                        ]
                    ]);
                })->rawColumns(['status'], true)->toJson();
        }

        $html = $builder->columns([
            Column::make('id')->title('#'),
            Column::make('customer'),
            Column::make('email'),
            Column::make('supplier')->title('Current Supplier')->orderable(false),
            Column::make('new_supplier')->title('New Supplier')->orderable(false),
            //Column::make('dealId')->title('DealID'),
            Column::make('envelopeId')->width(150)->title('Contract ID')->orderable(false),
            Column::make('loaEnvelopeId')->width(150)->title('LOA ID')->orderable(false),
            //Column::make('status')->title('Status')->width('auto'),
            Column::make('action')->width(150)->addClass('text-center')->orderable(false),
        ])->orderBy('0', 'desc')->responsive()->autoWidth();

        $suppliers = PowwrSupplier::active()->get();

        return view('admin.deals.index', compact('html', 'suppliers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.deals.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->hasPermisstion('edit');

        $deal = PowwrDeals::where('id', $id)->first();

        $suppliers = PowwrSupplier::active()->get();
        $pricechange = PowwrSupplier::apiSuppliers();

        $pricechange = array_keys($pricechange);

        return view('admin.deals.form', compact('deal', 'suppliers', 'pricechange'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->hasPermisstion('edit');

        $deal = PowwrDeals::where('id', $id)->first();

        $handler = $request->get('handler');

        //dd($request->all(), $deal->toArray());

        $fillables = $deal->getFillable();
        foreach ($fillables as $column) {
            if ($request->exists($column)) {
                $deal->{$column} = $request->input($column);
            }
        }

        $deal->save();

        /*if ($handler == 'submit-deal') {
            $dealResponse = $deal->saveDeal();

            if ($dealResponse['success']) {
                alert_message('Deal saved successfully to CRM.', 'success');
            } else {
                alert_message("Deal not saved to CRM.");
                $errors = $dealResponse['errors'] ?? null;
                if (!empty($errors)) {
                    foreach ($dealResponse['errors'] as $error) {
                        alert_message($error);
                    }
                } else {
                    $data = $dealResponse['data'] ?? null;
                    alert_message(json_encode($data));
                }
            }
        }*/

        if ($handler == 'submit-loa') {
            try {
                $loaResponse = $deal->sendLoa($deal);
                if ($loaResponse['success']) {
                    alert_message('LOA sent successfully.', 'success');
                } else {
                    $errors = $loaResponse['errors'] ?? null;
                    if (!empty($errors)) {
                        alert_message("LOA not sent.");
                        foreach ($errors as $error) {
                            alert_message($error);
                        }
                    }
                }
            } catch (\Exception $e) {
                alert_message("LOA Error:" . $e->getMessage());
            }
        }
        if ($handler == 'submit-contract') {
            try {
                $dealResponse = $deal->sendDocuSign($deal);
                if ($dealResponse['success']) {
                    alert_message('DocuSign sent successfully.', 'success');
                } else {
                    $errors = $dealResponse['errors'] ?? null;
                    if (!empty($errors)) {
                        alert_message("DocuSign not sent.");
                        foreach ($errors as $error) {
                            alert_message($error);
                        }
                    }
                }
            } catch (\Exception $e) {
                alert_message("Contract Error:" . $e->getMessage());
            }
        }

        if ($handler == 'submit-quote') {
            $dealResponse = $deal->sendQuote($deal);
            if ($dealResponse['success']) {
                alert_message('Quote sent successfully.', 'success');
            } else {
                $errors = $dealResponse['errors'] ?? null;
                if (!empty($errors)) {
                    alert_message("Quote not sent.");
                    foreach ($dealResponse['errors'] as $error) {
                        alert_message($error);
                    }
                }
            }
        }

        alert_message('Deal saved successfully.', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->hasPermisstion('delete');

        PowwrDeals::where('id', $id)->delete();

        alert_message('Deal deleted successfully.');

        return redirect()->route('admin.deals.index');
    }
}
