<h4>Powwr API Configuration</h4>
<div class="row">
    <?php
    $api_mode = data_get($setting->values, 'api_mode', 'test');
    ?>
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label mr-2">Api Mode: </label>
            <div class="form-check form-check-inline">
                <input class="form-check-input api_mode_field" type="radio" name="values[api_mode]" id="companyType1" value="live"
                    {{ data_get($setting->values,'api_mode', config('powwr.api_mode')) == 'live' ? 'checked' :'' }} >
                <label class="form-check-label" for="companyType1"> Live </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input api_mode_field" type="radio" name="values[api_mode]" id="companyType3" value="test"
                    {{ data_get($setting->values,'api_mode', config('powwr.api_mode')) == 'test' ? 'checked' :'' }} >
                <label class="form-check-label" for="companyType3"> Test </label>
            </div>
        </div>
    </div>
    <div class="col-md-6 api-settings api-live" {!! $api_mode !== 'live' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="brokerage_id">Brokerage ID</label>
            <input type="text" name="values[brokerage_id]" required
                   {!! $api_mode !== 'live' ? 'readonly':'' !!}
                   id="brokerage_id" class="form-control"
                   value="{{ data_get($setting->values,'brokerage_id', config('powwr.brokerage_id')) }}" placeholder="Enter brokerage ID">
        </div>
    </div>
    <div class="col-md-6 api-settings api-test" {!! $api_mode !== 'test' ? 'style="display:none"':'' !!} >
        <div class="form-group">
            <label for="test_brokerage_id">Brokerage ID</label>
            <input type="text" name="values[test_brokerage_id]" required
                   {!! $api_mode !== 'test' ? 'readonly':'' !!}
                   id="test_brokerage_id" class="form-control"
                   value="{{ data_get($setting->values,'test_brokerage_id', config('powwr.test_brokerage_id')) }}" placeholder="Enter brokerage ID">
        </div>
    </div>
    <div class="col-md-6 api-settings api-live" {!! $api_mode !== 'live' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="brokerage_email">Brokerage Email</label>
            <input type="text" name="values[brokerage_email]" required id="brokerage_email" class="form-control"
                   value="{{ data_get($setting->values,'brokerage_email', config('powwr.brokerage_email')) }}" placeholder="Enter brokerage email">
        </div>
    </div>
    <div class="col-md-6 api-settings api-test" {!! $api_mode !== 'test' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="test_brokerage_email">Brokerage Email</label>
            <input type="text" name="values[test_brokerage_email]" required id="test_brokerage_email" class="form-control"
                   value="{{ data_get($setting->values,'test_brokerage_email', config('powwr.test_brokerage_email')) }}" placeholder="Enter brokerage email">
        </div>
    </div>
    <div class="col-md-6 api-settings api-live" {!! $api_mode !== 'live' ? 'style="display:none"':'' !!} >
        <div class="form-group">
            <label for="client_id">Client ID</label>
            <input type="text" name="values[client_id]" required id="client_id" class="form-control"
                   value="{{ data_get($setting->values,'client_id', config('powwr.client_id')) }}" placeholder="Enter client id">
        </div>
    </div>
    <div class="col-md-6 api-settings api-test" {!! $api_mode !== 'test' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="test_client_id">Client ID</label>
            <input type="text" name="values[test_client_id]" required id="test_client_id" class="form-control"
                   value="{{ data_get($setting->values,'test_client_id', config('powwr.test_client_id')) }}" placeholder="Enter client id">
        </div>
    </div>
    <div class="col-md-6 api-settings api-live" {!! $api_mode !== 'live' ? 'style="display:none"':'' !!} >
        <div class="form-group">
            <label for="client_secret">Client Secret</label>
            <input type="text" name="values[client_secret]" required id="client_secret" class="form-control"
                   value="{{ data_get($setting->values,'client_secret', config('powwr.client_secret')) }}" placeholder="Enter client secret">
        </div>
    </div>
    <div class="col-md-6 api-settings api-test" {!! $api_mode !== 'test' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="test_client_secret">Client Secret</label>
            <input type="text" name="values[test_client_secret]" required id="test_client_secret" class="form-control"
                   value="{{ data_get($setting->values,'test_client_secret', config('powwr.test_client_secret')) }}" placeholder="Enter client secret">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="client_secret">API Endpoint</label>
            <p class="form-control api-settings api-live" {!! $api_mode !== 'live' ? 'style="display:none"':'' !!}>{{ config('powwr.api_endpoint') }}</p>
            <p class="form-control api-settings api-test" {!! $api_mode !== 'test' ? 'style="display:none"':'' !!}>{{ config('powwr.test_api_endpoint') }}</p>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="client_secret">Token Endpoint</label>
            <p class="form-control api-settings api-live" {!! $api_mode !== 'live' ? 'style="display:none"':'' !!}>{{ config('powwr.token_url') }}</p>
            <p class="form-control api-settings api-test" {!! $api_mode !== 'test' ? 'style="display:none"':'' !!}>{{ config('powwr.test_token_url') }}</p>
        </div>
    </div>
</div>
<hr>
<h4>Companies House Setting</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="client_secret">Api Key</label>
            <input type="text" required name="values[company_house_api_key]" id="company_house_api_key" class="form-control"
                   value="{{ data_get($setting->values,'company_house_api_key', config('powwr.company_house_api_key')) }}" placeholder="Enter api key">
        </div>
    </div>
</div>
<h4>UD Core API Setting</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="udcore_user_name">User Name</label>
            <input type="text" name="values[udcore_user_name]" id="udcore_user_name" class="form-control"
                   value="{{ data_get($setting->values,'udcore_user_name', config('powwr.udcore_user_name')) }}" placeholder="Enter user name">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="udcore_licence_code">Licence Code</label>
            <input type="text" name="values[udcore_licence_code]" id="udcore_licence_code" class="form-control"
                   value="{{ data_get($setting->values,'udcore_licence_code', config('powwr.udcore_licence_code')) }}" placeholder="Enter licence code">
        </div>
    </div>
</div>
<h4>DocuSign Settings</h4>
<div class="row">

    @php
        $dc_api_mode = data_get($setting->values, 'docusign_api_mode', 'test');
    @endphp
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label mr-2">Api Mode: </label>
            <div class="form-check form-check-inline">
                <input class="form-check-input dc_api_mode_field" type="radio" name="values[docusign_api_mode]" id="docusign_api_mode1" value="live"
                    {{ data_get($setting->values,'docusign_api_mode',config('powwr.docusign_api_mode')) == 'live' ? 'checked' :'' }} >
                <label class="form-check-label" for="docusign_api_mode1"> Live </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input dc_api_mode_field" type="radio" name="values[docusign_api_mode]" id="docusign_api_mode2" value="test"
                    {{ data_get($setting->values,'docusign_api_mode',config('powwr.docusign_api_mode')) == 'test' ? 'checked' :'' }} >
                <label class="form-check-label" for="docusign_api_mode2"> Test </label>
            </div>
        </div>
    </div>
    <div class="col-md-6 dc-api-settings dc-api-live" {!! $dc_api_mode !== 'live' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="docusign_username">User Name</label>
            <input type="text" name="values[docusign_username]" required
                   {!! $dc_api_mode !== 'live' ? 'readonly':'' !!}
                   id="docusign_username" class="form-control"
                   value="{{ data_get($setting->values,'docusign_username', config('powwr.docusign_username')) }}" placeholder="Enter user name">
        </div>
    </div>
    <div class="col-md-6 dc-api-settings dc-api-test" {!! $dc_api_mode !== 'test' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="docusign_test_username">Test User Name</label>
            <input type="text" name="values[docusign_test_username]" required
                   {!! $dc_api_mode !== 'test' ? 'readonly':'' !!}
                   id="docusign_test_username" class="form-control"
                   value="{{ data_get($setting->values,'docusign_test_username', config('powwr.docusign_test_username')) }}" placeholder="Enter user name">
        </div>
    </div>
    <div class="col-md-6 dc-api-settings dc-api-live" {!! $dc_api_mode !== 'live' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="docusign_password">Password</label>
            <input type="text" name="values[docusign_password]" required
                   {!! $dc_api_mode !== 'live' ? 'readonly':'' !!}
                   id="docusign_password" class="form-control"
                   value="{{ data_get($setting->values,'docusign_password', config('powwr.docusign_password')) }}" placeholder="Enter password">
        </div>
    </div>
    <div class="col-md-6 dc-api-settings dc-api-test" {!! $dc_api_mode !== 'test' ? 'style="display:none"':'' !!}>
        <div class="form-group">
            <label for="docusign_test_password">Test Password</label>
            <input type="text" name="values[docusign_test_password]"
                   {!! $dc_api_mode !== 'test' ? 'readonly':'' !!}
                   required id="docusign_test_password" class="form-control"
                   value="{{ data_get($setting->values,'docusign_test_password', config('powwr.docusign_test_password')) }}" placeholder="Enter password">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="docusign_loa_template">DocuSign LOA Template</label>
            <input type="text" name="values[docusign_loa_template]" required
                   id="docusign_loa_template" class="form-control"
                   value="{{ data_get($setting->values,'docusign_loa_template', config('powwr.docusign_loa_template')) }}" plaocehlder="Enter template name">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="docusign_loa_template_eonnext">DocuSign LOA Template for EON-Next</label>
            <input type="text" name="values[docusign_loa_template_eonnext]" required id="docusign_loa_template_eonnext" class="form-control"
                   value="{{ data_get($setting->values,'docusign_loa_template_eonnext', config('powwr.docusign_loa_template_eonnext')) }}" placeholder="Enter template name">
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function () {
            $('.api_mode_field').on('click', function () {
                var _val = $(this).val();
                $('.api-settings').hide().find('input').attr('readonly',true);
                $('.api-' + _val).show().find('input').attr('readonly',false);
            });
            $('.dc_api_mode_field').on('click', function () {
                var _val = $(this).val();
                $('.dc-api-settings').hide().find('input').attr('readonly',true);
                $('.dc-api-' + _val).show().find('input').attr('readonly',false);
            });
        });
    </script>
@endpush
