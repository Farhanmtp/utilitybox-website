<div class="row">
    <div class="col-12">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label mr-2">Company Type: </label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="company[type]" id="companyType1" value="Limited"
                        {{ data_get($deal,'company.type') == 'Limited' ? 'checked' :'' }} >
                    <label class="form-check-label" for="companyType1"> Limited </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="company[type]" id="companyType3" value="SoleTrader"
                        {{ data_get($deal,'company.type') == 'SoleTrader' ? 'checked' :'' }} >
                    <label class="form-check-label" for="companyType3"> Sole Trader </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="company[type]" id="companyType2" value="LimitedLiabilityPartnership"
                        {{ data_get($deal,'company.type') == 'LimitedLiabilityPartnership' ? 'checked' :'' }} >
                    <label class="form-check-label" for="companyType2"> LLP </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label company-name">{{ data_get($deal,'company.type') == 'LimitedLiabilityPartnership' ? 'Partnership Name' :'Company Name' }}</label>
            <input class="form-control" type="text" required name="company[name]" id="companyName" value="{{data_get($deal,'company.name')}}">
            <div id="companies" style="display: none">
                <ul></ul>
            </div>
        </div>
    </div>
</div>
<div class="company-details" style="display: {{ data_get($deal,'company.type') == 'LimitedLiabilityPartnership' ? 'none' :'block' }}">
    <div class="row">
        <div class="col-md-6 ">
            <div class="form-group">
                <label class="control-label">Registration Number</label>
                <input class="form-control" type="text" name="company[number]" id="companyNumber" value="{{data_get($deal,'company.number')}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <div class="d-block">
                    <div class="form-check form-check-inline">
                        <input type="hidden" name="company[isMicroBusiness]" value="0">
                        <input class="form-check-input" type="checkbox" name="company[isMicroBusiness]"
                               {{ data_get($deal,'company.isMicroBusiness') ? 'checked' :'' }}
                               id="isMicroBusiness" value="1">
                        <label class="form-check-label" for="isMicroBusiness">Is Micro Business</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="company-details" style="display: {{ data_get($deal,'company.type') == 'LimitedLiabilityPartnership' ? 'none' :'block' }}">
    <hr>
    <div class="row company">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Address Line 1</label>
                <input class="form-control buildingNumber" type="text" name="company[buildingNumber]" id="companyBuildingNumber" value="{{data_get($deal,'company.buildingNumber')}}">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Address Line 2</label>
                <input class="form-control buildingName" type="text" name="company[buildingName]" id="companyBuildingName" value="{{data_get($deal,'company.buildingName')}}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Thoroughfare Name</label>
                <input class="form-control thoroughfareName" type="text" name="company[thoroughfareName]" id="companyThoroughfareName" value="{{data_get($deal,'company.thoroughfareName')}}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">County</label>
                <input class="form-control county" type="text" name="company[county]" id="companyCounty" value="{{data_get($deal,'company.county')}}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Post Town</label>
                <input class="form-control postTown" type="text" name="company[postTown]" id="companyPostTown" value="{{data_get($deal,'company.postTown')}}">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">PostCode</label>
                <input class="form-control postcode" type="text" name="company[postcode]" id="companyPostcode" value="{{data_get($deal,'company.postcode')}}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">PO Box</label>
                <input class="form-control poBox" type="text" name="company[poBox]" value="{{data_get($deal,'company.poBox')}}">
            </div>
        </div>
    </div>
</div>


