<?php

namespace App\Http\Controllers;

use App\Enums\BillingCycle;
use App\Exceptions\SubscriptionException;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use App\Support\CurrentOrganization;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function current(Request $request, CurrentOrganization $currentOrganization)
    {
        $subscription = $currentOrganization->get()->currentSubscription();
        if (! $subscription) {
            return response()->json([
                'error' => [
                    'code' => 'SUBSCRIPTION_NOT_FOUND',
                    'message' => 'Aucune souscription courante.',
                ],
            ], 404);
        }

        abort_unless($request->user()->can('view', $subscription), 403);

        return new SubscriptionResource($subscription->load('plan'));
    }

    public function store(
        CreateSubscriptionRequest $request,
        CurrentOrganization $currentOrganization,
        SubscriptionService $service,
    ) {
        $organization = $currentOrganization->get();
        abort_unless($request->user()->can('create', [Subscription::class, $organization]), 403);

        try {
            $subscription = $service->create(
                $organization,
                $request->validated('plan'),
                BillingCycle::from($request->validated('billing_cycle')),
            );
        } catch (SubscriptionException $exception) {
            return response()->json([
                'error' => [
                    'code' => $exception->errorCode,
                    'message' => $exception->getMessage(),
                ],
            ], $exception->statusCode);
        }

        return (new SubscriptionResource($subscription))->response()->setStatusCode(201);
    }
}
