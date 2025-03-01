<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\DocAlumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use PhpParser\Comment\Doc;

class DocumentAcademicController extends Controller
{


    // Método para obtener el último valor del contador de una caja específica
    public function obtenerContadorCaja($codigoCajaSuffix)
    {
        // Busca el último valor de 'num_registros_caja' para una caja específica
        $ultimoRegistro = Alumno::where('caja', 'LIKE', "%-$codigoCajaSuffix")
            ->orderBy('num_registros_caja', 'desc')
            ->first();

        // Si no se encuentra ningún registro, se retorna 0 como valor inicial
        $contador = $ultimoRegistro ? $ultimoRegistro->num_registros_caja : 0;

        return response()->json(['num_registros_caja' => $contador]);
    }

    // Método para registrar un alumno con los datos proporcionados
    public function registerStudent(Request $request)
    {
        $validatedData = $request->validate([
            'dni' => 'required|string',
            'matricula_code' => 'nullable|string',
            'nombre' => 'required|string',
            'carrera' => 'required|string',
            'caja' => 'required|string',
            'observaciones' => 'nullable|string',
            // Validaciones para los grados académicos
            'anio_bachiller' => 'nullable',
            'anio_titulo' => 'nullable',
            'anio_maestria' => 'nullable',
            'anio_doctorado' => 'nullable',
            'anio_especialidad1' => 'nullable',
            'anio_especialidad2' => 'nullable',
            'num_registros_caja' => 'required|integer',
        ]);

        // Asignar valores predeterminados para dni y matricula_code si están vacíos
        $dni = $validatedData['dni'];
        $matricula_code = $validatedData['matricula_code'] ?? '0000000000'; // 10 ceros si no se proporciona el código de matrícula

        // Verificar si el DNI no es ceros y ya existe un alumno con el mismo dni
        if ($dni !== '00000000' && Alumno::where('dni', $dni)->exists()) {
            return response()->json(['error' => 'El DNI ya está registrado en el sistema.'], 400);
        }

        // Verificar si el código de matrícula no es ceros y ya existe un alumno con el mismo código de matrícula
        if ($matricula_code !== '0000000000' && Alumno::where('matricula_code', $matricula_code)->exists()) {
            return response()->json(['error' => 'El código de matrícula ya está registrado en el sistema.'], 400);
        }

        try {
            // Guardar al alumno
            $alumno = Alumno::create([
                'dni' => $dni,
                'nombre' => strtoupper($validatedData['nombre']),
                'matricula_code' => strtoupper($matricula_code),
                'carrera' => strtoupper($validatedData['carrera']),
                'caja' => strtoupper($validatedData['caja']),
                'observaciones' => strtoupper($validatedData['observaciones']),
                'anio_bachiller' => $validatedData['anio_bachiller'],
                'anio_titulo' => $validatedData['anio_titulo'],
                'anio_maestria' => $validatedData['anio_maestria'],
                'anio_doctorado' => $validatedData['anio_doctorado'],
                'anio_especialidad1' => $validatedData['anio_especialidad1'],
                'anio_especialidad2' => $validatedData['anio_especialidad2'],
                'num_registros_caja' => $validatedData['num_registros_caja'] + 1, // Incrementar el contador
            ]);

            return response()->json(['message' => 'Registro de alumno y grados exitoso.', 'data' => $alumno], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el registro del alumno: ' . $e->getMessage()], 500);
        }
    }

    public function uploadDocuments(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|exists:alumno,nombre',
            'documentos' => 'required|array',
            'documentos.*.tipo' => 'required|string',
            'documentos.*.grado' => 'required|string',
            'documentos.*.documento' => 'required|file',
        ]);
    
