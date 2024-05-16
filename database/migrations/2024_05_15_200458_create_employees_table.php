<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('id_empleado');
            $table->string('primer_apellido', 20);
            $table->string('segundo_apellido', 20);
            $table->string('primer_nombre', 20);
            $table->string('otros_nombres', 50)->nullable();
            $table->string('tipo_identificacion', 50);
            $table->string('numero_identificacion', 20);
            $table->string('correo_electronico', 300);
            $table->enum('pais_empleo', ['Colombia', 'Estados Unidos']);
            $table->date('fecha_ingreso');
            $table->string('area', 50);
            $table->string('estado', 10)->default('Activo');
            $table->timestamp('fecha_registro')->useCurrent(); // Use 'useCurrent()' instead of DB::raw('CURRENT_TIMESTAMP')
            $table->unique(['tipo_identificacion', 'numero_identificacion']);
                    $table->timestamps();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
