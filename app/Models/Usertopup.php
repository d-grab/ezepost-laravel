<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usertopup extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'currency',
      'amount',
      'is_topup'
    ];
}
