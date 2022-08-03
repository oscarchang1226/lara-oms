<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakerModel extends Model
{
    use HasFactory;

    protected $fillable = ['maker_id', 'name', 'year'];

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }
}
