<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ShippingCourierProperties extends Model
{
    protected $table = "tl_com_shipping_courier_properties";

    protected $fillable = [
        'shipping_courier_id', 
        'api_key', 
        'api_secret', 
        'branch_id'
    ];

    /**
     * Get the courier that owns these properties.
     */
    public function courier()
    {
        return $this->belongsTo(ShippingCourier::class, 'shipping_courier_id');
    }

    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = trim((string) $value);
    }

    public function setBranchIdAttribute($value)
    {
        $this->attributes['branch_id'] = trim((string) $value);
    }

    /**
     * Automatically encrypt the secret when saving and decrypt when retrieving.
     */
    public function setApiSecretAttribute($value)
    {
        $this->attributes['api_secret'] = Crypt::encryptString(trim((string) $value));
    }

    public function getApiSecretAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Return plain if not encrypted (useful during migration)
        }
    }
}
