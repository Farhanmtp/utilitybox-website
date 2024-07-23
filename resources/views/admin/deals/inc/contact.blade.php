<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label class="control-label mr-2">Title: </label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="customer[title]" id="customerTitle1" value="Mr"
                    {{ data_get($deal,'customer.title') == 'Mr' ? 'checked' :'' }} >
                <label class="form-check-label" for="customerTitle1"> Mr </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="customer[title]" id="customerTitle2" value="Mrs"
                    {{ data_get($deal,'customer.title') == 'Mrs' ? 'checked' :'' }} >
                <label class="form-check-label" for="customerTitle2"> Mrs </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="customer[title]" id="customerTitle3" value="Miss"
                    {{ data_get($deal,'customer.title') == 'Miss' ? 'checked' :'' }} >
                <label class="form-check-label" for="customerTitle3"> Miss </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="customer[title]" id="customerTitle4" value="Dr"
                    {{ data_get($deal,'customer.title') == 'Dr' ? 'checked' :'' }} >
                <label class="form-check-label" for="customerTitle4"> Dr </label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Job Title</label>
            <select required name="customer[jobTitle]" class="form-control">
                <option value="">Select Job Title</option>
                <option value="Executive Board"
                    {{ data_get($deal,'customer.jobTitle') == 'Executive Board' ? 'selected' :'' }} >Executive Board
                </option>
                <option value="Head of Purchasing"
                    {{ data_get($deal,'customer.jobTitle') == 'Head of Purchasing' ? 'selected' :'' }} >Head of Purchasing
                </option>
                <option value="Head of Sales"
                    {{ data_get($deal,'customer.jobTitle') == 'Head of Sales' ? 'selected' :'' }} >Head of Sales
                </option>
                <option value="Head of Personnel"
                    {{ data_get($deal,'customer.jobTitle') == 'Head of Personnel' ? 'selected' :'' }} >Head of Personnel
                </option>
                <option value="Fin. Accounting Manager"
                    {{ data_get($deal,'customer.jobTitle') == 'Fin. Accounting Manager' ? 'selected' :'' }} >Fin. Accounting Manager
                </option>
                <option value="Marketing Manager"
                    {{ data_get($deal,'customer.jobTitle') == 'Marketing Manager' ? 'selected' :'' }} >Marketing Manager
                </option>
                <option value="Marketing Manager"
                    {{ data_get($deal,'customer.jobTitle') == 'Marketing Manager' ? 'selected' :'' }} >Marketing Manager
                </option>
                <option value="Proprietor / Owner"
                    {{ data_get($deal,'customer.jobTitle') == 'Proprietor / Owner' ? 'selected' :'' }} >Proprietor / Owner
                </option>
                <option value="Partner"
                    {{ data_get($deal,'customer.jobTitle') == 'Partner' ? 'selected' :'' }} >Partner
                </option>
                <option value="Director"
                    {{ data_get($deal,'customer.jobTitle') == 'Director' ? 'selected' :'' }} >Director
                </option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">First Name</label>
            <input class="form-control" type="text" required name="customer[firstName]" value="{{data_get($deal,'customer.firstName')}}">
        </div>
    </div>
    <div class="col-md-6">

        <div class="form-group">
            <label class="control-label">Last Name</label>
            <input class="form-control" type="text" name="customer[lastName]" value="{{data_get($deal,'customer.lastName')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Date Of Birth</label>
            <input class="form-control" type="date" name="customer[dateOfBirth]" value="{{formatDate(data_get($deal,'customer.dateOfBirth'))}}">
        </div>
    </div>
    <div class="col-md-6">

        <div class="form-group">
            <label class="control-label">Email</label>
            <input class="form-control" type="email" required name="customer[email]" value="{{data_get($deal,'customer.email')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Mobile</label>
            <input class="form-control" type="tel" required name="customer[mobile]" value="{{data_get($deal,'customer.mobile')?:data_get($deal,'customer.phone')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Landline</label>
            <input class="form-control" type="tel" name="customer[landline]" value="{{data_get($deal,'customer.landline')}}">
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Building Number</label>
            <input class="form-control" type="text" name="customer[buildingNumber]" value="{{data_get($deal,'customer.buildingNumber')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Building Name</label>
            <input class="form-control" type="text" name="customer[buildingName]" value="{{data_get($deal,'customer.buildingName')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Sub-Building Name</label>
            <input class="form-control" type="text" name="customer[subBuildingName]" value="{{data_get($deal,'customer.subBuildingName')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Thoroughfare Name</label>
            <input class="form-control" type="text" name="customer[thoroughfareName]" value="{{data_get($deal,'customer.thoroughfareName')}}">
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">County</label>
            <input class="form-control" type="text" name="customer[county]" value="{{data_get($deal,'customer.county')}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Post Town</label>
            <input class="form-control" type="text" name="customer[postTown]" value="{{data_get($deal,'customer.postTown')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">PostCode</label>
                    <input class="form-control" type="text" name="customer[postcode]" value="{{data_get($deal,'customer.postcode')}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">PO Box</label>
                    <input class="form-control" type="text" name="customer[poBox]" value="{{data_get($deal,'customer.poBox')}}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Move In Date</label>
                    <input class="form-control" type="date" name="customer[moveInDate]" value="{{formatDate(data_get($deal,'customer.moveInDate'))}}">
                </div>
            </div>
            {{--<div class="col-md-8">
                <div class="form-group">
                    <label class="control-label">Previous Address</label>
                    <input class="form-control" type="text" name="customer[previousAddress]" value="{{data_get($deal,'customer.previousAddress')}}">
                </div>
            </div>--}}
        </div>
    </div>
</div>
{{--@dump($deal->customer)--}}
