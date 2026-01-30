<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DEBUG API ===\n\n";

echo "1. Testando config.sqlite.php...\n";
try {
    require_once 'config.sqlite.php';
    echo "✓ Config carregado\n";
    echo "✓ PDO conectado\n\n";
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "2. Testando helpers.php...\n";
try {
    require_once 'helpers.php';
    echo "✓ Helpers carregado\n\n";
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "3. Testando controllers...\n";
try {
    require_once 'controllers/ChapterController.php';
    require_once 'controllers/AuthController.php';
    require_once 'controllers/SettingsController.php';
    require_once 'controllers/MetricsController.php';
    echo "✓ Controllers carregados\n\n";
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "4. Testando query no banco...\n";
try {
    $chapters = $pdo->query("SELECT * FROM chapters LIMIT 1")->fetch();
    echo "✓ Query executada\n";
    echo "✓ Resultado: " . json_encode($chapters) . "\n\n";
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "=== TUDO OK ===\n";
