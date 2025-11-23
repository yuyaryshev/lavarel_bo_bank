<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    public const OPERATION_DEPOSIT = 'd';
    public const OPERATION_TRANSFER = 't';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'transactions';
    public $timestamps = false;

	protected static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			if (!$model->id) {
				$model->id = (string) \Illuminate\Support\Str::uuid();
			}
		});
	}
	
    protected $fillable = [
        'dt',
        'user_from_id',
        'user_to_id',
        'text',
        'amount',
        'operation_type',
        'created_at',
    ];

    protected $casts = [
        'dt' => 'date',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];
}
