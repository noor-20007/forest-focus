<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('focus_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('duration'); // بالدقائق
            $table->boolean('success');
            $table->timestamp('session_date');
            $table->timestamps();

            $table->index(['user_id', 'session_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('focus_sessions');
    }
};
