<?php

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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('login');
            $table->string('password');
            $table->timestamps();
        });
        $hash_password = \Illuminate\Support\Facades\Hash::make('admin');
        \Illuminate\Support\Facades\DB::table('admins')
            ->insert([
                'name' => 'Adminstrator',
                'login' => 'zavuch',
                'password' => $hash_password
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
