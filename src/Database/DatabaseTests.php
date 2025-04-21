<?php
namespace Root\PhpTestFramework\Database;

class DatabaseTests
{
    /**
     * Testa la connessione al database
     */
    public static function testConnection($config)
    {
        try {
            $conn = new \mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database']
            );
            
            if ($conn->connect_error) {
                return [
                    'success' => false,
                    'message' => 'Errore di connessione: ' . $conn->connect_error
                ];
            }
            
            $conn->close();
            return [
                'success' => true,
                'message' => 'Connessione al database riuscita'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Testa se una tabella esiste
     */
    public static function testTableExists($config, $table)
    {
        try {
            $conn = new \mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database']
            );
            
            if ($conn->connect_error) {
                return [
                    'success' => false,
                    'message' => 'Errore di connessione: ' . $conn->connect_error
                ];
            }
            
            $result = $conn->query("SHOW TABLES LIKE '{$table}'");
            $exists = $result->num_rows > 0;
            
            $conn->close();
            return [
                'success' => $exists,
                'message' => $exists ? "La tabella '{$table}' esiste" : "La tabella '{$table}' non esiste"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Testa una query semplice
     */
    public static function testQuery($config, $query)
    {
        try {
            $conn = new \mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database']
            );
            
            if ($conn->connect_error) {
                return [
                    'success' => false,
                    'message' => 'Errore di connessione: ' . $conn->connect_error
                ];
            }
            
            $result = $conn->query($query);
            
            if ($result === false) {
                return [
                    'success' => false,
                    'message' => 'Errore nella query: ' . $conn->error
                ];
            }
            
            $conn->close();
            return [
                'success' => true,
                'message' => 'Query eseguita con successo'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ];
        }
    }
}