<?php

namespace App\Http\Requests;

use App\Enums\BillingCycle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan' => ['required', 'string'],
            'billing_cycle' => ['required', 'string', Rule::in(BillingCycle::values())],
            'plan_id' => ['prohibited'],
            'organization_id' => ['prohibited'],
            'price' => ['prohibited'],
            'currency' => ['prohibited'],
            'status' => ['prohibited'],
            'starts_at' => ['prohibited'],
            'ends_at' => ['prohibited'],
        ];
    }
}
