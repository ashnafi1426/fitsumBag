<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    protected $fillable = [
        'menu_item_id',
        'customer_phone',
        'description',
        'item_name',
        'item_image',
        'item_price',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'item_price' => 'decimal:2',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
