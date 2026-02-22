<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private const DEFAULTS = [
        'EPAM Systems',
        'Synopsys Armenia',
        'ServiceTitan',
        'Picsart',
        'Digitain',
        'SoftConstruct',
        'BetConstruct',
        'Krisp',
        'Renderforest',
        'DataArt',
        'Menu Group',
        'SuperAnnotate',
        'CodeSignal',
        'Joomag',
        'PandaDoc',
        'Adobe',
        'National Instruments',
        'TeamViewer',
        'Aarki',
        'Simply Technologies',
    ];

    public function index(): JsonResponse
    {
        $fromDb = Company::orderBy('name')->pluck('name')->all();

        $merged = collect(self::DEFAULTS)
            ->merge($fromDb)
            ->unique()
            ->sort()
            ->values();

        return response()->json($merged);
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
