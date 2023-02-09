<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Meeting extends Model
{
    use HasFactory;

    protected $hidden = [
        'createBy',
    ];

     /**
     * Interact with the meeting's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  ["spot", "scheduled"][$value],
        );
    }
    protected function status(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  ["pending", "canceled", "waiting", "completed"][$value],
        );
    }
}
 