<?php

namespace App\Http\Controllers;

use App\Models\SalarySubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalarySubmissionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Honeypot check — bots fill hidden fields
        if ($request->filled('website')) {
            return response()->json(['message' => 'Thank you!'], 200);
        }

        $validated = $request->validate([
            'claimToken'      => ['required', 'uuid', 'unique:salary_submissions,claim_token'],
            'role'            => ['required', 'string', 'max:50'],
            'level'           => ['required', Rule::in(['Junior', 'Mid', 'Senior', 'Lead', 'Manager'])],
            'experienceYears' => ['required', 'integer', 'min:0', 'max:40'],
            'salaryAmount'    => ['required', 'integer', 'min:1', 'max:100000000'],
            'currency'        => ['required', Rule::in(['AMD', 'USD', 'EUR'])],
            'period'          => ['required', Rule::in(['Monthly', 'Yearly'])],
            'netOrGross'      => ['required', Rule::in(['Net', 'Gross'])],
            'location'        => ['required', 'string', 'max:50'],
            'companyName'     => ['nullable', 'string', 'max:150'],
            'contractType'    => ['nullable', Rule::in(['Employee', 'Contractor'])],
            'techTags'        => ['nullable', 'array'],
            'techTags.*'      => ['string', 'max:50'],
        ]);

        $submission = SalarySubmission::create([
            'claim_token'      => $validated['claimToken'],
            'role'             => $validated['role'],
            'level'            => $validated['level'],
            'experience_years' => $validated['experienceYears'],
            'salary_amount'    => $validated['salaryAmount'],
            'currency'         => $validated['currency'],
            'period'           => $validated['period'],
            'net_or_gross'     => $validated['netOrGross'],
            'location'         => $validated['location'],
            'company_name'     => $validated['companyName'] ?? null,
            'contract_type'    => $validated['contractType'] ?? null,
            'tech_tags'        => $validated['techTags'] ?? null,
        ]);

        return response()->json([
            'message' => 'Submission saved successfully.',
            'id'      => $submission->id,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $query = SalarySubmission::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }
        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }
        if ($request->filled('location')) {
            $query->where('location', $request->input('location'));
        }
        if ($request->filled('currency')) {
            $query->where('currency', $request->input('currency'));
        }
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->input('contract_type'));
        }

        $submissions = $query
            ->select([
                'id',
                'role',
                'level',
                'experience_years',
                'salary_amount',
                'currency',
                'period',
                'net_or_gross',
                'location',
                'company_name',
                'contract_type',
                'tech_tags',
                'created_at',
            ])
            ->orderByDesc('created_at')
            ->paginate($request->input('per_page', 25));

        return response()->json($submissions);
    }

    public function stats(): JsonResponse
    {
        $total = SalarySubmission::count();

        $byRole = SalarySubmission::selectRaw('role, COUNT(*) as count, AVG(salary_amount) as avg_salary')
            ->groupBy('role')
            ->orderByDesc('count')
            ->get();

        $byLevel = SalarySubmission::selectRaw('level, COUNT(*) as count, AVG(salary_amount) as avg_salary')
            ->groupBy('level')
            ->orderByDesc('count')
            ->get();

        $byLocation = SalarySubmission::selectRaw('location, COUNT(*) as count')
            ->groupBy('location')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'total_submissions' => $total,
            'by_role'           => $byRole,
            'by_level'          => $byLevel,
            'by_location'       => $byLocation,
        ]);
    }
}
