<?php
namespace App\Services;

use Culqi\Culqi;

class CulqiService
{
    protected $culqi;

    public function __construct()
    {
        $this->culqi = new Culqi([
            'api_key' => config('services.culqi.secret_key')
        ]);
    }

    public function crearCargo($token, $monto, $email)
    {
        return $this->culqi->Charges->create([
            'amount' => $monto * 100,
            'currency_code' => 'PEN',
            'email' => $email,
            'source_id' => $token,
        ]);
    }
}
