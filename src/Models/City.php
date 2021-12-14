<?php

namespace Optimisthub\LCSC\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public static function getByStateId(int $id)
    {
        return self::query()->where("state_id", $id)->get();
    }
}
