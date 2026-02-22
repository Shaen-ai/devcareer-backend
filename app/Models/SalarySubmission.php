<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySubmission extends Model
{
    protected $fillable = [
        'claim_token',
        'role',
        'level',
        'experience_years',
        'salary_amount',
        'currency',
        'period',
        'net_or_gross',
        'company_name',
        'tech_tags',
    ];

    protected function casts(): array
    {
        return [
            'tech_tags' => 'array',
            'experience_years' => 'integer',
            'salary_amount' => 'integer',
        ];
    }
}
