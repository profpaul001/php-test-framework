# PHP Test Framework

Un framework di test generico per applicazioni PHP/MySQL che permette di eseguire facilmente test comuni senza dover scrivere codice di test da zero.

![Versione](https://img.shields.io/badge/versione-0.1.0-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple)
![Licenza](https://img.shields.io/badge/licenza-MIT-green)

## 🌟 Panoramica

PHP Test Framework è un sistema modulare che offre test predefiniti e pronti all'uso per verificare componenti comuni nelle applicazioni PHP/MySQL:

- ✅ Connessioni al database
- ✅ Struttura e integrità delle tabelle
- ✅ Pagine web e risposte HTTP
- ✅ Form e input utente

Questa è la **versione 0.1.0** - una prima implementazione stabile che sarà arricchita con nuove funzionalità nei prossimi aggiornamenti.

## 🚀 Installazione

### Via Composer


```bash
composer require profpaul001/php-test-framework
Clonazione dal Repository
git clone https://github.com/profpaul001/php-test-framework.git
cd php-test-framework
composer install

📋 Utilizzo Base

<?php
require_once 'vendor/autoload.php';

use Root\PhpTestFramework\Core\TestRunner;
use Root\PhpTestFramework\Database\DatabaseTests;
use Root\PhpTestFramework\HTTP\HTTPTests;
use Root\PhpTestFramework\Forms\FormTests;

// Configurazione
$config = [
    'database' => [
        'host' => 'localhost',
        'username' => 'user',
        'password' => 'password',
        'database' => 'testdb'
    ],
    'urls' => [
        'home' => 'https://miosito.com',
        'login' => 'https://miosito.com/login.php'
    ],
    'forms' => [
        'login' => [
            'url' => 'https://miosito.com/login.php',
            'fields' => [
                'username' => 'test',
                'password' => 'test123'
            ],
            'success' => 'Login successful'
        ]
    ]
];

// Inizializza il test runner
$runner = new TestRunner($config);

// Esegui diversi test
echo "Esecuzione test database...\n";
$result = $runner->runTest(DatabaseTests::class, 'testConnection', [$config['database']]);
echo ($result['success'] ? "✓" : "✗") . " " . $result['message'] . "\n";

echo "\nEsecuzione test HTTP...\n";
$result = $runner->runTest(HTTPTests::class, 'testStatusCode', [$config['urls']['home']]);
echo ($result['success'] ? "✓" : "✗") . " " . $result['message'] . "\n";

// Genera un report HTML
$htmlReport = $runner->generateReport();
file_put_contents('report.html', $htmlReport);
echo "\nReport HTML generato in report.html\n";

📊 Funzionalità
Test Database

// Verifica connessione
$result = $runner->runTest(DatabaseTests::class, 'testConnection', [$config['database']]);

// Verifica esistenza tabella
$result = $runner->runTest(DatabaseTests::class, 'testTableExists', [$config['database'], 'users']);

// Esegui una query di test
$result = $runner->runTest(DatabaseTests::class, 'testQuery', [
    $config['database'], 
    "SELECT COUNT(*) FROM users"
]);

Test HTTP

// Verifica codice risposta
$result = $runner->runTest(HTTPTests::class, 'testStatusCode', [$config['urls']['home']]);

// Verifica contenuto pagina
$result = $runner->runTest(HTTPTests::class, 'testPageContent', [
    $config['urls']['home'], 
    'Benvenuto nel mio sito'
]);

// Verifica tempo di risposta
$result = $runner->runTest(HTTPTests::class, 'testResponseTime', [
    $config['urls']['home'], 
    1.5 // timeout in secondi
]);

// Verifica HTTPS
$result = $runner->runTest(HTTPTests::class, 'testHTTPS', [$config['urls']['home']]);

Test Form

// Verifica invio form
$result = $runner->runTest(FormTests::class, 'testFormSubmit', [
    $config['forms']['login']['url'],
    $config['forms']['login']['fields'],
    $config['forms']['login']['success']
]);

// Verifica validazione
$result = $runner->runTest(FormTests::class, 'testFormValidation', [
    $config['forms']['login']['url'],
    ['username' => '', 'password' => ''],
    'Il campo username è obbligatorio'
]);

💻 Interfaccia Web
Il framework include anche una semplice interfaccia web per eseguire i test attraverso il browser. Basta accedere al file index.php nella directory principale.

🛣️ Roadmap
Questa è solo la prima versione del framework. In futuro, prevediamo di aggiungere:

 Test di sicurezza (XSS, SQL Injection, CSRF)
 Test di prestazioni e carico
 Test di integrità dei dati
 Supporto per API REST
 Generazione automatica di test basati sulla struttura del database
 Integrazione con sistemi CI/CD
 Supporto per framework PHP popolari (Laravel, Symfony, etc.)

🤝 Contribuire
I contributi sono benvenuti! Se desideri contribuire:

Fai un fork del repository
Crea un branch per la tua feature (git checkout -b feature/AmazingFeature)
Committa le tue modifiche (git commit -m 'Add some AmazingFeature')
Pusha al branch (git push origin feature/AmazingFeature)
Apri una Pull Request

📜 Licenza
Questo progetto è distribuito con licenza MIT. Vedi il file LICENSE per maggiori informazioni.
👨‍💻 Autore

ProfPaul - GitHub

⭐️ Se questo progetto ti è utile, considera di aggiungergli una stella su GitHub! ⭐️

