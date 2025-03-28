<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Account extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'user_id', 'account_name', 'account_number',
        'account_type', 'currency', 'balance'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($account) {
            do {
                $account->account_number = generateLuhnNumber(12);
            } while (self::where('account_number', $account->account_number)->exists());
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

