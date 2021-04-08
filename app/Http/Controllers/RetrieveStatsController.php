<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class RetrieveStatsController extends Controller
{
    /*
    Funcion para mostrar las canciones o artistas dependiendo de la seleccion del usuario
    usa el token para entrar en el cliente de spotify y buscar las canciones o artistas
    toma las credenciales del .env 
    */
    public function retrieveData(\GuzzleHttp\Client $httpClient, Request $request)
    {
        try {
            $response = $httpClient->get("https://api.spotify.com/v1/me/top/{$request->input('type')}?time_range={$request->input('time_range')}", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('spotify_token'),
                ],
            ]);

            return response()->json(json_decode($response->getBody())->items);
        } catch (\Exception $e) {

            $refreshToken = $httpClient->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'client_id' => env('SPOTIFY_KEY'),
                    'client_secret' => env('SPOTIFY_SECRET'),
                    'grant_type' => 'refresh_token',
                    'refresh_token' => session('spotify_refresh'),
                    'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
                ]
            ]);

            $refreshToken = json_decode($refreshToken->getBody());

            session(['spotify_token' => $refreshToken->access_token]);

            if (isset($refreshToken->refresh_token)) {
                session(['spotify_refresh' => $refreshToken->refresh_token]);
            }

            $response = $httpClient->get("https://api.spotify.com/v1/me/top/{$request->input('type')}?time_range={$request->input('time_range')}", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('spotify_token'),
                ],
            ]);

            return response()->json(json_decode($response->getBody())->items);
        }
    }
    /*
    envia a la vista de favoritos
    */
    public function viewStats()
    {
        if (session()->has('spotify_token')) {
            return view('favourites');
        }

        return redirect('/login/spotify');
    }
    /*
    
    esta funcion usa el token de usuario de spotify
    muestra las caracteristicas de cada cancion.
    retorna un json con los datos
    */
    public function retrieveAnalysedTrackData(\GuzzleHttp\Client $httpClient, Request $request)
    {
        $response = $httpClient->get("https://api.spotify.com/v1/audio-features/{$request->input('track')}", [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session('spotify_token'),
            ],
        ]);

        return response()->json(json_decode($response->getBody()));
    }
}
