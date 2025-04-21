<?php
namespace Root\PhpTestFramework\Forms;

class FormTests
{
    /**
     * Testa il submit di un form
     */
    public static function testFormSubmit($url, $formData, $successIndicator)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $success = strpos($response, $successIndicator) !== false;
        
        return [
            'success' => $success,
            'message' => $success ? 
                "Il form è stato inviato con successo" : 
                "Il form non è stato inviato correttamente"
        ];
    }
    
    /**
     * Testa la validazione di un form
     */
    public static function testFormValidation($url, $invalidData, $errorIndicator)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $invalidData);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $hasError = strpos($response, $errorIndicator) !== false;
        
        return [
            'success' => $hasError,
            'message' => $hasError ? 
                "La validazione del form funziona correttamente" : 
                "La validazione del form non funziona come previsto"
        ];
    }
}