<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            // Relacion con el post que lleva este adjunto
            $table->foreignId('post_id')
                ->constrained()
                ->onDelete('cascade');

            // Información del archivo
            $table->string('mime_type',100);                //Tipo MIME (image/jpeg, etc)
            $table->string('path',500);                     //Ruta donde se guardó
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
