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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true); // Cambiado para que sea auto-incremental y renombrado a user_id
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('documento', function (Blueprint $table) {
            $table->string('cod_doc', 36);
            $table->primary('cod_doc'); // Estableciendo 'cod_doc' como clave primaria
            $table->integer('id'); // Asegúrate de que este cambio se refleje en todas las tablas que lo referencian.
            $table->date('fecha');
            $table->string('tipo');
            // Asegúrate de que 'user_id' en 'users' esté correctamente definido como se mencionó antes.
            $table->foreign('id')->references('id')->on('users');
        });
        

        // Las demás definiciones de tabla permanecen iguales.

        Schema::create('doc_acad', function (Blueprint $table) {
            $table->string('cod_acad', 36);
            $table->primary('cod_acad'); // Estableciendo como clave primaria
            $table->string('cod_doc', 36);
            $table->date('fecha');
            $table->foreign('cod_doc')->references('cod_doc')->on('documento');
        });

        Schema::create('doc_admin', function (Blueprint $table) {
            $table->string('cod_admin', 36);
            $table->primary('cod_admin'); // Estableciendo como clave primaria
            $table->string('cod_doc', 36);
            $table->date('fecha');
            $table->foreign('cod_doc')->references('cod_doc')->on('documento');
        });

        Schema::create('alumno', function (Blueprint $table) {
            $table->integer('dni')->unique(); // Manteniendo dni como integer y marcándolo como unsigned
            $table->primary('dni'); // Estableciendo como clave primaria
            $table->string('cod_acad', 36);
            $table->string('nombre');
            $table->string('carrera');
            $table->foreign('cod_acad')->references('cod_acad')->on('doc_acad');
        });

        Schema::create('doc_alumno', function (Blueprint $table) {
            $table->string('id', 36); // Cambiado de id() a varchar
            $table->primary('id'); // Estableciendo como clave primaria
            $table->integer('dni'); // Referencia a alumno sin cambios ya que se mantiene como integer
            $table->string('grado');
            $table->string('tipo');
            $table->string('ruta');
            $table->string('caja');
            $table->text('observaciones')->nullable();
            $table->foreign('dni')->references('dni')->on('alumno');
        });

        Schema::create('silabo', function (Blueprint $table) {
            $table->string('id', 36); // Cambiado de id() a varchar
            $table->primary('id'); // Estableciendo como clave primaria
            $table->string('cod_admin', 36);
            $table->year('año');
            $table->string('carrera');
            $table->string('curso');
            $table->string('ruta');
            $table->foreign('cod_admin')->references('cod_admin')->on('doc_admin');
        });

        Schema::create('resolucion', function (Blueprint $table) {
            $table->string('id', 36); // Cambiado de id() a varchar
            $table->primary('id'); // Estableciendo como clave primaria
            $table->string('cod_admin', 36);
            $table->string('motivo');
            $table->string('ruta');
            $table->string('numero');
            $table->year('año');
            $table->foreign('cod_admin')->references('cod_admin')->on('doc_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('documento');
        Schema::dropIfExists('doc_acad');
        Schema::dropIfExists('doc_admin');
        Schema::dropIfExists('alumno');
        Schema::dropIfExists('doc_alumno');
        Schema::dropIfExists('silabo');
        Schema::dropIfExists('resolucion');
    }
};
