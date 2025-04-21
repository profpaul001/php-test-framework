<?php
require_once __DIR__ . '/vendor/autoload.php';

use Root\PhpTestFramework\Core\TestRunner;
use Root\PhpTestFramework\Database\DatabaseTests;
use Root\PhpTestFramework\HTTP\HTTPTests;
use Root\PhpTestFramework\Forms\FormTests;

// Carica configurazione se esiste
$configFile = __DIR__ . '/examples/config.php';
$config = file_exists($configFile) ? require $configFile : [];

// Inizializza TestRunner
$runner = new TestRunner($config);

// Esegui test se richiesto
$testResults = [];
if (isset($_GET['run_tests']) && $_GET['run_tests'] === '1') {
    // Test database
    $testResults[] = [
        'name' => 'Connessione Database',
        'result' => $runner->runTest(DatabaseTests::class, 'testConnection', [$config['database']])
    ];
    
    // Test HTTP
    $testResults[] = [
        'name' => 'Risposta Homepage',
        'result' => $runner->runTest(HTTPTests::class, 'testStatusCode', [$config['urls']['home']])
    ];
    
    // Test form
    if (isset($config['forms']['login'])) {
        $loginForm = $config['forms']['login'];
        $testResults[] = [
            'name' => 'Submit Form Login',
            'result' => $runner->runTest(FormTests::class, 'testFormSubmit', [
                $loginForm['url'],
                $loginForm['fields'],
                $loginForm['success']
            ])
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Test Framework</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
        }
        .test-result {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            border: 1px solid #d0e9c6;
            color: #3c763d;
        }
        .failure {
            background-color: #f2dede;
            border: 1px solid #ebcccc;
            color: #a94442;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP Test Framework</h1>
        
        <p>
            Questo framework permette di eseguire facilmente test generici per applicazioni PHP/MySQL.
        </p>
        
        <?php if (!empty($testResults)): ?>
            <h2>Risultati dei Test</h2>
            
            <?php foreach ($testResults as $test): ?>
                <div class="test-result <?= $test['result']['success'] ? 'success' : 'failure' ?>">
                    <strong><?= htmlspecialchars($test['name']) ?>:</strong> 
                    <?= htmlspecialchars($test['result']['message']) ?>
                </div>
            <?php endforeach; ?>
            
            <a href="index.php" class="btn">Torna alla Home</a>
        <?php else: ?>
            <a href="index.php?run_tests=1" class="btn">Esegui Test</a>
        <?php endif; ?>
        
        <h2>Documentazione</h2>
        <p>
            Per utilizzare il framework nei tuoi progetti, includi l'autoloader e utilizza le classi:
        </p>
        <pre>
require_once 'vendor/autoload.php';

use Root\PhpTestFramework\Core\TestRunner;
use Root\PhpTestFramework\Database\DatabaseTests;
use Root\PhpTestFramework\HTTP\HTTPTests;
use Root\PhpTestFramework\Forms\FormTests;

// Esempio di utilizzo
$config = [...];
$runner = new TestRunner($config);
$result = $runner->runTest(DatabaseTests::class, 'testConnection', [$config['database']]);
        </pre>
    </div>
</body>
</html>