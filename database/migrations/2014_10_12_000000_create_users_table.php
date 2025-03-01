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
        Schema::create('alumno', function (Blueprint $table) {
            $table->id();
            $table->string('dni'); // Usando string por si el DNI tiene ceros al principio, agregado index para clave foránea
            $table->string('matricula_code'); // Usando string por si el código de matrícula tiene ceros al principio
            $table->string('nombre');
            $table->string('carrera');
            $table->string('caja');
            $table->integer('num_registros_caja')->default(0); // Nueva columna para contar registros por caja
            $table->text('observaciones')->nullable();
            $table->year('anio_bachiller')->nullable();
            $table->year('anio_titulo')->nullable();
            $table->year('anio_maestria')->nullable();
            $table->year('anio_doctorado')->nullable();
            $table->year('anio_especialidad1')->nullable();
            $table->year('anio_especialidad2')->nullable();
            $table->timestamps();
        });

        Schema::create('doc_alumno', function (Blueprint $table) {
            $table->id(); // Auto-incremental y clave primaria
            $table->unsignedBigInteger('alumno_id'); // Clave foránea que referencia al ID del alumno
            $table->string('grado');
            $table->string('tipo');
            $table->string('ruta'); // Almacena la ruta del archivo
            $table->timestamps(); // created_at y updated_at

            $table->foreign('alumno_id')->references('id')->on('alumno')->onDelete('cascade'); // Relación con la tabla alumno
        });

        Schema::create('silabo', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // Clave foránea que referencia al correo del usuario
            $table->string('facultad');
            $table->string('escuela');
            $table->timestamps();

            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
        });
        Schema::create('det_silabo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('silabo_id')->constrained('silabo')->onDelete('cascade'); // Referencia a la tabla silabo
            $table->year('anio');
            $table->string('carrera');
            $table->string('curso');
            $table->string('ruta'); // Ruta del documento
            $table->timestamps();
        });
        Schema::create('resolucion', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // Clave foránea que referencia al correo del usuario
            $table->string('tipo_res');
            $table->year('anio');
            $table->timestamps();
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
        });
        Schema::create('det_resolucion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resolucion_id')->constrained('resolucion')->onDelete('cascade'); // Referencia a la tabla resolucion
            $table->string('numero');
            $table->string('autor');
            $table->string('receptor');
            $table->string('motivo');
            $table->string('ruta'); // Ruta del documento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('alumno');
        Schema::dropIfExists('doc_alumno');
        Schema::dropIfExists('silabo');
        Schema::dropIfExists('det_silabo');
        Schema::dropIfExists('resolucion');
        Schema::dropIfExists('det_resolucion');
    }
};
