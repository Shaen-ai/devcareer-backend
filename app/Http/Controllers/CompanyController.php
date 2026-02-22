<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(): JsonResponse
    {
        $companies = Company::orderBy('name')->pluck('name');

        return response()->json($companies);
    }

    public function createOrUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
        ]);

        $company = Company::firstOrCreate(
            ['name' => trim($validated['name'])]
        );

        return response()->json([
            'name' => $company->name,
            'created' => $company->wasRecentlyCreated,
        ], $company->wasRecentlyCreated ? 201 : 200);
    }
}
