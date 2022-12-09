<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class InhouseService extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'checkin_time',
        'checkout_time'
    ];

    public function inhouse()
    {
        return $this->belongsTo(Inhouse::class);
    }

    public function serviceStaff()
    {
        return $this->belongsTo(ServiceStaff::class);
    }

    public function carbonCheckin(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->checkin_time)
        );
    }
}
