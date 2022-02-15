<?php

namespace App\Http\Requests;

use App\Services\LoanApplicationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoanApplicationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $status = request()->post('status');
        if (!empty($status) && !in_array($status, [
            LoanApplicationService::PENDING_STATUS, LoanApplicationService::APPOVED_STATUS, LoanApplicationService::REJECTED_STATUS])) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'loan_amount' => 'required|numeric|gt:0',
            'loan_term' => 'required|integer|gt:0',
        ];
    }
}
