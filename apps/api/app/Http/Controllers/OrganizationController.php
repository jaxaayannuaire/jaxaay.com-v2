<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\OrganizationService;
use App\Support\CurrentOrganization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $r)
    {
        return OrganizationResource::collection($r->user()->organizations()->get());
    }

    public function store(CreateOrganizationRequest $r, OrganizationService $s)
    {
        return (new OrganizationResource($s->create($r->user(), $r->validated('name'))))->response()->setStatusCode(201);
    }

    public function show(Request $r, Organization $organization)
    {
        abort_unless($r->user()->can('view', $organization), 403);

        return new OrganizationResource($organization->load('users'));
    }

    public function context(CurrentOrganization $current)
    {
        return ['data' => new OrganizationResource($current->get())];
    }
}
