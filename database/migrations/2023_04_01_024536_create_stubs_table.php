<?php

use App\Models\Report;
use App\Models\Source;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stubs', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->foreignIdFor(Source::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('from'); // audio time
            $table->unsignedInteger('to'); // audio time
            $table->string('prompt')->nullable();
            $table->string('filename')->nullable(); // Only null until file is ready

            $table->timestamps();

            $table->foreignIdFor(Report::class)->nullable();

            $table->primary(['id', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stubs');
    }
};
