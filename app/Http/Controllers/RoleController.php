<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private const DEFAULTS = [
        'DevOps',
        'Backend',
        'Frontend',
        'Fullstack',
        'QA',
        'Mobile',
        'Data Engineer',
        'Security',
        'Engineering Manager',
        'Project Manager',
    ];

    public function index(): JsonResponse
    {
        $fromDb = Role::orderBy('name')->pluck('name')->all();

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
            'name' => ['required', 'string', 'max:50'],
        ]);

        $role = Role::firstOrCreate(
            ['name' => trim($validated['name'])]
        );

        return response()->json([
            'name' => $role->name,
            'created' => $role->wasRecentlyCreated,
        ], $role->wasRecentlyCreated ? 201 : 200);
    }
}