<div class="partner-details" style="display: {{ data_get($deal,'company.type') == 'LimitedLiabilityPartnership' ? 'block' :'none' }}">
    <hr>
    <h5>First Partner Details:</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">First Name</label>
                <input class="form-control" type="text" name="company[partner1][firstName]" value="{{data_get($deal,'company.partner1.firstName')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Last Name</label>
                <input class="form-control" type="text" name="company[partner1][lastName]" value="{{data_get($deal,'company.partner1.lastName')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Date of Birth</label>
                <input class="form-control" type="date" name="company[partner1][dob]" value="{{data_get($deal,'company.partner1.dob')}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Address Line 1</label>
                <input class="form-control" type="text" name="company[partner1][buildingNumber]" value="{{data_get($deal,'company.partner1.buildingNumber')}}">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Address Line 2</label>
                <input class="form-control" type="text" name="company[partner1][buildingName]" value="{{data_get($deal,'company.partner1.buildingName')}}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Thoroughfare Name</label>
                <input class="form-control" type="text" name="company[partner1][thoroughfareName]" value="{{data_get($deal,'company.partner1.thoroughfareName')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">County</label>
                <input class="form-control" type="text" name="company[partner1][county]" value="{{data_get($deal,'company.partner1.county')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Post Town</label>
                <input class="form-control" type="text" name="company[partner1][postTown]" value="{{data_get($deal,'company.partner1.postTown')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">PostCode</label>
                        <input class="form-control" type="text" name="company[partner1][postcode]" value="{{data_get($deal,'company.partner1.postcode')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">PO Box</label>
                        <input class="form-control" type="text" name="company[partner1][poBox]" value="{{data_get($deal,'company.partner1.poBox')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h5>Second Partner Details:</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">First Name</label>
                <input class="form-control" type="text" name="company[partner2][firstName]" value="{{data_get($deal,'company.partner2.firstName')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Last Name</label>
                <input class="form-control" type="text" name="company[partner2][lastName]" value="{{data_get($deal,'company.partner2.lastName')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Date of Birth</label>
                <input class="form-control" type="date" name="company[partner2][dob]" value="{{data_get($deal,'company.partner2.dob')}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Address Line 1</label>
                <input class="form-control" type="text" name="company[partner2][buildingNumber]" value="{{data_get($deal,'company.partner2.buildingNumber')}}">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Address Line 2</label>
                <input class="form-control" type="text" name="company[partner2][buildingName]" value="{{data_get($deal,'company.partner2.buildingName')}}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Thoroughfare Name</label>
                <input class="form-control" type="text" name="company[partner2][thoroughfareName]" value="{{data_get($deal,'company.partner2.thoroughfareName')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">County</label>
                <input class="form-control" type="text" name="company[partner2][county]" value="{{data_get($deal,'company.partner2.county')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Post Town</label>
                <input class="form-control" type="text" name="company[partner2][postTown]" value="{{data_get($deal,'company.partner2.postTown')}}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">PostCode</label>
                        <input class="form-control" type="text" name="company[partner2][postcode]" value="{{data_get($deal,'company.partner2.postcode')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">PO Box</label>
                        <input class="form-control" type="text" name="company[partner2][poBox]" value="{{data_get($deal,'company.partner2.poBox')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('style')
    <style>
        #companies {
            display: none;
            position: absolute;
            top: 99%;
            width: 100%;
            background: #fff;
            z-index: 999;
            border: 1px solid #ccc;
            max-height: 250px;
            overflow: hidden;
            overflow-y: auto;
        }

        #companies ul {
            margin: 0;
            padding: 0;
        }

        #companies ul li {
            display: block;
            padding: 2px 13px;
            cursor: pointer;
        }

        #companies .searching {
            padding: 2px 13px;
        }

        #companies ul li:not(:last-child) {
            border-bottom: 1px dashed #ccc;
        }
    </style>
@endpush
@push('script')
    <script>
        function setCompanyAddress(company) {
            var name = company.title;
            var number = company?.number;
            var premises = company?.address?.premises ?? '';
            var buildingNumber = (premises ? premises + ', ' : '') + company?.address?.address_line_1 ?? '';
            var buildingName = company?.address?.address_line_2 ?? '';
            var postTown = company?.address?.locality ?? company?.address?.address_line_2 ?? '';
            var county = company?.address?.region ?? company?.address?.locality;
            var postcode = company?.address?.postal_code;
            var sicCodes = company?.sic_codes ?? '';
            var thoroughfareName = company?.address?.address_line_2 ?? '';

            $("#companyName").val(name);
            $("#companyNumber").val(number);
            $("#companyCounty").val(county);
            $("#companyPostcode").val(postcode);
            $("#companyBuildingName").val(buildingName);
            $("#companyBuildingNumber").val(buildingNumber);
            $("#companyThoroughfare").val(thoroughfareName);
            $("#companyPostTown").val(postTown);
            $("#companies").hide();
        }

        $(function () {

            $(document).on('change', '[name="company[type]"]', function (e) {
                var type = $(this).val();
                if (type == 'LimitedLiabilityPartnership') {
                    $(".company-name").text('Partnership Name');
                    $(".company-details").hide();
                    $(".partner-details").show();
                } else {
                    $(".company-name").text('Company Name');
                    $(".partner-details").hide();
                    $(".company-details").show()
                }
                console.log($(this).val());
            })
            var timer = null;
            $(document).on('keyup', '#companyName', function () {
                var _val = $(this).val();
                var _types = $('[name="company[type]"]:checked');
                var _type = '';
                if (_types.length) {
                    _type = _types.val();
                }
                if (_type === 'Limited') {
                    _type = 'ltd'
                } else if (_type === 'LimitedLiabilityPartnership') {
                    _type = 'llp'
                } else if (_type === 'PLC') {
                    _type = 'plc'
                } else {
                    _type = ''
                }

                if (_type == 'ltd' && _val) {
                    timer = setTimeout(function () {
                        var comps = $("#companies");
                        comps.html('<div class="searching">Searching...</div>').show();
                        /*$.ajax({
                            method: 'POST',
                            url: '{{ url('/api/powwr/companies') }}',
                            data: {q: _val, type: _type,}
                        }).done(function (data) {*/

                        $.post('{{ url('/api/powwr/companies') }}', {
                            q: _val,
                            type: _type,
                        }, function (e) {
                            var data = e?.data;
                            var html = $('<ul/>');
                            if (data && data.length) {
                                $.each(data, function (i, com) {
                                    var li = $('<li/>').html(com?.title).on('click', function () {
                                        setCompanyAddress(com);
                                    });
                                    html.append(li);
                                })
                            }
                            comps.html(html).show();
                        }).fail(function () {
                            comps.html('<ul><li>Record not found.</li></ul>')
                        })
                    }, 500)
                }
            })
        })
    </script>

@endpush
