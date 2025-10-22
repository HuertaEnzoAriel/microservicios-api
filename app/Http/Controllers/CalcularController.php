<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InvalidArgumentException;

class CalcularController extends Controller
{
    public function calcular ($operation, $num1, $num2) {
    $result = match($operation) {
        'add' => $num1 + $num2,
        'sub' => $num1 - $num2,
        'mul' => $num1 * $num2,
        'div' => $num2 != 0 ? $num1 / $num2 : 'Error: Zero division',
        default => throw new InvalidArgumentException('invalid operator')
    };
    
    return response()->json([
        'operation' => $operation,
        'num1' => $num1,
        'num2' => $num2,
        'result' => $result
    ]);
}
}
