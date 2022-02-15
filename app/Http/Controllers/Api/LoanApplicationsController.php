<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanApplicationStoreRequest;
use App\Http\Requests\LoanApplicationUpdateRequest;
use App\Http\Resources\LoanApplicationResource;
use App\Http\Resources\LoanApplicationResourceCollection;
use App\Models\LoanApplication;
use App\Models\LoanPaymentOrder;
use App\Services\LoanApplicationService;
use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class LoanApplicationsController extends Controller
{
    use ApiResponseTrait;

    protected $loanApplicationService;

    /**
     * __construct
     *
     * @param LoanApplicationService $loanApplicationService
     * @return void
     */
    public function __construct(LoanApplicationService $loanApplicationService)
    {
        $this->middleware('auth:api', []);
        $this->authorizeResource(LoanApplication::class, 'loan_application');
        $this->loanApplicationService = $loanApplicationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$collection = LoanApplication::where('user_id', auth()->id())->get();
        $collection = LoanApplication::all();
        return $this->responseSuccessJson(new LoanApplicationResourceCollection($collection));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LoanApplicationStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanApplicationStoreRequest $request)
    {
        $user = auth()->user();
        $params = $request->only(['loan_amount', 'loan_term', 'description']);
        $loanApplication = $this->loanApplicationService->add($user, $params);
        return $this->responseSuccessJson(new LoanApplicationResource($loanApplication));
    }

    /**
     * Display the specified resource.
     *
     * @param  LoanApplication  $loanApplication
     * @return \Illuminate\Http\Response
     */
    public function show(LoanApplication $loanApplication)
    {
        return $this->responseSuccessJson(new LoanApplicationResource($loanApplication));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LoanApplicationUpdateRequest  $request
     * @param  LoanApplication  $loanApplication
     * @return \Illuminate\Http\Response
     */
    public function update(LoanApplicationUpdateRequest $request, LoanApplication $loanApplication)
    {
        $params = $request->only(['loan_amount', 'loan_term', 'status']);
        // if ($request->user()->cannot('update', $loanApplication)) {
        //     return $this->responseFailJson(__('messages.permission_denied'), null, Response::HTTP_FORBIDDEN);
        // }
        $loanApplication = $this->loanApplicationService->update($loanApplication, $params);
        return $this->responseSuccessJson(new LoanApplicationResource($loanApplication));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LoanApplication  $loanApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanApplication $loanApplication)
    {
        $loanApplication->loanPaymentOrder()->delete();
        $loanApplication->delete();
        $data = "Loan Application Deleted Successfully";
        return $this->responseSuccessJson(null,$data);
    }
}
