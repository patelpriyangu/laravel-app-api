<?php

namespace App\Models;

use App\Services\LoanApplicationService;
use App\Services\LoanPaymentService;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    public $table = 'loan_applications';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'loan_amount',
        'loan_term',
        'description',
        'status',
        'user_id',
        'approved_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * approvedUser
     *
     * @return User
     */
    public function approvedUser()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
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

    /**
     * weeklyPaymentAmount
     *
     * @return void
     */
    public function weeklyPaymentAmount()
    {
        //$loanApplicationService = LoanApplicationService::getInstance();
        $loanApplicationService = app()->make(LoanApplicationService::class);
        return $loanApplicationService->weeklyPaymentCalculator($this->loan_amount, $this->loan_term);
    }

    /**
     * orders
     *
     * @return void
     */
    public function orders()
    {
        return $this->hasMany(LoanPaymentOrder::class, 'loan_application_id');
    }

    /**
     * paidAmountTotal
     *
     * @return void
     */
    public function paidAmountTotal()
    {
        return LoanPaymentOrder::where([
            'loan_application_id' => $this->id, 'status' => LoanPaymentService::COMPLETED_STATUS,
        ])->sum('amount');
    }

    public function loanPaymentOrder()
    {
        return $this->hasMany(LoanPaymentOrder::class, 'loan_application_id');
    }
}
