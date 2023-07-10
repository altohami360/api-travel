<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'travels';

    public $fillable = [
        'name',
        'slug',
        'description',
        'is_public',
        'number_of_days'
    ];

    public function tours()
    {
        return $this->hasMany(Travel::class);
    }

    public function numberOfNight()
    {
        return Attribute::make(
            get: fn ($value, $attribute) => $attribute['number_of_days'] - 1
        );
    }
}
