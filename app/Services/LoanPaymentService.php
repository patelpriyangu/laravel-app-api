<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\LoanPaymentOrder;
use App\Models\User;

class LoanPaymentService
{
    public const PROCESSING_STATUS = 'processing';
    public const COMPLETED_STATUS = 'completed';

    protected static $instance;

    /**
     * Singleton creation
     *
     * @return LoanApplicationService
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * add
     *
     * @param User $user
     * @param mixed $loanAmount
     * @param mixed $loanTerm
     * @param mixed $description
     * @return LoanPaymentOrder
     */
    public function add(User $user, LoanApplication $loanApplication, $amount)
    {
        $loanPaymentOrder = LoanPaymentOrder::create([
            'user_id' => $user->id,
            'loan_application_id' => $loanApplication->id,
            'amount' => $amount,
            'status' => self::COMPLETED_STATUS,
        ]);
        return $loanPaymentOrder;
    }
}
