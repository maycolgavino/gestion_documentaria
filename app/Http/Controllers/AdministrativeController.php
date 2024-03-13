<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resolucion;
use App\Models\DetResolucion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class  AdministrativeController extends Controller
{
    public function registerRes(Request $request)
    {
        $validatedData = $request->validate([
            'tipo' => 'required|string',
            'anio_res' => 'required|string',
        ]);

        $userEmail = auth()->user()->email; // Asegúrate de que el usuario está autenticado.

        try {
            $resolucion = Resolucion::create([
                'tipo_res' => strtoupper($validatedData['tipo']),
                // 'codigo_caja' => $validatedData['codigoCaja'],
                'anio' => $validatedData['anio_res'],
                'email' => $userEmail,
                // Asumiendo que quieres guardar el correo del usuario que registra el sílabo
                // 'observaciones' => $validatedData['observaciones'] ?? '',
            ]);

            return response()->json(['message' => 'Registro de Resolucion exitoso.', 'data' => $resolucion], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el registro de Resolucion: ' . $e->getMessage()], 500);
        }
    }

    public function uploadRes(Request $request)
    {
        $validatedData = $request->validate([
            'documentos' => 'required|array',
            'documentos.*.documento' => 'required|file',
            'documentos.*.numero' => 'required|string',
            'documentos.*.autor' => 'required|string',
            'documentos.*.receptor' => 'required|string',
            'documentos.*.motivo' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $resdata = Resolucion::latest('id')->firstOrFail();

            foreach ($validatedData['documentos'] as $doc) {
                $documentoFile = $doc['documento'];
                // Construye el nombre del archivo usando el código, carrera y curso
                $nombreArchivo = $doc['autor'] . '_' . $doc['numero'] . '_' . $resdata->anio . '.pdf';

                // Guarda el archivo en el disco 'public' y en la carpeta especificada con el nombre construido
                // Dentro del método uploadRes
                $ruta = $documentoFile->storeAs('public/documentos/resoluciones/'.$resdata->anio, $nombreArchivo);


                $detres = DetResolucion::create([

                    'resolucion_id' => $resdata->id,
                    'numero' => strtoupper($doc['numero']),
                    'autor' => strtoupper($doc['autor']),
                    'receptor' => strtoupper($doc['receptor']),
                    'motivo' => strtoupper($doc['motivo']),
                    'ruta' => $ruta,
                ]);
            }

            return response()->json(['message' => 'Documentos del Resolucion subidos exitosamente.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al subir documentos de Resolucion: ' . $e->getMessage()], 500);
        }
    }
    public function getResDetails(Request $request)
    {
        // Obtén los parámetros de búsqueda del request
        $tipoResolucion = $request->query('tipoResolucion');
        $anio = $request->query('anio');

        // Busca en la tabla `resolucion` por tipo y año, y obtén los IDs correspondientes
        $resolucionesIds = Resolucion::where('tipo_res', $tipoResolucion)
            ->where('anio', $anio)
            ->pluck('id');

        // Utiliza los IDs obtenidos para buscar en `det_resolucion`
        $detallesResoluciones = DetResolucion::whereIn('resolucion_id', $resolucionesIds) // Asegúrate de que 'resolucion_id' es el nombre correcto del campo FK en tu modelo DetResolucion
            ->with('resolucion') // Si tienes una relación definida en DetResolucion para acceder a la Resolucion asociada
            ->get();

        return response()->json(['data' => $detallesResoluciones]);
    }

    public function downloadResDocument($id)
    {
        try {
            // Encuentra el detalle del sílabo usando el ID
            $detRes = DetResolucion::findOrFail($id);
            // Construye la ruta completa al archivo
            $pathToFileRes = storage_path('app/' . $detRes->ruta);

            // Comprueba si el archivo existe
            if (!file_exists($pathToFileRes)) {
                return response()->json(['message' => 'File not found.'], 404);
            }
            // Devuelve el archivo para su descarga
            return response()->download($pathToFileRes);
        } catch (\Exception $e) {
            // Maneja la excepción y envía una respuesta JSON en caso de error
            return response()->json(['message' => 'Error downloading the document: ' . $e->getMessage()], 500);
        }
    }
}
