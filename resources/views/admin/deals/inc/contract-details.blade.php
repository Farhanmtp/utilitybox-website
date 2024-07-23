<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Current Supplier</label>
            <select readonly name="contract[currentSupplier]"
                    {{ data_get($deal,'contract.currentSupplier') ? 'disabled' :'required' }}
                    class="form-control">
                <option value="">Current Supplier</option>
                @foreach($pricechange as $suplier)
                    <option value="{{$suplier}}"
                        {{ data_get($deal,'contract.currentSupplier',data_get($deal,'contract.currentSupplierName')) == $suplier ? 'selected' :'' }} > {{$suplier}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Current End Date</label>
            <input class="form-control" type="date"
                   {{ data_get($deal,'contract.isOutOfContract') ? "" : "required"  }}  name="contract[currentEndDate]"
                   value="{{formatDate(data_get($deal,'contract.currentEndDate')?:now())}}">
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">New Supplier</label>
            <select name="contract[newSupplier]"
                    {{ data_get($deal,'contract.newSupplier') ? 'disabled' :'' }}
                    class="form-control">
                <option value="">New Supplier</option>
                @foreach($pricechange as $suplier)
                    <option value="{{$suplier}}"
                        {{ data_get($deal,'contract.newSupplier',data_get($deal,'contract.newSupplierName')) == $suplier ? 'selected' :'' }} > {{$suplier}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">New Start Date</label>
            <input class="form-control" type="date" name="contract[startDate]"
                   value="{{formatDate(data_get($deal,'contract.startDate'))}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">New End Date</label>
            <input class="form-control" type="date" name="contract[endDate]"
                   value="{{formatDate(data_get($deal,'contract.endDate'))}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input type="hidden" name="contract[isNewConnection]" value="0">
                <input class="form-check-input" type="checkbox" name="contract[isNewConnection]"
                       {{ data_get($deal,'contract.isNewConnection') ? 'checked' :'' }}
                       id="isNewConnection" value="1">
                <label class="form-check-label" for="isNewConnection">Is New Connection</label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <input type="hidden" name="custom_quote" value="">
        <div class="form-group">
            <label class="control-label">Uplift</label>
            @if(data_get($deal,'quoteDetails.Uplift'))
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <b>Current</b>: <span
                                class="d-inline-block mx-2">{{data_get($deal,'quoteDetails.Uplift')}}</span>
                        </span>
                    </div>
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <b>New:</b>
                        </span>
                    </div>
                    <input class="form-control" type="number" step=".1" id="uplift"
                           name="customUplift"
                           value="{{data_get($deal,'customUplift')}}"
                           data-value="{{data_get($deal,'quoteDetails.Uplift')}}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="updateUplift">Update</button>
                        <button class="btn btn-outline-secondary" type="button" id="checkOffers">Check Offers</button>
                    </div>
                </div>
            @else
                <p>Quotation not selected</p>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label class="control-label">Allowed Quotes</label>
        <select name="allowedSuppliers[]" id="allowedSuppliers"  multiple class="form-control">
            @foreach($pricechange as $suplier)
                <option value="{{$suplier}}"
                    {{ in_array($suplier,data_get($deal,'allowedSuppliers',[])) ? 'selected' :'' }} > {{$suplier}}
                </option>
            @endforeach
        </select>
    </div>
</div>
@push('script')
    <script>
        $(function () {
            $('#allowedSuppliers').selectpicker({
                sanitize: false,
                size: 8,
                dropupAuto: true,
                actionsBox: true,
            });
        })
    </script>

@endpush
