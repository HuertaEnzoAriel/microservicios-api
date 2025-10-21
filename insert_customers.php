<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Customer;

$clientes = [
    [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'jdoe@hotmail.com',
    ],
    [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jsmith@hotmail.com',
    ],
    [
        'first_name' => 'Maru',
        'last_name' => 'Scheffer',
        'email' => 'mscheffer@hotmail.com',
    ],
    [
        'first_name' => 'Martin',
        'last_name' => 'Varela',
        'email' => 'mvarelochoa@hotmail.com',
    ],
];

foreach ($clientes as $customerData) {
    Customer::updateOrCreate(
        ['email' => $customerData['email']], // Condición de búsqueda
        $customerData
    );
}

$listado = Customer::all();
foreach ($listado as $customer) {
    echo "$customer\n";
}

echo "\n\nTotal customers: " . $listado->count() . "\n";