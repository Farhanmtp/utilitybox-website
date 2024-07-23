<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Current Supplier</label>
            {{--<select readonly name="contract[currentSupplier]"
                    {{ data_get($deal,'contract.currentSupplier') ? 'disabled' :'required' }}
                    class="form-control">
                <option value="">Current Supplier</option>
                @foreach($suppliers as $suplier)
                    <option value="{{$suplier->powwr_id}}"
                        {{ data_get($deal,'contract.currentSupplier') == $suplier->powwr_id ? 'selected' :'' }} > {{$suplier->name}}
                    </option>
                @endforeach
            </select>--}}
            <select readonly name="contract[currentSupplierName]"
                    {{ data_get($deal,'contract.currentSupplierName') ? 'disabled' :'required' }}
                    class="form-control">
                <option value="">Current Supplier</option>
                @foreach($pricechange as $suplier)
                    <option value="{{$suplier}}"
                        {{ data_get($deal,'contract.currentSupplierName') == $suplier ? 'selected' :'' }} > {{$suplier}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Current End Date</label>
            <input class="form-control" type="date" {{ data_get($deal,'contract.isOutOfContract') ? "" : "required"  }}  name="contract[currentEndDate]" value="{{formatDate(data_get($deal,'contract.currentEndDate')?:now())}}">
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">New Supplier</label>
            {{--<select name="contract[newSupplier]" required class="form-control">
                <option value="">New Supplier</option>
                @foreach($suppliers as $suplier)
                    <option value="{{$suplier->powwr_id}}"
                        {{ data_get($deal,'contract.newSupplier') == $suplier->powwr_id ? 'selected' :'' }} > {{$suplier->name}}
                    </option>
                @endforeach
            </select>--}}
            <select name="contract[newSupplierName]"
                    {{ data_get($deal,'contract.newSupplierName') ? 'disabled' :'required' }}
                    class="form-control">
                <option value="">New Supplier</option>
                @foreach($pricechange as $suplier)
                    <option value="{{$suplier}}"
                        {{ data_get($deal,'contract.newSupplierName') == $suplier ? 'selected' :'' }} > {{$suplier}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">New Start Date</label>
            <input class="form-control" type="date" required name="contract[startDate]" value="{{formatDate(data_get($deal,'contract.startDate'))}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">New End Date</label>
            <input class="form-control" type="date" required name="contract[endDate]" value="{{formatDate(data_get($deal,'contract.endDate'))}}">
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
