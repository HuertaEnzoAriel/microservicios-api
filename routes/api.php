<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\HolaController;
use App\Http\Controllers\CalcularController;

Route::get('/ping', fn() => response()->json([
    'success' => true,
    'data' => ['status' => 'ok'],
    'message' => 'API is running correctly'
]));

// Respuesta JSON en API routes/api.php
Route::get('/status', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Ejemplo: API simple de productos
Route::get('products', function () {
    $products = [
        ['id' => 1, 'name' => 'Laptop', 'price' => 999.99],
        ['id' => 2, 'name' => 'Mouse', 'price' => 25.99],
        ['id' => 3, 'name' => 'Teclado', 'price' => 89.99]
    ];
    
    return response()->json([
        'data' => $products,
        'count' => count($products)
    ]);
});


// Parámetro obligatorio
Route::get('/customers/{id}', function ($id) {
    return "Cliente ID: {$id}";
});

// Parámetro opcional
Route::get('/customers/{id?}', function ($id = null) {
    if ($id) {
        return "Cliente ID: {$id}";
    }
    return "Lista de todos los clientes";
});

// Múltiples parámetros
Route::get('/customers/{id}/reviews/{reviewId}', function ($id, $reviewId) {
    return "Review {$reviewId} del cliente {$id}";
});


// Solo números
Route::get('/product/{id}', function ($id) {
    return "Producto: {$id}";
})->where('id', '[0-9]+');

// Solo letras
Route::get('/categories/{slug}', function ($slug) {
    return "Categoría: {$slug}";
})->where('slug', '[a-zA-Z\-]+');

// Expresiones regulares múltiples
Route::get('/reseñas/{mes}/{año}', function ($mes, $año) {
    return "Reseñas de {$mes}/{$año}";
})->where(['año' => '[0-9]{4}', 'mes' => '[0-9]{2}']);


// Respuesta JSON en API routes/api.php
Route::get('/api/status', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});


// Ejemplo: API simple de productos
Route::get('/api/products', function () {
    $products = [
        ['id' => 1, 'name' => 'Laptop', 'price' => 999.99],
        ['id' => 2, 'name' => 'Mouse', 'price' => 25.99],
        ['id' => 3, 'name' => 'Teclado', 'price' => 89.99]
    ];
    
    return response()->json([
        'data' => $products,
        'count' => count($products)
    ]);
});

// Endpoint con parámetros y validación básica
Route::get('/calc/{operation}/{num1}/{num2}', [CalcularController::class, 'calcular']);


// Esto se vuelve difícil de mantener:
Route::post('/stat', function () {
    $validated = request()->validate([
        'numbers' => 'required|array|min:2',
        'numbers.*' => 'numeric',
        'operation' => 'required|in:sum,mean'
    ],    
    [
        'numbers.required' => 'Debe proporcionar al menos 2 números.',
        'numbers.array' => 'Los números deben ser un array.',
        'numbers.min' => 'Debe proporcionar al menos 2 números.',
        'numbers.*.numeric' => 'Todos los elementos del array deben ser numéricos.',
        'operation.required' => 'La operación es obligatoria.',
        'operation.in' => 'La operación debe ser: sum o mean.'
    ]);

    $numbers = $validated['numbers'];
    $operation = $validated['operation'];
    $result = match($operation) {
        'sum' => array_sum($numbers),
        'mean' => array_sum($numbers) / count($numbers),
    };
    return response()->json(['result' => $result]);
});


// Endpoint de prueba para archivos (sin autenticación para testing)
Route::post('/test-files', [FileController::class, 'upload']);
Route::get('/test-files', [FileController::class, 'index']);
Route::get('/test-files/download/{filename}', [FileController::class, 'download']);
Route::delete('/test-files/{filename}', [FileController::class, 'delete']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas para reset de contraseña
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

// Ruta para verificar email (no requiere autenticación)
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Reenviar email de verificación
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);

    // Rutas para manejo de archivos
    Route::prefix('files')->group(function () {
        Route::post('/upload', [FileController::class, 'upload']);
        Route::get('/', [FileController::class, 'index']);
        Route::get('/download/{filename}', [FileController::class, 'download']);
        Route::delete('/{filename}', [FileController::class, 'delete']);
    });
});
