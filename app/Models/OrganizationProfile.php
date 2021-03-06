<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProfile extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getLogoAttribute($key)
    {
        return asset('logos/'.$key);
    }
}
