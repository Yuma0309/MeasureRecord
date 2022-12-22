<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ScopeAuth;

class Record extends Model
{
    use HasFactory;

    Public function title()
    {
        // "Title"モデルのデータを取得する
        return $this->belongsTo('App\Models\Title');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ScopeAuth); // ログインしているユーザーの絞り込み
    }
}
