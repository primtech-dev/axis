<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE expenses
            MODIFY status ENUM('OPEN','PARTIAL','PAID')
            NOT NULL DEFAULT 'OPEN'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE expenses
            MODIFY status ENUM('OPEN','PAID')
            NOT NULL DEFAULT 'OPEN'
        ");
    }
};
