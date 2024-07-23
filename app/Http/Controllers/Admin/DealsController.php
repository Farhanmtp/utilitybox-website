<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deals;
use App\Models\Suppliers;
use App\Notifications\ChangeDealUpliftNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
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

            $query = Deals::with(['supplier']);

            if ($supplier = $request->get('supplier')) {
                $query->whereJsonContains("contract->currentSupplier", $supplier);
            }
            if ($new_supplier = $request->get('new_supplier')) {
                $query->whereJsonContains("contract->newSupplier", $new_supplier);
            }

            if ($status = $request->get('status')) {
                $query->where("status", $status);
            }

            return DataTables::eloquent($query)
                ->filterColumn('supplier', function ($query, $keyword) {
                    $query->where("contract->currentSupplier", "LIKE", "%{$keyword}%");
                })
                ->filterColumn('new_supplier', function ($query, $keyword) {
                    $query->where("contract->newSupplier", "LIKE", "%{$keyword}%");
                })
                ->orderColumn('supplier', function ($q, $order) {
                    $q->orderBy('contract->currentSupplier', $order);
                })
                ->orderColumn('new_supplier', function ($q, $order) {
                    $q->orderBy('contract->newSupplier', $order);
                })
                ->orderColumn('customer', function ($q, $order) {
                    $q->orderBy('customer->firstName', $order);
                })
                ->orderColumn('email', function ($q, $order) {
                    $q->orderBy('customer->email', $order);
                })
                ->editColumn('status', function ($model) {
                    return $model->status_html;
                })
                ->addColumn('supplier', function ($model) {
                    return $model->contract['currentSupplier'] ?? $model->contract['currentSupplierName'] ?? '';
                })
                ->addColumn('new_supplier', function ($model) {
                    return $model->contract['newSupplier'] ?? $model->contract['newSupplierName'] ?? '';
                })
                ->addColumn('customer', function ($model) {
                    return $model->customer['firstName'] ?? '';
                })
                ->addColumn('email', function ($model) {
                    return $model->customer['email'] ?? '';
                })
                ->editColumn('created_at', function ($model) {
                    if ($model->created_at instanceof Carbon) {
                        return $model->created_at->toDateString();
                    } else {
                        return Carbon::parse($model->created_at)->toDateString();
                    }
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
                })->addColumn('control', '')->rawColumns(['status'], true)->toJson();
        }

        $html = $builder->addTableClass('table-sm')
            ->columns([
                Column::make('id')->title('#'),
                Column::make('customer')->responsivePriority(1),
                Column::make('email')->responsivePriority(2),
                Column::make('supplier')->title('Current Supplier')->orderable(true),
                Column::make('new_supplier')->title('New Supplier')->orderable(true),
                Column::make('envelopeId')->title('Contract ID')->width(100)->orderable(false),
                Column::make('loaEnvelopeId')->width(100)->title('LOA ID')->orderable(false),
                Column::make('created_at')->title('Date')->width(100)->responsivePriority(2),
                Column::make('status')->title('Status')->width('auto')->responsivePriority(1),
                Column::make('action')->addClass('action text-right')->width(100)->responsivePriority(1)->orderable(false),
                Column::make('control')->title(' ')->responsivePriority(1)->width('auto')->addClass('dtr-controlx')->orderable(false),
            ])->orderBy('0', 'desc')
            //->addButton(Button::make('colvis')->align('button-right')->className('btn-sm')->text('Toggle Column'))
            ->autoWidth();

        $suppliers = Suppliers::apiSuppliers();

        $suppliers = array_keys($suppliers);

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

        $deal = Deals::where('id', $id)->firstOrFail();

        $suppliers = Suppliers::active()->get();
        $pricechange = Suppliers::apiSuppliers();

        $pricechange = array_keys($pricechange);

        return view('admin.deals.form', compact('deal', 'suppliers', 'pricechange'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->hasPermisstion('edit');

        $deal = Deals::where('id', $id)->first();

        $handler = $request->get('handler');

        $customUplift = $request->input('customUplift');
        $upliftSupplier = $request->input('contract.newSupplier', data_get($deal, 'contract.newSupplier'));

        $fillables = $deal->getFillable();
        foreach ($fillables as $column) {
            if ($column == 'upliftSupplier') {
                $deal->{$column} = $customUplift ? $upliftSupplier : null;
            } else {
                if ($request->exists($column)) {
                    $deal->{$column} = $request->input($column);
                }
            }
        }

        $newSupplier = $request->input('contract.newSupplier', data_get($deal, 'contract.newSupplier'));
        $allowedSuppliers = $request->input('allowedSuppliers', []);
        if (!empty($allowedSuppliers) && $newSupplier && !in_array($newSupplier, $allowedSuppliers)) {
            $allowedSuppliers[] = $newSupplier;
        }

        //dd($allowedSuppliers);
        $deal->allowedSuppliers = $allowedSuppliers;

        $custom_quote = $request->get('custom_quote');
        if ($custom_quote) {
            $deal->quoteDetails = json_decode($custom_quote);
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

        Deals::where('id', $id)->delete();

        alert_message('Deal deleted successfully.');

        return redirect()->route('admin.deals.index');
    }

    public function updateUplift(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'uplift' => ['required'],
        ]);

        $id = $request->input('id');
        $uplift = $request->input('uplift');

        $deal = Deals::where('id', $id)->firstOrFail();

        $deal->upliftSupplier = data_get($deal, 'contract.newSupplier');
        $deal->customUplift = $uplift;
        $deal->step = 4;
        $deal->tab = null;

        $deal->save();

        $email = $deal->customer_email;
        if (!$email && $deal->user) {
            $email = $deal->user->email;
        }

        try {
            Notification::route('mail', $email)->notify(new ChangeDealUpliftNotification($email, $deal));
            return response()->json(['message' => "Uplift updated and email sent to customer"]);
        } catch (\Exception $e) {
            return response()->json(['message' => "Uplift updated but email not sent"]);
        }
    }
}
