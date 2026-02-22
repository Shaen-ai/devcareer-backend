<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_submissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('claim_token')->unique();

            // Core salary info
            $table->string('role', 50);
            $table->enum('level', ['Junior', 'Mid', 'Senior', 'Lead', 'Manager']);
            $table->unsignedTinyInteger('experience_years');
            $table->unsignedBigInteger('salary_amount');
            $table->enum('currency', ['AMD', 'USD', 'EUR'])->default('AMD');
            $table->enum('period', ['Monthly', 'Yearly'])->default('Monthly');
            $table->enum('net_or_gross', ['Net', 'Gross'])->default('Net');
            $table->string('location', 50);

            // Optional fields
            $table->string('company_name', 150)->nullable();
            $table->enum('contract_type', ['Employee', 'Contractor'])->nullable();
            $table->json('tech_tags')->nullable();

            $table->timestamps();

            // Indexes for common query patterns
            $table->index('role');
            $table->index('level');
            $table->index('location');
            $table->index('currency');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_submissions');
    }
};
