<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->string('password')->nullable();
            $table->string('qr_style')->nullable(); // store QR customization options
            $table->integer('max_clicks')->nullable();
            $table->json('qr_options')->nullable(); // store additional QR options
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn(['password', 'qr_style', 'max_clicks', 'qr_options']);
        });
    }
};
