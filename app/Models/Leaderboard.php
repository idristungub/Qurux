<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Leaderboard extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }




}
