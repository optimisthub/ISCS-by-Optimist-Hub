<?php

namespace Optimisthub\LCSC\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public static function list()
    {
        return self::query()->orderBy("name")->get();
    }

    public function states()
    {
        return $this->hasMany(State::class)->orderBy("name");
    }
}
