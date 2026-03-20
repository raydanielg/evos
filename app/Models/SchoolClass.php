<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'code',
        'sort_order',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
