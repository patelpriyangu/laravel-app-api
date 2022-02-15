<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'loan_amount' => (float) $this->loan_amount,
            'loan_term' => (float) $this->loan_term,
            'weekly_payment_amount' => (float) $this->weeklyPaymentAmount(),
            'paid_amount_total' => (float) $this->paidAmountTotal(),
            'repayment_total' => $this->orders->count(),
            'description' => $this->description,
            'status' => $this->status,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
