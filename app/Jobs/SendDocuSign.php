<?php

namespace App\Jobs;

use App\Models\Deals;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDocuSign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(protected Deals $deal)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $deal = $this->deal;

        try {
            $response = $deal->sendLoa($deal);
            if ($response['success']) {
                $envelopeId = $response['envelopeId'] ?? '';
                if ($envelopeId) {
                    $deal->loaEnvelopeId = $envelopeId;
                    $deal->save();
                }
            }
        } catch (\Exception $e) {
        }

        $response = $deal->sendDocuSign($deal);
        if ($response['success']) {
            $envelopeId = $response['envelopeId'] ?? '';
            if ($envelopeId) {
                $deal->envelopeId = $envelopeId;
                $deal->save();
            }
        }
    }
}
