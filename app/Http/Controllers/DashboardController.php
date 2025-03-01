<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use Nesk\Puphpeteer\Puppeteer;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function alumnosIncompletos()
    {
        try {
            $alumnos = Alumno::where(function ($query) {
                    $query->where('dni', '00000000')
                        ->orWhere('matricula_code', '0000000000')
                        ->orWhereNull('dni')
                        ->orWhereNull('matricula_code')
                        ->orWhere('dni', '')
                        ->orWhere('matricula_code', '')
                        ->orWhere('dni', '-')
                        ->orWhere('matricula_code', '-');
                })
                ->select('id', 'nombre', 'dni', 'matricula_code', 'caja', 'carrera', 'created_at')
                ->get()
                ->reject(function ($alumno) {
                    return empty($alumno->nombre) && empty($alumno->dni) && 
                           empty($alumno->matricula_code) && empty($alumno->carrera);
                }) // 🔥 Elimina SOLO las filas donde TODOS los campos están vacíos o NULL
                ->values(); // 🔥 Reindexar el array para evitar problemas en el frontend

            return response()->json($alumnos, 200);
        } catch (\Exception $e) {
            Log::error("Error obteniendo alumnos incompletos: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener datos'], 500);
        }
    }

    public function generarPDF()
    {
        try {
            $url = url('/vista-reporte'); // Ruta de la vista que se desea capturar

            $puppeteer = new Puppeteer();
            $browser = $puppeteer->launch([
                'args' => ['--no-sandbox', '--disable-setuid-sandbox'] // Evita errores en entornos sin GUI
            ]);
            $page = $browser->newPage();
            $page->goto($url, ['waitUntil' => 'networkidle2']);

            // Generar PDF
            $pdf = $page->pdf(['format' => 'A4']);
            $browser->close();

            return Response::make($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="reporte.pdf"',
                'Content-Length' => strlen($pdf), // Añadir tamaño del archivo para mejor compatibilidad
            ]);
        } catch (\Exception $e) {
            Log::error("Error generando PDF: " . $e->getMessage());
            return response()->json(['error' => 'Error al generar el PDF'], 500);
        }
    }
}
