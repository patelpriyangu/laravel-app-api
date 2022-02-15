<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentChargeRequest;
use App\Http\Resources\LoanPaymentOrderResource;
use App\Models\LoanApplication;
use App\Services\LoanApplicationService;
use App\Services\LoanPaymentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class LoanPaymentController extends Controller
{
    use ApiResponseTrait;

    protected $loanPaymentService;

    /**
     * __construct
     *
     * @param LoanPaymentService $loanPaymentService
     * @return void
     */
    public function __construct(LoanPaymentService $loanPaymentService)
    {
        $this->middleware('auth:api', []);
        $this->loanPaymentService = $loanPaymentService;
    }

    /**
     * pay
     *
     * @param PaymentChargeRequest $request
     * @return void
     */
    public function pay(PaymentChargeRequest $request)
    {
        try {
            $user = auth()->user();

            $loanApplicationId = $request->post('loan_application_id');
            $loanApplication = LoanApplication::findOrFail($loanApplicationId);

            /**
             * @var LoanApplication $loanApplication
             */

            if ($loanApplication->status != LoanApplicationService::APPOVED_STATUS) {
                return $this->responseFailJson(__('loan.not_approved'));
            }

            if ($loanApplication->user_id != auth()->id()) {
                return $this->responseFailJson(__('messages.permission_denied'), null, Response::HTTP_FORBIDDEN);
            }

            $loanPaymentOrder = $this->loanPaymentService->add($user, $loanApplication, $loanApplication->weeklyPaymentAmount());
            return $this->responseSuccessJson(new LoanPaymentOrderResource($loanPaymentOrder));
        } catch (ModelNotFoundException $er) {
            return $this->responseFailJson(__('messages.item_not_found'));
        }
    }
}
