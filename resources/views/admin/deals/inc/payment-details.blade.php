<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Payment Method</label>
            <select name="paymentDetail[method]" class="form-control">
                <option value="">Select Job Title</option>
                <option value="Direct Debit"
                    {{ in_array(data_get($deal,'paymentDetail.method') ,['DirectDebit','Direct Debit']) ? 'selected' :'' }} >
                    Direct Debit
                </option>
                <option value="Monthly Direct Debit"
                    {{ in_array(data_get($deal,'paymentDetail.method') ,['MonthlyDirectDebit','DirectDebit','Direct Debit']) ? 'selected' :'' }} >
                    Monthly Direct Debit
                </option>
                <option value="Fixed Direct Debit"
                    {{ in_array(data_get($deal,'paymentDetail.method') ,['FixedDirectDebit','Fixed Direct Debit']) ? 'selected' :'' }} >
                    Fixed Direct Debit
                </option>
                <option value="Variable Direct Debit"
                    {{ data_get($deal,'paymentDetail.method') == 'VariableDirectDebit' ? 'selected' :'' }} >
                    Variable Direct Debit
                </option>
                <option value="Receipt of Bill"
                    {{ in_array(data_get($deal,'paymentDetail.method') ,['ReceiptofBill','Receipt of Bill']) ? 'selected' :'' }} >
                    Receipt of Bill
                </option>
                <option value="Prepayment "
                    {{ data_get($deal,'paymentDetail.method') == 'Prepayment ' ? 'selected' :'' }} >
                    Prepayment
                </option>
                <option value="BACS "
                    {{ data_get($deal,'paymentDetail.method') == 'BACS ' ? 'selected' :'' }} >
                    BACS
                </option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Payment Term In Days</label>
            <input class="form-control" type="number" name="paymentDetail[paymentTermInDays]"
                   value="{{data_get($deal,'paymentDetail.paymentTermInDays')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Direct Debit Day Of Month</label>
            <input class="form-control" type="number" name="paymentDetail[directDebitDayOfMonth]"
                   value="{{data_get($deal,'paymentDetail.directDebitDayOfMonth')}}">
        </div>
    </div>
</div>
<hr>
<h5>Account Details:</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Bank Name</label>
            <input class="form-control" type="text" name="bankDetails[name]"
                   value="{{data_get($deal,'bankDetails.name')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Branch Name</label>
            <input class="form-control" type="text" name="bankDetails[branchName]"
                   value="{{data_get($deal,'bankDetails.branchName')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Sort Code</label>
            <input class="form-control" type="text" name="bankDetails[sortCode]"
                   value="{{data_get($deal,'bankDetails.sortCode')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Account Number</label>
            <input class="form-control" type="text" name="bankDetails[accountNumber]"
                   value="{{data_get($deal,'bankDetails.accountNumber')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Account Name</label>
            <input class="form-control" type="text" name="bankDetails[accountName]"
                   value="{{data_get($deal,'bankDetails.accountName')}}">
        </div>
    </div>
</div>
<hr>
<h5>Bank Address:</h5>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">Address Line 1</label>
            <input class="form-control" type="text" name="bankAddress[buildingNumber]"
                   value="{{data_get($deal,'bankAddress.buildingNumber')}}">
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">Address Line 2</label>
            <input class="form-control" type="text" name="bankAddress[buildingName]"
                   value="{{data_get($deal,'bankAddress.buildingName')}}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Thoroughfare Name</label>
            <input class="form-control" type="text" name="bankAddress[thoroughfareName]"
                   value="{{data_get($deal,'bankAddress.thoroughfareName')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">County</label>
            <input class="form-control" type="text" name="bankAddress[county]"
                   value="{{data_get($deal,'bankAddress.county')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Post Town</label>
            <input class="form-control" type="text" name="bankAddress[postTown]"
                   value="{{data_get($deal,'bankAddress.postTown')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">PostCode</label>
            <input class="form-control" type="text" name="bankAddress[postcode]"
                   value="{{data_get($deal,'bankAddress.postcode')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">PO Box</label>
            <input class="form-control" type="text" name="bankAddress[poBox]"
                   value="{{data_get($deal,'bankAddress.poBox')}}">
        </div>
    </div>
</div>
