<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::orderBy('name')->pluck('name');

        return response()->json($roles);
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
