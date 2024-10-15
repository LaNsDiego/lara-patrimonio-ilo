<?php

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (! function_exists('fetch_dni')) {
    function fetch_dni($date)
    {

    }
}


function convertImageToBase64($imageUrl)
{
    $client = new Client();
    $response = $client->get($imageUrl);

    // Verifica si la solicitud fue exitosa
    if ($response->getStatusCode() == 200) {
        $imageContent = $response->getBody()->getContents();
        $base64 = base64_encode($imageContent);

        // Opcional: Obtén el tipo MIME de la imagen
        $mimeType = $response->getHeader('Content-Type')[0];

        // Construye el Data URI (opcional)
        $dataUri = "data:$mimeType;base64,$base64";

        return $base64;
    }

    return null;
}


function getImageFromUrl($imageUrl)
{
    // Crear una instancia de Guzzle Client
    $client = new Client([
        'verify' => false,
    ]);

    try {
        // Obtener el contenido de la imagen
        $response = $client->get($imageUrl);

        if ($response->getStatusCode() == 200) {
            $imageContent = $response->getBody()->getContents();

            // Obtener la extensión del archivo basada en el tipo de contenido
            $contentType = $response->getHeaderLine('Content-Type');
            $extension = Str::after($contentType, '/');

            // Crear un nombre de archivo único
            $fileName = 'image_' . time() . '.' . $extension;

            // Crear un archivo temporal para la imagen
            $tempPath = sys_get_temp_dir() . '/' . $fileName;
            file_put_contents($tempPath, $imageContent);

            // Crear y devolver el objeto UploadedFile
            return new UploadedFile(
                $tempPath,
                $fileName,
                $contentType,
                null,
                true
            );
        }
    } catch (\Exception $e) {
        // Manejar cualquier error que pueda ocurrir durante la descarga
        Log::error('Error al descargar la imagen: ' . $e->getMessage());
    }

    return null;
}
