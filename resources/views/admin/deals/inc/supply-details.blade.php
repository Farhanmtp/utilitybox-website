<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Meter Number <small>(MPAN Core / MPAN Bottom / MPRN)</small></label>
            <input class="form-control" type="text" required name="smeDetails[meterNumber]" value="{{data_get($deal,'smeDetails.meterNumber')}}">
        </div>
    </div>
    @if(data_get($deal,'utilityType') != 'gas')
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">MPAN Top</label>
                <input class="form-control" type="text" required name="smeDetails[mpanTop]" value="{{data_get($deal,'smeDetails.mpanTop')}}">
            </div>
        </div>
    @endif
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Meter Serial Number</label>
            <input class="form-control" type="text" required name="smeDetails[meterSerialNumber]" value="{{data_get($deal,'smeDetails.meterSerialNumber')}}">
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <label>Usage</label>
    </div>
    <div class="col-lg-2 col-md-3">
        <div class="form-group">
            <label class="control-label">Unit</label>
            <input class="form-control" type="number" required name="usage[unit]" value="{{data_get($deal,'usage.unit')}}">
        </div>
    </div>
    @if(data_get($deal,'utilityType') == 'electric')
        <div class="col-lg-2 col-md-3">
            <div class="form-group">
                <label class="control-label">Day</label>
                <input class="form-control" type="number" name="usage[day]" value="{{data_get($deal,'usage.day')}}">
            </div>
        </div>
        <div class="col-lg-2 col-md-3">
            <div class="form-group">
                <label class="control-label">Night</label>
                <input class="form-control" type="number" name="usage[night]" value="{{data_get($deal,'usage.night')}}">
            </div>
        </div>
        <div class="col-lg-2 col-md-3">
            <div class="form-group">
                <label class="control-label">Weekend</label>
                <input class="form-control" type="number" name="usage[weekend]" value="{{data_get($deal,'usage.weekend')}}">
            </div>
        </div>
        <div class="col-lg-2 col-md-3">
            <div class="form-group">
                <label class="control-label">Reactive</label>
                <input class="form-control" type="number" name="usage[reactive]" value="{{data_get($deal,'usage.reactive')}}">
            </div>
        </div>
        <div class="col-lg-2 col-md-3">
            <div class="form-group">
                <label class="control-label">Capacity</label>
                <input class="form-control" type="number" name="usage[capacity]" value="{{data_get($deal,'usage.capacity')}}">
            </div>
        </div>
    @endif
</div>
<hr>
<input type="hidden" name="site[address]" value="{{data_get($deal,'site.address')}}">
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Site Name</label>
            <input class="form-control" type="text" name="site[name]" value="{{data_get($deal,'site.name')}}">
        </div>
    </div>
</div>
<div class="row site">
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">Address Line 1</label>
            <input class="form-control buildingNumber" type="text" name="site[buildingNumber]" value="{{data_get($deal,'site.buildingNumber')}}">
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">Address Line 2</label>
            <input class="form-control buildingName" type="text" name="site[buildingName]" value="{{data_get($deal,'site.buildingName')}}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Thoroughfare Name</label>
            <input class="form-control" type="text" name="site[thoroughfareName]" value="{{data_get($deal,'site.thoroughfareName')}}">
        </div>
    </div>
    @if(data_get($deal,'site.dependentThoroughfareName'))
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Dependent Thorough Fare Name</label>
                <input class="form-control" type="text" name="site[dependentThoroughfareName]" value="{{data_get($deal,'site.dependentThoroughfareName')}}">
            </div>
        </div>
    @endif
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">County</label>
            <input class="form-control county" type="text" name="site[county]" value="{{data_get($deal,'site.county')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Post Town</label>
            <input class="form-control postTown" type="text" name="site[postTown]" value="{{data_get($deal,'site.postTown')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">PostCode</label>
            <input class="form-control postcode" type="text" name="site[postcode]" value="{{data_get($deal,'site.postcode')}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">PO Box</label>
            <input class="form-control poBox" type="text" name="site[poBox]" value="{{data_get($deal,'site.poBox')}}">
        </div>
    </div>
</div>
