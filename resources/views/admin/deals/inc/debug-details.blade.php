{{--<h4 data-toggle="collapse" data-target="#collapseDealApi" aria-expanded="false" aria-controls="collapsePayment">
Deal API Payload
</h4>
<div id="collapseDealApi" class="collapse" aria-labelledby="headingDealApi">
<code><pre style="font-size: 100%;">
{!! json_encode($deal->getBody($deal), JSON_PRETTY_PRINT)  !!}
</pre>
</code>
</div>--}}

<h4 data-toggle="collapse" data-target="#collapseDocuSignApi" aria-expanded="false" aria-controls="collapsePayment">
    Contract <small>Request Json/API Payload</small>
</h4>
<div id="collapseDocuSignApi" class="collapse" aria-labelledby="headingDocuSignApi">
    <?php
    $data = $deal->docuSignBody($deal);
    $docusign_request = new \App\Http\Integrations\Powwr\Requests\DocuSignRequest($data);
    //dump($docusign_request->body()->all());
    ?>
<code><pre style="font-size: 100%;">
{!! json_encode($docusign_request->body()->all(), JSON_PRETTY_PRINT)  !!}
</pre>
</code>
</div>
<h4 data-toggle="collapse" data-target="#collapseLoaApi" aria-expanded="false" aria-controls="collapsePayment">
    LOA <small>Request Json/API Payload</small>
</h4>
<div id="collapseLoaApi" class="collapse" aria-labelledby="headingLoaApi">
    <?php
    $data = $deal->docuSignBody($deal,'loa');
    $loa_request = new \App\Http\Integrations\Powwr\Requests\SendLoaRequest($data);
    //dump($docusign_request->body()->all());
    ?>
<code><pre style="font-size: 100%;">
{!! json_encode($loa_request->body()->all(), JSON_PRETTY_PRINT)  !!}
</pre>
</code>
</div>

{{--@dump($deal->docuSignBody($deal))--}}
{{--@dump($deal->docuSignBody($deal,'loa'))--}}
{{--@dump($deal->getBody($deal))--}}
@dump($deal->toArray())
