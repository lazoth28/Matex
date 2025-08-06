<?php
// Configuración de MercadoPago para Matex
class MercadoPagoConfig {
    // Credenciales de prueba - CAMBIAR POR LAS REALES EN PRODUCCIÓN
    const ACCESS_TOKEN = 'TEST-3579996812147630-060901-e596f809859e178a4da218bf4a1b5cd7-660962330';
    const PUBLIC_KEY = 'TEST-d0286271-3ae2-4c82-bd77-5e0b77bd8a14';
    
    // URLs de la API
    const API_BASE_URL = 'https://api.mercadopago.com';
    
    public static function createPreference($items, $backUrls = []) {
        $preference = [
            'items' => $items,
            'back_urls' => array_merge([
                'success' => 'https://tu-sitio.com/pago_exitoso.php',
                'failure' => 'https://tu-sitio.com/pago_fallido.php',
                'pending' => 'https://tu-sitio.com/pago_pendiente.php'
            ], $backUrls),
            'auto_return' => 'approved',
            'notification_url' => 'https://tu-sitio.com/webhook_mp.php',
            'external_reference' => 'MATEX-' . uniqid(),
            'payer' => [
                'name' => $_SESSION['nombre'] ?? 'Usuario',
                'email' => $_SESSION['email'] ?? 'test@test.com'
            ]
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => self::API_BASE_URL . '/checkout/preferences',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($preference),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::ACCESS_TOKEN
            ],
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpCode === 201) {
            return json_decode($response, true);
        }
        
        return false;
    }
    
    public static function createCardToken($cardData) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => self::API_BASE_URL . '/v1/card_tokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($cardData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::ACCESS_TOKEN
            ],
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpCode === 201) {
            return json_decode($response, true);
        }
        
        return false;
    }
    
    public static function createPayment($paymentData) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => self::API_BASE_URL . '/v1/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($paymentData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::ACCESS_TOKEN,
                'X-Idempotency-Key: ' . uniqid()
            ],
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpCode === 201) {
            return json_decode($response, true);
        }
        
        return false;
    }
}
?>