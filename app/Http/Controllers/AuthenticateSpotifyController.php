<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Socialite;

class AuthenticateSpotifyController extends Controller
{
    /*
    funcion de login
    usa la libreria de Socialite para realizar la autenticación
    */
    public function spotifyLogin()
    {
        return Socialite::with('spotify')
        ->scopes(['user-top-read'])
        ->redirect();
    }
    /*
    La funcion usa las credenciales del .env 
    client_id es el id de la aplicacion de spotify
    client_secret es el id secreto de la aplicacion de spotify
    redirect_uri es el link de respuesta de spotify dentro de la configuracion de la aplicacion
    */
    public function spotifyCallback(\GuzzleHttp\Client $httpClient)
    {
        if (isset($_GET['error']))
        {
            return redirect('/denied');
        }

        $response = $httpClient->post('https://accounts.spotify.com/api/token', [
            'form_params' => [
                'client_id' => env('SPOTIFY_KEY'),
                'client_secret' => env('SPOTIFY_SECRET'),
                'grant_type' => 'authorization_code',
                'code' => $_GET['code'],

                'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            ]
        ]);

        session(['spotify_token' => json_decode($response->getBody())->access_token]);
        session(['spotify_refresh' => json_decode($response->getBody())->refresh_token]);
        return redirect('/favourites');
    }
    /*
    funcion para denegar el acceso a la aplicacion
    */
    public function denied()
    {
        return view('denied');
    }
}
