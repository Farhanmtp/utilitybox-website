@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('admin.deals.update',$deal->id) }}" method="post" class="validate-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><h3 class="card-title">{{ $deal->name }}</h3></div>
                    <div class="col-6 text-right">
                        <a class="btn btn-primary" href="{{ route('admin.deals.index') }}"><i class="fa fa-angle-left"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                {!! show_alert() !!}
                <input type="hidden" name="id" value="{{ $deal->id }}">
                <input type="hidden" name="dealId" value="{{ $deal->dealId }}">
                <p>DealId: {{ $deal->dealId }}</p>
                <div class="card">
                    <div class="card-header" id="headingContact">
                        <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapseContact" aria-expanded="true" aria-controls="collapseContact">
                            Contact Details
                        </h5>
                    </div>

                    <div id="collapseContact" class="collapse show" aria-labelledby="headingContact">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.contact')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingBusiness">
                        <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapseBusiness" aria-expanded="false" aria-controls="collapseBusiness">
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
                    <div class="card-header" id="headingSupply">
                        <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapseSupply" aria-expanded="false" aria-controls="collapseSupply">
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
                    <div class="card-header" id="headingContract">
                        <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapseContract" aria-expanded="false" aria-controls="collapseContract">
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
                    <div class="card-header" id="headingBilling">
                        <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapseBilling" aria-expanded="false" aria-controls="collapseBilling">
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
                    <div class="card-header" id="headingPayment">
                        <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapsePayment" aria-expanded="false" aria-controls="collapsePayment">
                            Payment Details
                        </h5>
                    </div>
                    <div id="collapsePayment" class="collapse" aria-labelledby="headingPayment">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.payment-details')
                        </div>
                    </div>
                </div>
                {{--<div class="card">
                    <div class="card-header" id="headingPayment">
                        <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapseQuote" aria-expanded="false" aria-controls="collapsePayment">
                            Payment Details
                        </h5>
                    </div>
                    <div id="collapsePayment" class="collapse" aria-labelledby="headingPayment">
                        <div class="card-body">
                            @includeIf('admin.deals.inc.payment-details')
                        </div>
                    </div>
                </div>--}}
            </div>
            <div class="card-footer text-right">
                @if(hasPermission('deals.edit'))
                    {{--<button type="submit" name="handler" value="submit-deal" class="btn btn-success">Update Deal</button>
                        <button type="submit" name="handler" value="submit-quote" class="btn btn-success">Submit Quote</button>--}}
                    <button type="submit" name="handler" value="save" class="btn btn-success">Save Deal</button>
                    <button type="submit" name="handler" value="submit-contract" class="btn btn-success">Send Contract</button>
                    <button type="submit" name="handler" value="submit-loa" class="btn btn-success">Send LOA</button>
                @endif
                <a class="btn btn-danger" href="{{ route('admin.deals.index') }}">Cancel</a>

            </div>
        </form>
    </div>
    <div class="card card-danger ">
        <div class="card-header" id="headingDebug">
            <h5 class="p-0 m-0" data-toggle="collapse" data-target="#collapseDebug" aria-expanded="false" aria-controls="collapseDebug">
                Debugging Details
            </h5>
        </div>
        <div id="collapseDebug" class="collapse" aria-labelledby="headingDebug">
            <div class="card-body">
                @includeIf('admin.deals.inc.debug-details')
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/bs-file-input/bs-file-input.min.js') }}"></script>
    <script>
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

        $(document).ready(function () {
            bsCustomFileInput.init()
        })
    </script>
@endsection
