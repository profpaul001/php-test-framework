<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Root\PhpTestFramework\Core\TestRunner;
use Root\PhpTestFramework\Database\DatabaseTests;
use Root\PhpTestFramework\HTTP\HTTPTests;
use Root\PhpTestFramework\Forms\FormTests;

// Carica configurazione
$config = require __DIR__ . '/config.php';
$runner = new TestRunner($config);

// Test database
echo "Esecuzione test database...\n";
$result = $runner->runTest(DatabaseTests::class, 'testConnection', [$config['database']]);
echo ($result['success'] ? "✓" : "✗") . " " . $result['message'] . "\n";

// Test HTTP
echo "\nEsecuzione test HTTP...\n";
$result = $runner->runTest(HTTPTests::class, 'testStatusCode', [$config['urls']['home']]);
echo ($result['success'] ? "✓" : "✗") . " " . $result['message'] . "\n";

// Test form (se applicabile)
echo "\nEsecuzione test form...\n";
$loginForm = $config['forms']['login'];
$result = $runner->runTest(FormTests::class, 'testFormSubmit', [
    $loginForm['url'],
    $loginForm['fields'],
    $loginForm['success']
]);
echo ($result['success'] ? "✓" : "✗") . " " . $result['message'] . "\n";

echo "\nTest completati.\n";