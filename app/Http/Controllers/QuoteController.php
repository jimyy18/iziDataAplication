<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(){
        $random = rand(0,2);
        $source =['https://catfact.ninja/fact','https://api.chucknorris.io/jokes/random','https://dog-facts-
        api.herokuapp.com/api/v1/resources/dogs?number'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $source[$random],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        return response()->json([
            'status'  => 200,
            'quote,' =>$response,
            'source' =>$source[$random]
        ]);
    }
}
