<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Transaction;

class Order extends Model
{
    use HasFactory;

    public function user () {
        return $this->belongsTo (User::class, "user_id");
    }

    public function orderitems () {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions () {
        return $this->hasMany(Transaction::class);
    }
}
