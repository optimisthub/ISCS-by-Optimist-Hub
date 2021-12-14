<?php

namespace Optimisthub\LCSC\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public static function list( array $select = [])
    {
        $query = self::query()->orderBy("name");
        if($select){
            $query->select($select);
        }

        return $query->get();
    }

    public function states()
    {
        return $this->hasMany(State::class)->orderBy("name");
    }
}
