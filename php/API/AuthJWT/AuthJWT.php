<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthJWT {
    private $secretKey;
    private $algorithm = 'HS256';
    
    public function __construct($secretKey = null) {
        $this->secretKey = $secretKey ?: $this->generateSecretKey();
    }
    
    // Generar clave secreta segura
    private function generateSecretKey() {
        return bin2hex("Dlcjvl031210@");
    }
    
    // Crear token de acceso
    public function createAccessToken($userId, $username, $role = 'user') {
        $payload = [
            'iss' => $_SERVER['HTTP_HOST'] ?? 'localhost',
            'aud' => 'api',
            'iat' => time(),
            'exp' => time() + (15 * 60), // 15 minutos
            'type' => 'access',
            'user_id' => $userId,
            'username' => $username,
            'role' => $role
        ];
        
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }
    
    // Crear token de refresco
    public function createRefreshToken($userId) {
        $payload = [
            'iss' => $_SERVER['HTTP_HOST'] ?? 'localhost',
            'aud' => 'api',
            'iat' => time(),
            'exp' => time() + (7 * 24 * 60 * 60), // 7 días
            'type' => 'refresh',
            'user_id' => $userId
        ];
        
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }
    
    // Validar token
    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            // print_r($decoded);die();
            return (array) $decoded;
        } catch (Exception $e) {
            // print_r($e);die();
            return false;
        }
    }
    
    // Extraer información del token sin validar (útil para logs)
    public function peekToken($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        
        $payload = base64_decode($parts[1]);
        return json_decode($payload, true);
    }
}




// Ejemplo de uso en un sistema de login
class AuthController {
    private $authJWT;
    
    public function __construct() {
        $this->authJWT = new AuthJWT('mi-clave-secreta-muy-segura');
    }
    
    public function login($username, $password) {
        // Aquí iría la validación real contra la base de datos
        $user = $this->authenticateUser($username, $password);
        
        if ($user) {
            $accessToken = $this->authJWT->createAccessToken(
                $user['id'], 
                $user['username'], 
                $user['role']
            );
            
            $refreshToken = $this->authJWT->createRefreshToken($user['id']);
            
            return [
                'success' => true,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in' => 15 * 60 // 15 minutos
            ];
        }
        
        return ['success' => false, 'message' => 'Credenciales inválidas'];
    }
    
    public function validateAccess($token) {
        $decoded = $this->authJWT->validateToken($token);
        
        if ($decoded && $decoded['type'] === 'access') {
            return [
                'valid' => true,
                'user_id' => $decoded['user_id'],
                'username' => $decoded['username'],
                'role' => $decoded['role']
            ];
        }
        
        return ['valid' => false];
    }
    
    public function refreshToken($refreshToken) {
        $decoded = $this->authJWT->validateToken($refreshToken);
        
        if ($decoded && $decoded['type'] === 'refresh') {
            // Aquí buscaríamos los datos del usuario en la BD
            $user = $this->getUserById($decoded['user_id']);
            
            if ($user) {
                $newAccessToken = $this->authJWT->createAccessToken(
                    $user['id'], 
                    $user['username'], 
                    $user['role']
                );
                
                return [
                    'success' => true,
                    'access_token' => $newAccessToken
                ];
            }
        }
        
        return ['success' => false, 'message' => 'Token de refresco inválido'];
    }
    
    // Métodos de ejemplo (en un caso real conectarías con tu BD)
    private function authenticateUser($username, $password) {
        // Simulación de autenticación
        $users = [
            'juan' => ['id' => 1, 'username' => 'juan', 'password' => '123', 'role' => 'user'],
            'admin' => ['id' => 2, 'username' => 'admin', 'password' => 'admin123', 'role' => 'admin']
        ];
        
        if (isset($users[$username]) && $users[$username]['password'] === $password) {
            $user = $users[$username];
            unset($user['password']); // No devolver la contraseña
            return $user;
        }
        
        return false;
    }
    
    private function getUserById($id) {
        $users = [
            1 => ['id' => 1, 'username' => 'juan', 'role' => 'user'],
            2 => ['id' => 2, 'username' => 'admin', 'role' => 'admin']
        ];
        
        return $users[$id] ?? false;
    }
}

// // DEMOSTRACIÓN
// echo "=== DEMOSTRACIÓN JWT PHP ===\n\n";

// $authController = new AuthController();

// // 1. Login
// echo "1. Realizando login...\n";
// $loginResult = $authController->login('juan', '123');
// if ($loginResult['success']) {
//     echo "Login exitoso!\n";
//     echo "Access Token: " . substr($loginResult['access_token'], 0, 50) . "...\n";
//     echo "Refresh Token: " . substr($loginResult['refresh_token'], 0, 50) . "...\n\n";
    
//     $accessToken = $loginResult['access_token'];
    
//     // 2. Validar token
//     echo "2. Validando token...\n";
//     $validation = $authController->validateAccess($accessToken);
//     if ($validation['valid']) {
//         echo "Token válido! Usuario: {$validation['username']}, Rol: {$validation['role']}\n\n";
//     }
    
//     // 3. Peek token (sin validar)
//     echo "3. Visualizando contenido del token (sin validar)...\n";
//     $authJWT = new AuthJWT('mi-clave-secreta-muy-segura');
//     $tokenData = $authJWT->peekToken($accessToken);
//     print_r($tokenData);
    
// } else {
//     echo "Error en login: {$loginResult['message']}\n";
// }
?>