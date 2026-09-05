<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Support\CurrentOrganization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveOrganizationContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $publicId = $request->header('X-Organization-Id');
        if (! $publicId) {
            return response()->json(['error' => ['code' => 'ORGANIZATION_CONTEXT_REQUIRED', 'message' => 'Le contexte organisation est requis.']], 400);
        }
        $organization = Organization::where('public_id', $publicId)->first();
        if (! $organization) {
            return response()->json(['error' => ['code' => 'ORGANIZATION_NOT_FOUND', 'message' => 'Organisation introuvable.']], 404);
        }
        if (! $request->user()->organizations()->whereKey($organization->id)->exists()) {
            return response()->json(['error' => ['code' => 'ORGANIZATION_ACCESS_DENIED', 'message' => 'Accès organisation refusé.']], 403);
        }
        if ($organization->status !== 'active') {
            return response()->json(['error' => ['code' => 'ORGANIZATION_SUSPENDED', 'message' => 'Organisation suspendue.']], 403);
        }
        app(CurrentOrganization::class)->set($organization);

        return $next($request);
    }
}
