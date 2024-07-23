<div class="row">
    <div class="col-12">
        <div class="form-group">
            @php
                $bap = data_get($deal,'billingAddress.billingAddressPreference');
            @endphp
            <div class="form-check form-check-inline">
                <input class="form-check-input bap" type="radio" name="billingAddress[billingAddressPreference]" id="billingAddressType1" value="company"
                    {{ $bap == 'company' ? 'checked' :'' }} >
                <label class="form-check-label" for="billingAddressType1"> Registered Business Address </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input bap" type="radio" name="billingAddress[billingAddressPreference]" id="billingAddressType2" value="site"
                    {{ $bap == 'site' ? 'checked' :'' }} >
                <label class="form-check-label" for="billingAddressType2"> Site Address </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input bap" type="radio" name="billingAddress[billingAddressPreference]" id="billingAddressType3" value="other"
                    {{ $bap == 'other' || !$bap ? 'checked' :'' }} >
                <label class="form-check-label" for="billingAddressType3"> Other Address Preferences </label>
            </div>
        </div>
    </div>
</div>

<div class="row billingAddress">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Building Number</label>
            <input class="form-control buildingNumber" type="text" name="billingAddress[buildingNumber]" value="{{data_get($deal,'billingAddress.buildingNumber')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Building Name</label>
            <input class="form-control buildingName" type="text" name="billingAddress[buildingName]" value="{{data_get($deal,'billingAddress.buildingName')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Sub-Building Name</label>
            <input class="form-control subBuildingName" type="text" name="billingAddress[subBuildingName]" value="{{data_get($deal,'billingAddress.subBuildingName')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Thoroughfare Name</label>
            <input class="form-control thoroughfareName" type="text" name="billingAddress[thoroughfareName]" value="{{data_get($deal,'billingAddress.thoroughfareName')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">County</label>
            <input class="form-control county" type="text" name="billingAddress[county]" value="{{data_get($deal,'billingAddress.county')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Post Town</label>
            <input class="form-control postTown" type="text" name="billingAddress[postTown]" value="{{data_get($deal,'billingAddress.postTown')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">PostCode</label>
            <input class="form-control postcode" type="text" name="billingAddress[postcode]" value="{{data_get($deal,'billingAddress.postcode')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">PO Box</label>
            <input class="form-control poBox" type="text" name="billingAddress[poBox]" value="{{data_get($deal,'billingAddress.poBox')}}">
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function () {
            var _address_fields = [
                'buildingName',
                'subBuildingName',
                'buildingNumber',
                'thoroughfareNumber',
                'county',
                'postTown',
                'postcode',
                'poBox',
                'number',
            ]
            var _billing_address = <?php echo (!empty(data_get($deal, 'billingAddress')) ? json_encode(data_get($deal, 'billingAddress')) :'{}') ?>;

            console.log((_billing_address && Object.keys(_billing_address).length));

            $(document).on('change', '.bap', function () {
                var _val = $(this).val();
                _address_fields.forEach(function (i) {
                    if (_val == 'other') {
                        if (_billing_address && Object.keys(_billing_address).length) {
                            $('.billingAddress .' + i).val(_billing_address[i]);
                        }
                    } else {
                        $('.billingAddress .' + i).val($('.' + _val + ' .' + i).val());
                    }
                })
            })
        })
    </script>

@endpush
