<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $fillable = ['name', 'price'];

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
