<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RefreshToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Автогенерация token при создании
    protected static function booted()
    {
        static::creating(function ($refreshToken) {
            if (empty($refreshToken->token)) {
                $refreshToken->token = Hash::make(Str::random(64));
            }
            if (empty($refreshToken->expires_at)) {
                $refreshToken->expires_at = now()->addMinutes(config('auth.refresh_token_lifetime'));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function regenerate(): string
    {
        $this->token = Hash::make(Str::random(64));
        $this->expires_at = now()->addMinutes(config('auth.refresh_token_lifetime'));
        $this->save();

        return $this->token;
    }
}
