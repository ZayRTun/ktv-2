<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Inhouse extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $dates = [
    //     'arrival',
    //     'departure'
    // ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function inhouseServices()
    {
        return $this->hasMany(InhouseService::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function table()
    {
        return $this->hasOneThrough(Table::class, Room::class, 'id', 'id', 'room_id', 'id');
    }

    public function incomeTransactions()
    {
        return $this->hasMany(IncomeTransaction::class);
    }

    public function viewInformationInvoices()
    {
        return $this->hasMany(ViewInformationInvoice::class);
    }

    public function viewInformationInvoiceCustomers()
    {
        return $this->hasMany(ViewInformationInvoiceCustomer::class);
    }

    public function carbonArrival(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->arrival)
        );
    }

    public function carbonDeparture(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->departure)
        );
    }
}
