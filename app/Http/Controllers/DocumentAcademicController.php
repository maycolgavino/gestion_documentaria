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
    public function registerStudent(Request $request)
    {
        $validatedData = $request->validate([
            'dni' => 'required|string|unique:alumno,dni',
            'matricula_code' => 'required|string|unique:alumno,matricula_code',
            'nombre' => 'required|string',
            'carrera' => 'required|string',
            'anio_egreso' => 'required|integer',
            'caja' => 'required|string',
            'observaciones' => 'nullable|string',
            // Validaciones para los grados académicos
            'anio_bachiller' => 'nullable|integer',
            'anio_titulo' => 'nullable|integer',
            'anio_maestria' => 'nullable|integer',
            'anio_doctorado' => 'nullable|integer',
            'anio_especialidad1' => 'nullable|integer',
            'anio_especialidad2' => 'nullable|integer',
        ]);

        $userEmail = auth()->user()->email; // Asegurarse de que el usuario está autenticado.

        try {
            // Guardar al alumno
            $alumno = Alumno::create([
                'dni' => $validatedData['dni'],
                'nombre' => strtoupper($validatedData['nombre']),
                'matricula_code' => strtoupper($validatedData['matricula_code']),
                'carrera' => strtoupper($validatedData['carrera']),
                'anio_egreso' => $validatedData['anio_egreso'],
                'caja' => strtoupper($validatedData['caja']),
                'observaciones' => strtoupper($validatedData['observaciones']),
                'anio_bachiller' => $validatedData['anio_bachiller'],
                'anio_titulo' => $validatedData['anio_titulo'],
                'anio_maestria' => $validatedData['anio_maestria'],
                'anio_doctorado' => $validatedData['anio_doctorado'],
                'anio_especialidad1' => $validatedData['anio_especialidad1'],
                'anio_especialidad2' => $validatedData['anio_especialidad2'],
            ]);


            return response()->json(['message' => 'Registro de alumno y grados exitoso.', 'data' => $alumno], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el registro del alumno: ' . $e->getMessage()], 500);
        }
    }


    public function uploadDocuments(Request $request)
    {
        $validatedData = $request->validate([
            'dni' => 'required|string|exists:alumno,dni',
            'documentos' => 'required|array',
            'documentos.*.tipo' => 'required|string',
            'documentos.*.grado' => 'required|string',
            'documentos.*.documento' => 'required|file',
        ]);

        try {
            $alumno = Alumno::where('dni', $validatedData['dni'])->firstOrFail();

            foreach ($validatedData['documentos'] as $doc) {
                //$documentoFile = $doc['documento'];
                $documentoFile = $doc['documento'];

                // Construye el nombre del archivo usando el tipo, grado y DNI del alumno
                $nombreArchivo = $doc['tipo'] . '_' . $doc['grado'] . '_' . $alumno->dni . '.pdf';

                // Guarda el archivo en el disco 'public' y en la carpeta especificada con el nombre construido
                $ruta = $documentoFile->storeAs('public/documentos/academicos/' . $alumno->dni, $nombreArchivo);
                // $ruta = Storage::putFile('documentos/academicos/' . $alumno->dni, $documentoFile );

                DocAlumno::create([
                    'dni' => $alumno->dni,
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

        // Buscar al alumno en las columnas 'dni', 'matricula_code' y 'nombre'
        $alumno = Alumno::where('dni', $search)
            ->orWhere('matricula_code', $search)
            ->orWhere('nombre', 'like', "%{$search}%")
            ->first();

        // Si se encuentra un alumno, buscar los documentos asociados
        if ($alumno) {
            $documentos = DocAlumno::where('dni', $alumno->dni)->get();
        }

        return response()->json([
            'alumno' => $alumno,
            'documentos' => $documentos
        ]);
    }
    public function downloadAcadDocument($id)
    {
        try {
            // Encuentra el detalle del sílabo usando el ID
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
            'anio_egreso' => 'required|integer',
            'caja' => 'required|string',
            'observaciones' => 'nullable|string',
            // Validaciones para los grados académicos
            'anio_bachiller' => 'nullable|integer',
            'anio_titulo' => 'nullable|integer',
            'anio_maestria' => 'nullable|integer',
            'anio_doctorado' => 'nullable|integer',
            'anio_especialidad1' => 'nullable|integer',
            'anio_especialidad2' => 'nullable|integer', 
        ]);

        $newDni = strtoupper($validatedData['dni']);
        $alumnoId = $validatedData['id'];

        DB::beginTransaction();

        try {
            // Encontrar al alumno por su ID
            $alumno = Alumno::findOrFail($alumnoId);

            // Verificar si el nuevo DNI ya está en uso
            $existingAlumno = Alumno::where('dni', $newDni)->where('id', '!=', $alumnoId)->first();
            if ($existingAlumno) {
                throw new \Exception('El nuevo DNI ya está en uso.');
            }

            // Buscar los documentos asociados al DNI antiguo
            $documentosAntiguos = DocAlumno::where('dni', $alumno->dni)->get();

            // Eliminar los documentos asociados al DNI antiguo
            DocAlumno::where('dni', $alumno->dni)->delete();

            // Actualizar los datos del alumno
            $alumno->update([
                'dni' => $newDni,
                'nombre' => strtoupper($validatedData['nombre']),
                'carrera' => strtoupper($validatedData['carrera']),
                'anio_egreso' => $validatedData['anio_egreso'],
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

            // Insertar los documentos con el nuevo DNI
            foreach ($documentosAntiguos as $documento) {
                DocAlumno::create([
                    'dni' => $newDni,
                    'grado' => $documento->grado,
                    'tipo' => $documento->tipo,
                    'ruta' => $documento->ruta,
                ]);
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Datos del alumno y documentos actualizados exitosamente.', 'data' => $alumno], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['error' => 'Error al actualizar los datos: ' . $e->getMessage()], 500);
        }
    }


    public function updateDocuments(Request $request)
    {
        $validatedData = $request->validate([
            'dni' => 'required|string|exists:alumno,dni',
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

            // Buscamos al alumno por su DNI
            $alumno = Alumno::where('dni', $validatedData['dni'])->firstOrFail();

            // Eliminación de documentos especificados
            if (!empty($validatedData['archivoParaEliminar'])) {
                foreach ($validatedData['archivoParaEliminar'] as $docId) {
                    $doc = DocAlumno::find($docId);
                    if ($doc && $doc->dni === $alumno->dni) {
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

                // Construimos el nombre del archivo usando el tipo, grado y DNI del alumno
                $nombreArchivo = $doc['tipo'] . '_' . $doc['grado'] . '_' . $alumno->dni . '.' . $documentoFile->getClientOriginalExtension();

                // Guardamos el archivo en el disco 'public' y en la carpeta especificada con el nombre construido
                $ruta = $documentoFile->storeAs('public/documentos/academicos/' . $alumno->dni, $nombreArchivo);

                // Creamos un nuevo registro en la tabla DocAlumno
                DocAlumno::create([
                    'dni' => $alumno->dni,
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
