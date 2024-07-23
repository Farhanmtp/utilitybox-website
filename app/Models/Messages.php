<?php

namespace App\Models;

use App\Traits\UtilsTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use UtilsTrait;

    protected $casts = [
        'attachment' => 'array',
    ];

    protected function attachment(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (isJson($value)){
                    $value = json_decode($value, true);
                }
                $output = [];
                if ($value) {
                    foreach ((array)$value as $file) {
                        if (storage()->exists('forms/' . $file)) {
                            $output[] = storage()->url('forms/' . $file);
                        }
                    }
                }
                return $output;
            },
        );
    }
}
