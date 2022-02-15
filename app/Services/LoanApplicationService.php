<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\User;

class LoanApplicationService
{
    public const PENDING_STATUS = 'pending';
    public const APPOVED_STATUS = 'approved';
    public const REJECTED_STATUS = 'rejected';
    public const DEFAULT_INTEREST_RATE = 9.0; // 9%/year

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
     * weeklyPaymentCalculator
     *
     * @param float $loanAmount
     * @param int $totalPayments
     * @param float $interest
     * @return void
     */
    public function weeklyPaymentCalculator(float $loanAmount, int $loanTerm, float $interestRate = null)
    {
        $interestRate = $interestRate ?? self::DEFAULT_INTEREST_RATE;
        $interestRatePerWeek = $interestRate / 5200;
        $value1 = $interestRatePerWeek * pow((1 + $interestRatePerWeek), $loanTerm);
        $value2 = pow((1 + $interestRatePerWeek), $loanTerm) - 1;
        $pmt = $loanAmount * ($value1 / $value2);
        return round($pmt, 2);
    }

    /**
     * add
     *
     * @param User $user
     * @param Array $params
     * @return LoanApplication|null
     */
    public function add(User $user, array $params)
    {
        return LoanApplication::create(array_merge([
            'user_id' => $user->id,
            'status' => self::PENDING_STATUS,
        ], $params));
    }

    /**
     * update
     *
     * @param LoanApplication $loanApplication
     * @param array $params
     * @return LoanApplication|null
     */
    public function update(LoanApplication $loanApplication, array $params = [])
    {
        if (!empty($params['status'])) {
            if (in_array($params['status'], [self::APPOVED_STATUS, self::REJECTED_STATUS])) {
                $params['approved_by_id'] = auth()->id();
            }
        }

        $loanApplication->update($params);
        return $loanApplication;
    }
}
