<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Companyとのリレーション設定
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
