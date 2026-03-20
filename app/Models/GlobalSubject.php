<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalSubject extends Model
{
    protected $fillable = ['name', 'code'];

    public function userSubjects(): HasMany
    {
        return $this->hasMany(UserSubject::class);
    }
}
