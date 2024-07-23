<?php

namespace App\Models;

use App\Http\Integrations\Powwr\Requests\SuppliersRequest;
use App\Http\Integrations\Powwr\UdCoreApiConnector;
use App\Models\Traits\ActiveTrait;
use App\Observers\SupplierObserver;
use App\Traits\UtilsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Suppliers extends Model
{
    use UtilsTrait, ActiveTrait;

    protected $appends = ['logo_url'];

    protected $fillable = [
        'name',
        'slug',
        'powwr_id',
        'logo',
        'supplier_type',
        'uplifts',
        'plans',
        'status',
    ];
    protected $casts = [
        'plans' => 'array',
        'uplifts' => 'array'
    ];

    public static $preferred_suppliers = [
        'British Gas',
        'British Gas Lite',
        //'Corona Energy',
        //'Drax',
        'EDF Energy',
        'E-ON Next',
        'Opus',
        'Scottish Power',
        'Smartest Energy',
    ];

    protected static function boot()
    {
        parent::boot();

        Suppliers::observe(SupplierObserver::class);
    }

    public function getLogoUrlAttribute($value)
    {
        if ($this->logo) {
            if (Storage::exists($this->logo)) {
                return Storage::url($this->logo);
            } elseif (Storage::exists('suppliers/' . $this->logo)) {
                return Storage::url('suppliers/' . $this->logo);
            } elseif (file_exists(public_path('images/logos/' . $this->logo))) {
                return url('images/logos/' . $this->logo);
            } elseif (file_exists(public_path($this->logo))) {
                return url($this->logo);
            } else {
                return '';
            }
        }
        return null;
    }

    public function setLogoAttribute($value)
    {
        $attribute_name = 'logo';

        $old_logo = $this->{$attribute_name};

        // Path
        $destination_path = 'suppliers';

        // If the image was erased
        if (empty($value)) {
            // delete the image from disk
            Storage::disk('public')->delete($destination_path . '/' . $old_logo);
            $this->attributes[$attribute_name] = null;
            return false;
        }

        // If laravel request->file('filename') resource OR base64 was sent, store it in the db
        try {
            if ($value instanceof UploadedFile) {

                // Get file extension
                $extension = $value->getClientOriginalExtension();
                if (empty($extension)) {
                    $extension = 'jpg';
                }

                // Image default sizes
                $width = 300;
                $height = 300;

                // Make the image
                $image = Image::make($value)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($extension, 100);

                $filename = md5(microtime(true)) . '.' . $extension;

                // Store the image on disk.
                Storage::disk('public')->put($destination_path . '/' . $filename, $image->stream());

                $this->attributes[$attribute_name] = $filename;

                if ($old_logo && Storage::disk('public')->exists($old_logo)) {
                    Storage::disk('public')->delete($old_logo);
                }
            } else {
                $this->attributes[$attribute_name] = $value;
            }
        } catch (\Exception $e) {
            alert_message($e->getMessage());
            $this->attributes[$attribute_name] = null;
            return false;
        }
    }

    public static function apiSuppliers($SupplierName = null, $Utility = null)
    {

        $cache_key = 'pricechange.' . Str::slug($SupplierName ?: 'all');

        $cachedList = Cache::get($cache_key);
        if (!empty($cachedList)) {
            return $cachedList;
        }

        $connector = new UdCoreApiConnector();

        $api_request = new SuppliersRequest(['SupplierName' => $SupplierName]);

        $api_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $result = $connector->send($api_request);

        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200) {
            $list = $response_body['GetLatestPriceChangeResult']['PriceChanges'] ?? [];

            if (!empty($list)) {
                $list = collect($list)->when($Utility, function ($collection, $Utility) {
                    return $collection->reject(function ($item) use ($Utility) {
                        return $item['Utility'] != ucfirst($Utility);
                    });
                })
                    ->groupBy('Supplier')
                    ->toArray();
                ksort($list);
                Cache::put($cache_key, $list, (60 * 60 * 2));
            }

            return $list;
        }

        return $response_body;
    }

}
