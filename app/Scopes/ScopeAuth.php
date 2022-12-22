<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth; //認証モデルを使用する

class ScopeAuth implements Scope
{
    Public function apply(Builder $builder, Model $model)
    {
        // ログインしているユーザーの絞り込み
        $userId = Auth::user()->id;
        $builder->where('user_id', $userId);
    }
}
