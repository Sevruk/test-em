<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создаёт таблицы для хранения кеша в базе данных.
     * Использует длину ключа 191, чтобы избежать ошибки:
     * "Specified key was too long; max key length is 1000 bytes"
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key', 191)->primary(); // Уменьшено с 255 до 191
            $table->mediumText('value');
            $table->integer('expiration')->unsigned();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key', 191)->primary(); // То же самое для ключа
            $table->string('owner');
            $table->integer('expiration')->unsigned();
        });
    }

    /**
     * Удаляет таблицы кеша.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};