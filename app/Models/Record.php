<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    Public function titles()
    {
        // "Title"モデルのデータを取得する
        return $this->belongsTo('App\Models\Title');
    }
}
