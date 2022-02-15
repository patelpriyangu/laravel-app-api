<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPaymentOrder extends Model
{
    use HasFactory;

    public $table = 'loan_payment_order';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'amount',
        'description',
        'status',
        'user_id',
        'loan_application_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * loanApplication
     *
     * @return User
     */
    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application');
    }

    /**
     * user
     *
     * @return User|null
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
