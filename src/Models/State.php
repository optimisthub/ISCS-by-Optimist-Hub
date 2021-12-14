<?php

namespace Optimisthub\LCSC\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->belongsTo(State::class)->orderBy("name");
    }

    public static function getByCountryId(int $id)
    {
        return self::query()->where("country_id", $id)->get();
    }

    public static function getByCountryCode(string $code)
    {
        return self::query()->whereHas("country", function ($query) use ($code) {
            return $query->where("iso3", $code)->orWhere("iso2", $code);
        })->get();
    }
}
