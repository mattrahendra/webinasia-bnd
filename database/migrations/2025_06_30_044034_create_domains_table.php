<?php

// database/migrations/xxxx_create_domains_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('extension'); // .com, .id, .net, etc
            $table->decimal('price', 10, 2);
            $table->decimal('renewal_price', 10, 2);
            $table->enum('status', ['available', 'reserved', 'taken', 'expired']);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('reserved_until')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('godaddy_data')->nullable(); // Store GoDaddy response
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('domains');
    }
};
