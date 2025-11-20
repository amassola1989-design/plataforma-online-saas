<?php
class Database {
    private $host = "localhost";
    private $db_name = "massolag_negocios";
    private $username = "massolag_negocios";
    private $password = "Luyano8906*";
    public $conn;
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username, 
                $this->password,
                $this->options
            );
        } catch(PDOException $exception) {
            // En desarrollo, mostrar el error real
            if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
                echo "Error de conexión: " . $exception->getMessage();
            } else {
                // En producción, mostrar un mensaje genérico y registrar el error
                error_log("Error de conexión BD: " . $exception->getMessage());
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error temporal del sistema. Por favor, intenta nuevamente.'
                    ]);
                } else {
                    echo "<div style='padding: 20px; margin: 20px; border: 1px solid #f5c6cb; background: #f8d7da; color: #721c24; border-radius: 5px;'>
                            <h3>Error de Conexión</h3>
                            <p>Estamos experimentando problemas técnicos. Por favor, intenta nuevamente en unos minutos.</p>
                            <small>Si el problema persiste, contacta al soporte técnico.</small>
                          </div>";
                }
            }
            exit();
        }
        return $this->conn;
    }

    // ... (el resto de métodos helper que ya teníamos)
}
?>