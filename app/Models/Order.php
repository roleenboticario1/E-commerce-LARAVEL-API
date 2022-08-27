<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
       'user_id',
       'firstname',
       'lastname',
       'phone',
       'email_address',
       'address',
       'city',
       'state',
       'zip_code',
       'payment_id',
       'payment_mode',
       'tracking_no',
       'status',
       'remark'
    ];

    public function orderitems(){
       return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }
}