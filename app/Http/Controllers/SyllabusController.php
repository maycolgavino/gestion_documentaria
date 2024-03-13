<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetSilabo;
use App\Models\Silabo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class SyllabusController extends Controller
{
    public function registerSilabo(Request $request)
    {
        $validatedData = $request->validate([
            'facultad' => 'required|string',
            'escuela' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $userEmail = auth()->user()->email; // Asegúrate de que el usuario está autenticado.

        try {
            $silabo = Silabo::create([
                'facultad' => strtoupper($validatedData['facultad']),
                'escuela' => strtoupper($validatedData['escuela']),
                // 'codigo_caja' => $validatedData['codigoCaja'],
                // 'anio' => $validatedData['anio'],
                'email' => $userEmail, // Asumiendo que quieres guardar el correo del usuario que registra el sílabo
                // 'observaciones' => $validatedData['observaciones'] ?? '',
            ]);

            return response()->json(['message' => 'Registro de sílabo exitoso.', 'data' => $silabo], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el registro del sílabo: ' . $e->getMessage()], 500);
        }
    }

    public function uploadSyllabus(Request $request)
    {
        $validatedData = $request->validate([
            'documentos' => 'required|array',
            'documentos.*.code' => 'required|string',
            'documentos.*.carrera' => 'required|string',
            'documentos.*.curso' => 'required|string',
            'documentos.*.documento' => 'required|file',
            'documentos.*.anio' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $silabodata = Silabo::latest('id')->firstOrFail();

            foreach ($validatedData['documentos'] as $doc) {
                $documentoFile = $doc['documento'];
                // Construye el nombre del archivo usando el código, carrera y curso
                $nombreArchivo = $doc['code'] . '_' . $doc['carrera'] . '_' . $doc['curso'] . '.pdf';

                // Guarda el archivo en el disco 'public' y en la carpeta especificada con el nombre construido
                $ruta = $documentoFile->storeAs('public/documentos/silabos/' . $silabodata->facultad, $nombreArchivo);

                $detsilabo = DetSilabo::create([

                    'silabo_id' => $silabodata->id,
                    'id' => $doc['code'],  // Asumiendo que tienes una columna para relacionar DetSilabo con Silabo
                    'carrera' => strtoupper($doc['carrera']),
                    'curso' => strtoupper($doc['curso']),
                    'anio' => strtoupper($doc['anio']),
                    'ruta' => $ruta,
                ]);
            }

            return response()->json(['message' => 'Documentos del sílabo subidos exitosamente.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al subir documentos del sílabo: ' . $e->getMessage()], 500);
        }
    }
    public function getSyllabusDetails(Request $request)
    {
        $query = $request->query('query');

        // Intenta encontrar coincidencias en la tabla silabos primero.
        $silaboIds = Silabo::where('facultad', 'like', "%{$query}%")
            ->orWhere('escuela', 'like', "%{$query}%")
            ->pluck('id'); // Obtén solo los IDs para usarlos en la siguiente consulta si es necesario.

        if ($silaboIds->isEmpty()) {
            // Si no se encontraron coincidencias en silabos, busca en det_silabo directamente.
            $detalles = DetSilabo::where('id', 'like', "%{$query}%")
                ->orWhere('anio', 'like', "%{$query}%")
                ->orWhere('carrera', 'like', "%{$query}%")
                ->orWhere('curso', 'like', "%{$query}%")
                ->with('silabo')
                ->get();
        } else {
            // Si se encontraron silabos, obtén los DetSilabo relacionados con esos IDs.
            $detalles = DetSilabo::whereIn('silabo_id', $silaboIds)
                ->with('silabo')
                ->get();
        }

        return response()->json(['data' => $detalles], Response::HTTP_OK);
    }
    public function downloadDocument($id)
    {
        try {
            // Encuentra el detalle del sílabo usando el ID
            $detail = DetSilabo::findOrFail($id);
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
