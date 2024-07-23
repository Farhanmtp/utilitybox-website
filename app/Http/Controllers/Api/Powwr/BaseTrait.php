<?php

namespace App\Http\Controllers\Api\Powwr;

trait BaseTrait
{

    public function PowwrErrors($response_body)
    {
        $errors = [];
        $_errors = data_get($response_body, 'extensions.errors');

        if (is_array($_errors)) {
            foreach ($_errors as $error) {
                $desc = data_get($error, 'description');
                $type = data_get($error, 'type');
                if ($type) {
                    $desc .= ' - ' . $type;
                }

                $properties = data_get($error, 'properties', []);
                if (!empty($properties)) {
                    $path = data_get($properties[0] ?? [], 'path');
                    $path = str_replace('.', ' ', $path);

                    if ($path) {
                        $desc .= ' - (' . $path . ')';
                    }
                }
                $errors[] = $desc;
            }
        }

        return $errors;
    }

}