        try {
            // Obtener el alumno por el nombre proporcionado
            $alumno = Alumno::where('nombre', $validatedData['nombre'])->first();
    
            // Verificar que se encontró un alumno
            if (!$alumno) {
                return response()->json(['error' => 'No se encontró un alumno con el nombre proporcionado.'], 404);
            }
    
            $alumnoId = $alumno->id; // Obtener el ID del alumno
    
            foreach ($validatedData['documentos'] as $doc) {
                $documentoFile = $doc['documento'];
    
                // Construye el nombre del archivo usando el tipo, grado y nombre del alumno
                $nombreArchivo = $doc['tipo'] . '_' . $doc['grado'] . '_' . $alumno->nombre . '.pdf';
    
                // Guarda el archivo en el disco 'public' y en la carpeta especificada con el nombre construido
                $ruta = $documentoFile->storeAs('public/documentos/academicos/' . $alumno->nombre, $nombreArchivo);
    
                // Crear el registro del documento asociado al alumno
                DocAlumno::create([
                    'alumno_id' => $alumnoId, // Rellenar con el ID del alumno
                    'grado' => strtoupper($doc['grado']),
                    'tipo' => strtoupper($doc['tipo']),
                    'ruta' => $ruta,
                ]);
            }
    
            return response()->json(['message' => 'Documentos subidos exitosamente.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al subir documentos: ' . $e->getMessage()], 500);
        }
    }
    
    

    public function getAcadDetails(Request $request)
    {
        $search = $request->query('search');
        $alumno = null;
        $documentos = [];
    
        // Buscar al alumno en las columnas 'id', 'matricula_code' y 'nombre'
        $alumno = Alumno::where('dni', $search)
            ->orWhere('matricula_code', $search)
            ->orWhere('nombre', 'like', "%{$search}%")
            ->first();
    
        // Si se encuentra un alumno, buscar los documentos asociados
        if ($alumno) {
            $documentos = DocAlumno::where('alumno_id', $alumno->id)->get();
        }
    
        return response()->json([
            'alumno' => $alumno,
            'documentos' => $documentos
        ]);
    }
    
    public function downloadAcadDocument($id)
    {
        try {
            // Encuentra el detalle del documento usando el ID
            $detail = DocAlumno::findOrFail($id);
            // Construye la ruta completa al archivo
            $pathToFile = storage_path('app/' . $detail->ruta);
    
            // Comprueba si el archivo existe
            if (!file_exists($pathToFile)) {
                return response()->json(['message' => 'File not found.'], 404);
            }
    
            // Devuelve el archivo para su descarga
            return response()->download($pathToFile);
        } catch (\Exception $e) {
            // Maneja la excepción y envía una respuesta JSON en caso de error
            return response()->json(['message' => 'Error downloading the document: ' . $e->getMessage()], 500);
        }
    }


    //A partir de aqui se modificaran los archivos


    public function updateStudent(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'dni' => 'required|string',
            'matricula_code' => 'required|string',
            'nombre' => 'required|string',
            'carrera' => 'required|string',
            'caja' => 'required|string',
            'observaciones' => 'nullable|string',
            // Validaciones para los grados académicos
            'anio_bachiller' => 'nullable',
            'anio_titulo' => 'nullable',
            'anio_maestria' => 'nullable',
            'anio_doctorado' => 'nullable',
            'anio_especialidad1' => 'nullable',
            'anio_especialidad2' => 'nullable',
        ]);
    
        $newDni = strtoupper($validatedData['dni']);
        $alumnoId = $validatedData['id'];
    
        DB::beginTransaction();
    
        try {
            // Encontrar al alumno por su ID
            $alumno = Alumno::findOrFail($alumnoId);
    
            // Verificar si el nuevo DNI ya está en uso, pero solo si ha cambiado
            if ($alumno->dni !== $newDni) {
                $existingAlumno = Alumno::where('dni', $newDni)->where('id', '!=', $alumnoId)->first();
                if ($existingAlumno) {
                    throw new \Exception('El nuevo DNI ya está en uso.');
                }
            }
    
            // Actualizar los datos del alumno
            $alumno->update([
                'dni' => $newDni,
                'nombre' => strtoupper($validatedData['nombre']),
                'carrera' => strtoupper($validatedData['carrera']),
                'matricula_code' => $validatedData['matricula_code'],
                'caja' => strtoupper($validatedData['caja']),
                'observaciones' => strtoupper($validatedData['observaciones']),
                'anio_bachiller' => $validatedData['anio_bachiller'],
                'anio_titulo' => $validatedData['anio_titulo'],
                'anio_maestria' => $validatedData['anio_maestria'],
                'anio_doctorado' => $validatedData['anio_doctorado'],
                'anio_especialidad1' => $validatedData['anio_especialidad1'],
                'anio_especialidad2' => $validatedData['anio_especialidad2'],
            ]);
    
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['message' => 'Datos del alumno actualizados exitosamente.', 'data' => $alumno], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
    
            return response()->json(['error' => 'Error al actualizar los datos: ' . $e->getMessage()], 500);
        }
    }
    


    public function updateDocuments(Request $request)
    {
        $validatedData = $request->validate([
            'alumno_id' => 'required|integer|exists:alumno,id',
            'documentos' => 'required|array',
            'documentos.*.tipo' => 'required|string',
            'documentos.*.grado' => 'required|string',
            'documentos.*.documento' => 'required|file',
            'archivoParaEliminar' => 'nullable|array',
            'archivoParaEliminar.*' => 'required|integer|exists:doc_alumno,id',
        ]);

        try {
            // Iniciar una transacción para garantizar la atomicidad
            DB::beginTransaction();

            // Buscamos al alumno por su ID
            $alumno = Alumno::findOrFail($validatedData['alumno_id']);

            // Eliminación de documentos especificados
            if (!empty($validatedData['archivoParaEliminar'])) {
                foreach ($validatedData['archivoParaEliminar'] as $docId) {
                    $doc = DocAlumno::find($docId);
                    if ($doc && $doc->alumno_id === $alumno->id) {
                        // Verificar la existencia del archivo antes de eliminar
                        if (Storage::exists($doc->ruta)) {
                            // Elimina el archivo del sistema de archivos
                            Storage::delete($doc->ruta);
                            // Verificar si el archivo ha sido eliminado
                            if (Storage::exists($doc->ruta)) {
                                return response()->json(['error' => 'No se pudo eliminar el archivo del sistema de archivos.'], 500);
                            }
                        } else {
                            // Continúa con la eliminación del registro de la base de datos incluso si el archivo no existe
                            $doc->delete();
                            return response()->json(['error' => 'El archivo no existe en el sistema de archivos.'], 404);
                        }
                        // Elimina el registro de la base de datos
                        $doc->delete();
                    } else {
                        return response()->json(['error' => 'Documento no encontrado o no pertenece al alumno.'], 404);
                    }
                }
            }

            // Subida de nuevos documentos
            foreach ($validatedData['documentos'] as $doc) {
                $documentoFile = $doc['documento'];

                // Construimos el nombre del archivo usando el tipo, grado y nombre del alumno
                $nombreArchivo = $doc['tipo'] . '_' . $doc['grado'] . '_' . $alumno->nombre . '.' . $documentoFile->getClientOriginalExtension();

                // Guardamos el archivo en el disco 'public' y en la carpeta especificada con el nombre construido
                $ruta = $documentoFile->storeAs('public/documentos/academicos/' . $alumno->nombre, $nombreArchivo);

                // Creamos un nuevo registro en la tabla DocAlumno
                DocAlumno::create([
                    'alumno_id' => $alumno->id,
                    'grado' => strtoupper($doc['grado']),
                    'tipo' => strtoupper($doc['tipo']),
                    'ruta' => $ruta,
                ]);
            }

            // Confirmar la transacción
            DB::commit();

            // Respuesta de éxito
            return response()->json(['message' => 'Documentos subidos exitosamente.'], 201);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Manejo de errores y respuesta de error
            return response()->json(['error' => 'Error al subir documentos: ' . $e->getMessage()], 500);
        }
    }
}
