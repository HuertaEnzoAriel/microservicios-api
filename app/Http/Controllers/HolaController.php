<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HolaController extends Controller
{
    public function saludar()
    {
        $datos = [
            'nombre' => 'Enzo',
            'edad' => 25,
            'ciudad' => 'San Juan'
        ];
        return view('hola-mundo', compact('datos'));

    }
}