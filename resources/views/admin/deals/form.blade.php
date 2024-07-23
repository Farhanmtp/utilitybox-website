@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.deals.update',$deal->id) }}" method="post" class="validate-form"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $deal->name }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.deals.index') }}"><i
                                class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                {!! show_alert() !!}
                <input type="hidden" name="id" value="{{ $deal->id }}">
                <input type="hidden" name="dealId" value="{{ $deal->dealId }}">
                <p>DealId: {{ $deal->dealId }}</p>
                <div class="card">
                    <div class="card-header">
                        <h5 class="p-0 m-0">
                            Deal Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Current Supplier</th>
                                <td>{{ data_get($deal,'contract.currentSupplier') }}</td>
                                <th>New Supplier</th>
                                <td>{{ data_get($deal,'contract.newSupplier') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{!! $deal->status_html !!}</td>
                                <th>Created At</th>
                                <td>{{ data_get($deal,'created_at') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingContact" data-toggle="collapse" data-target="#collapseContact"
                         aria-expanded="false" aria-controls="collapseContact">
                        <h5 class="p-0 m-0">
                            Contact Details
                        </h5>
                    </div>
                    <div id="collapseContact" class="collapse" aria-labelledby="headingContact">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.contact')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingBusiness" data-toggle="collapse" data-target="#collapseBusiness"
                         aria-expanded="false" aria-controls="collapseBusiness">
                        <h5 class="p-0 m-0">
                            Business Details
                        </h5>
                    </div>
                    <div id="collapseBusiness" class="collapse" aria-labelledby="headingBusiness">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.business-details')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingSupply" data-toggle="collapse" data-target="#collapseSupply"
                         aria-expanded="false" aria-controls="collapseSupply">
                        <h5 class="p-0 m-0">
                            Supply Details
                        </h5>
                    </div>
                    <div id="collapseSupply" class="collapse" aria-labelledby="headingSupply">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.supply-details')
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingBilling" data-toggle="collapse" data-target="#collapseBilling"
                         aria-expanded="false" aria-controls="collapseBilling">
                        <h5 class="p-0 m-0">
                            Billing Details
                        </h5>
                    </div>
                    <div id="collapseBilling" class="collapse" aria-labelledby="headingBilling">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.billing-details')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingPayment" data-toggle="collapse" data-target="#collapsePayment"
                         aria-expanded="false" aria-controls="collapsePayment">
                        <h5 class="p-0 m-0">
                            Payment Details
                        </h5>
                    </div>
                    <div id="collapsePayment" class="collapse" aria-labelledby="headingPayment">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.payment-details')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingContract" data-toggle="collapse" data-target="#collapseContract"
                         aria-expanded="false" aria-controls="collapseContract">
                        <h5 class="p-0 m-0">
                            Contract Details
                        </h5>
                    </div>
                    <div id="collapseContract" class="collapse" aria-labelledby="headingContract">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.contract-details')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingQuote" data-toggle="collapse" data-target="#collapseQuote"
                         aria-expanded="false" aria-controls="collapseQuote">
                        <h5 class="p-0 m-0">
                            Quote Details
                        </h5>
                    </div>
                    <div id="collapseQuote" class="collapse" aria-labelledby="headingQuote">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.quote-details')
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                @if(hasPermission('deals.edit'))
                    {{--<button type="submit" name="handler" value="submit-deal" class="btn btn-success">Update Deal</button>
                        <button type="submit" name="handler" value="submit-quote" class="btn btn-success">Submit Quote</button>--}}
                    <button type="submit" name="handler" value="save" class="btn btn-success">Save Deal</button>
                    <button type="submit" name="handler" value="submit-contract" class="btn btn-success">Send Contract
                    </button>
                    <button type="submit" name="handler" value="submit-loa" class="btn btn-success">Send LOA</button>
                @endif
                <a class="btn btn-danger" href="{{ route('admin.deals.index') }}">Cancel</a>

            </div>
        </form>
    </div>
    <div class="card card-danger ">
        <div class="card-header" id="headingDebug" data-toggle="collapse" data-target="#collapseDebug"
             aria-expanded="false" aria-controls="collapseDebug">
            <h5 class="p-0 m-0">
                Debugging Details
            </h5>
        </div>
        <div id="collapseDebug" class="collapse" aria-labelledby="headingDebug">
            <div class="card-body">
                @includeIf('admin.deals.inc.debug-details')
            </div>
        </div>
    </div>

    <div class="modal fade" id="offersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Offers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.min.css') }}">
    <style>
        .card > div[aria-expanded="true"] {
            background: #e2e2e2;
        }

        div[aria-expanded="true"] * {
            font-weight: bold;
        }

        .card:has(>[aria-expanded="true"]) {
            border: 1px solid #e2e2e2;;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/moment.js') }}"></script>
    <script src="{{ asset('assets/plugins/bs-file-input/bs-file-input.min.js') }}"></script>
    <script src="{{asset('assets/plugins/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
    <script>
        var currentOffer = {!! $deal->quoteDetails ? json_encode($deal->quoteDetails) : '""' !!};

        function copyToClipboard(id) {
            // Get the text field
            var copyText = document.getElementById(id);

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            // Alert the copied text
            alert("Copied the text: " + copyText.value);
        }

        function planDuration(terms) {
            let years = Math.floor(terms / 12);
            let months = terms % 12;

            return `${years} Year` + (months ? ` ${months} Month` : '');
        }

        function selectOffer(item) {
            $('[name="custom_quote"]').val(item ? JSON.stringify(item) : '');
        }

        function renderOffer(item, current = false) {
            var html = `<div class="p-3 border rounded mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mb-2 text-left d-inline-block"> ${item.Supplier}</h4>
                                    <div class=" ml-2 d-inline-block">${item.PlanType}</div> ${planDuration(item.Term)} Plan
                                    <div class="text-left d-inline-block ml-2">
                                        ${current ? '<span class="badge badge-success">Current Offer<span>' : ''}
                                        ${item.Preferred && !current ? '<span class="badge badge-info">Preferred<span>' : ''}
                                        ${item.BestDeal && !current ? '<span class="badge badge-warning">Best Deal</span>' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-auto"><strong>Standing Charge</strong>: ${item.StandingCharge} Pence/Day</div>`;
            if (item.DayUnitrate) {
                html += `<div class="col-md-auto"><strong>Day Unit Rate</strong>: ${item.DayUnitrate} Pence/Day</div>`;
            }
            if (item.NightUnitrate) {
                html += `<div class="col-md-auto"><strong>Night Unit Rate</strong>: ${item.NightUnitrate} Pence/Day</div>`;
            }
            if (item.WendUnitrate) {
                html += `<div class="col-md-auto"><strong>Wend Unit Rate</strong>: ${item.WendUnitrate} Pence/Day</div>`;
            }
            html += `<div class="col-md-auto">
                        <strong>Total New Spend</strong>:
                        £${item?.RawAnnualPrice ? (item?.RawAnnualPrice / 12).toFixed(2) : 0}
                        (${item?.AnnualPrice} Per Annum)
                    </div>
                </div>`;
            //if (!current) {
            //html += `<div class="mt-4"><button type="button" class="btn btn-success select-offer">Select Offer</button></div>`;
            //}
            html += `</div>`;

            return html;
        }

        function loadOffers(uplift) {
            $.ajax({
                type: 'POST',
                url: "{{ url('api/powwr/offers')}}",
                data: {
                    utilityType: "{{ data_get($deal,"utilityType") }}",
                    meterNumber: "{{ data_get($deal,"smeDetails.meterNumber") }}",
                    meterType: '{{ data_get($deal,"smeDetails.meterType",'') }}',
                    currentSupplier: "{{ data_get($deal,"contract.currentSupplier") }}",
                    newSupplier: "{{ data_get($deal,"quoteDetails.Supplier") }}",
                    contractRenewalDate: '{{ data_get($deal,"contract.startDate") }}',
                    contractEnded: false,
                    contractEndDate: '{{ data_get($deal,"contract.endDate") }}',
                    measurementClass: '{{ data_get($deal,"smeDetails.measurementClass") }}',
                    halfHourly: '',
                    //prompts: '',
                    plans: {
                        uplift: uplift,
                        duration: "{{data_get($deal,'quoteDetails.Term')}}"
                    },
                    consumption: {
                        amount: '{{ data_get($deal,"usage.unit") }}',
                        day: '{{ data_get($deal,"usage.unit") }}',
                        night: '{{ data_get($deal,"usage.night") }}',
                        wend: '{{ data_get($deal,"usage.weekend") }}',
                        kva: '{{ data_get($deal,"usage.kva") }}',
                        kvarh: '{{ data_get($deal,"usage.kvarh") }}',
                    },
                    mpanTop: "{{ data_get($deal,"smeDetails.mpanTop") }}",
                    postCode: '{{ data_get($deal,"site.postcode") }}',
                    renewal: null,
                    outOfContract: null,
                    uplift: null,
                    standingChargeUplift: null,
                    paymentMethod: 'Monthly Direct Debit',
                    sortByCommission: null,
                    businessType: '{{ data_get($deal,"company.type") }}',
                },
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (e) {
                    var modal = $("#offersModal .modal-body");
                    modal.html('');
                    var error = e.data?.Error;
                    var rates = e.data?.Rates;
                    console.log(rates)
                    if (error) {
                        var alert = [];
                        if (error.Message) {
                            alert.push(error.Message)
                        }
                        if (error.ErrorDetail) {
                            alert.push(error.ErrorDetail)
                        }
                        if (alert.length) {
                            modal.prepend("<div class='alert alert-danger'>" + alert.join('<br>') + "</div>")
                        }
                    }

                    if (currentOffer) {
                        html = $(renderOffer(currentOffer, true));
                        html.find('.select-offer').on('click', function () {
                            selectOffer();
                        });
                        modal.append(html);
                    }
                    var html = '';
                    $.each(rates, function (i, item) {
                        html = $(renderOffer(item));
                        html.find('.select-offer').on('click', function () {
                            selectOffer(item);
                        })
                        modal.append(html);
                    })
                }
            });
        }

        $(document).ready(function () {
            bsCustomFileInput.init();
            var moveInDate = $("#customerMoveInDate");

            if (moveInDate.val() && moment(moveInDate.val()).isAfter(moment().subtract(36, 'months'))) {
                $("#PreviousAddress").show();
            }

            moveInDate.on('change', function () {
                var date = $(this).val();
                if (date && moment(date).isAfter(moment().subtract(36, 'months'))) {
                    $("#PreviousAddress").show();
                } else {
                    $("#PreviousAddress").hide();
                }
            });

            $("#checkOffers").on('click', function () {
                if ($('#uplift').val()) {
                    $("#offersModal").modal('show');
                }
            })

            $("#updateUplift").on('click', function () {
                var uplift = $('#uplift').val();
                if ($('#uplift').val()) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('admin.deals.update-uplift') }}",
                        data: {
                            uplift: uplift,
                            id: "{{ data_get($deal,"id") }}",
                        },
                        dataType: 'json',
                        beforeSend: function () {

                        },
                        success: function (e) {
                            toastr.success(e.message);
                        }
                    });
                }
            })

            $("#offersModal").on('shown.bs.modal', function () {
                var uplift = $('#uplift').val();
                $(this).find('.modal-body').html("<div>Loading..</div>");
                loadOffers(uplift);
            }).on('hidden.bs.modal', function () {
                $(this).find('.modal-body').html("");
            })
        })
    </script>
@endsection
