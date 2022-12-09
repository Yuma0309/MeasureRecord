<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    Public function records()
    {
        // "App\Models\Record"モデルのデータを取得する
        return $this->hasMany('App\Models\Record');
    }
}
