<?php
namespace Root\PhpTestFramework\HTTP;

class HTTPTests
{
    /**
     * Testa lo status code di una pagina
     *
     * @param string $url URL della pagina da testare
     * @param int $expectedCode Codice HTTP atteso (default: 200)
     * @param bool $followRedirect Se seguire i redirect (default: true)
     * @return array Risultato del test
     */
    public static function testStatusCode($url, $expectedCode = 200, $followRedirect = true)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followRedirect);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'success' => $statusCode == $expectedCode,
            'message' => "La pagina ha risposto con codice {$statusCode}" . 
                        ($statusCode == $expectedCode ? '' : ", atteso {$expectedCode}")
        ];
    }
    
    /**
     * Testa se una pagina si carica correttamente
     *
     * @param string $url URL della pagina da testare
     * @return array Risultato del test
     */
    public static function testPageLoad($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        $content = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        $success = $statusCode >= 200 && $statusCode < 400 && !empty($content) && empty($error);
        
        return [
            'success' => $success,
            'message' => $success ? 
                "La pagina si è caricata correttamente (codice {$statusCode})" : 
                "Errore nel caricamento della pagina: " . ($error ?: "codice {$statusCode}")
        ];
    }
    
    /**
     * Testa se una pagina contiene un testo specifico
     *
     * @param string $url URL della pagina da testare
     * @param string $text Testo da cercare nella pagina
     * @return array Risultato del test
     */
    public static function testPageContent($url, $text)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        $content = curl_exec($ch);
        curl_close($ch);
        
        if ($content === false) {
            return [
                'success' => false,
                'message' => "Impossibile caricare la pagina per verificare il contenuto"
            ];
        }
        
        $contains = strpos($content, $text) !== false;
        
        return [
            'success' => $contains,
            'message' => $contains ? 
                "La pagina contiene il testo '{$text}'" : 
                "La pagina non contiene il testo '{$text}'"
        ];
    }
    
    /**
     * Testa se una pagina contiene un elemento HTML specifico
     *
     * @param string $url URL della pagina da testare
     * @param string $selector Selettore CSS dell'elemento da cercare
     * @return array Risultato del test
     */
    public static function testPageElement($url, $selector)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        $content = curl_exec($ch);
        curl_close($ch);
        
        if ($content === false) {
            return [
                'success' => false,
                'message' => "Impossibile caricare la pagina per verificare l'elemento"
            ];
        }
        
        // Utilizziamo una semplice regex per cercare l'elemento
        // (in un framework completo si potrebbe usare una libreria DOM)
        $pattern = "/<[^>]*(?:id=['\"]" . preg_quote($selector, '/') . "['\"]|class=['\"]" . 
                   preg_quote($selector, '/') . "['\"])[^>]*>/i";
        $found = preg_match($pattern, $content);
        
        return [
            'success' => $found === 1,
            'message' => $found === 1 ? 
                "La pagina contiene l'elemento '{$selector}'" : 
                "La pagina non contiene l'elemento '{$selector}'"
        ];
    }
    
    /**
     * Testa il tempo di risposta di una pagina
     *
     * @param string $url URL della pagina da testare
     * @param float $maxSeconds Tempo massimo di risposta in secondi
     * @return array Risultato del test
     */
    public static function testResponseTime($url, $maxSeconds = 1.0)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        
        $startTime = microtime(true);
        curl_exec($ch);
        $endTime = microtime(true);
        curl_close($ch);
        
        $responseTime = round($endTime - $startTime, 3);
        $success = $responseTime <= $maxSeconds;
        
        return [
            'success' => $success,
            'message' => "Tempo di risposta: {$responseTime} secondi" . 
                        ($success ? '' : ", superiore al limite di {$maxSeconds} secondi")
        ];
    }
    
    /**
     * Testa se una pagina è protetta da HTTPS
     *
     * @param string $url URL della pagina da testare
     * @return array Risultato del test
     */
    public static function testHTTPS($url)
    {
        $isHttps = strpos($url, 'https://') === 0;
        
        if ($isHttps) {
            return [
                'success' => true,
                'message' => "La pagina utilizza una connessione sicura HTTPS"
            ];
        }
        
        // Proviamo la versione HTTPS dell'URL
        $httpsUrl = str_replace('http://', 'https://', $url);
        $ch = curl_init($httpsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $httpsWorks = $statusCode >= 200 && $statusCode < 400;
        
        return [
            'success' => $httpsWorks,
            'message' => $httpsWorks ? 
                "La versione HTTPS della pagina è disponibile" : 
                "La pagina non supporta HTTPS"
        ];
    }
    
    /**
     * Testa gli header di una pagina
     *
     * @param string $url URL della pagina da testare
     * @param string $headerName Nome dell'header da verificare
     * @param string|null $expectedValue Valore atteso (null per verificare solo la presenza)
     * @return array Risultato del test
     */
    public static function testHeader($url, $headerName, $expectedValue = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false) {
            return [
                'success' => false,
                'message' => "Impossibile controllare gli header della pagina"
            ];
        }
        
        $headers = [];
        $headerLines = explode("\n", $response);
        foreach ($headerLines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) == 2) {
                $headerKey = trim($parts[0]);
                $headerValue = trim($parts[1]);
                $headers[strtolower($headerKey)] = $headerValue;
            }
        }
        
        $headerNameLower = strtolower($headerName);
        $headerExists = isset($headers[$headerNameLower]);
        
        if (!$headerExists) {
            return [
                'success' => false,
                'message' => "L'header '{$headerName}' non è presente"
            ];
        }
        
        if ($expectedValue !== null) {
            $actualValue = $headers[$headerNameLower];
            $valueMatches = (strcasecmp($actualValue, $expectedValue) === 0);
            
            return [
                'success' => $valueMatches,
                'message' => $valueMatches ? 
                    "L'header '{$headerName}' ha il valore atteso '{$expectedValue}'" : 
                    "L'header '{$headerName}' ha valore '{$actualValue}' invece di '{$expectedValue}'"
            ];
        }
        
        return [
            'success' => true,
            'message' => "L'header '{$headerName}' è presente"
        ];
    }
}