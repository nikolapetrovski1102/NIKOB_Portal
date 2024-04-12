<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transactions extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'transaction_id',
        'logs',
        'user_id',
        'amount',
        'notify',
        'type',
        'error_message',
    ];

    public function invoices() : HasMany
    {
        return $this->hasMany(Invoices::class, 'tid', 'id');
    }

    public function user() : HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function account() : HasMany
    {
        return $this->hasMany(UserAccounts::class, 'user_id', 'user_id');
    }
}
