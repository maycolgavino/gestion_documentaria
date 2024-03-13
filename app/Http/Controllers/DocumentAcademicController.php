<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\DocAlumno;
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
            'nombre' => 'required|string',
            'carrera' => 'required|string',
            'anio_egreso' => 'required|integer|between:1965,2024',
            'caja' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $userEmail = auth()->user()->email; // Asegurarse de que el usuario está autenticado.

        try {
            $alumno = Alumno::create([
                'dni' => $validatedData['dni'],
                'email' => $userEmail,
                'nombre' => $validatedData['nombre'],
                'carrera' => $validatedData['carrera'],
                'anio_egreso' => $validatedData['anio_egreso'],
                'caja' => $validatedData['caja'],
                'observaciones' => $validatedData['observaciones'],
            ]);

            return response()->json(['message' => 'Registro de alumno exitoso.', 'data' => $alumno], 201);
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
                    'grado' => $doc['grado'],
                    'tipo' => $doc['tipo'],
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
        $dni = $request->query('dni');
        $alumno = Alumno::where('dni', $dni)->first();
        $documentos = [];

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
}
