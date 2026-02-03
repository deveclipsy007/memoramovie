<?php
// Arquivo: api/debug_logs.php
ini_set('display_errors', 1);
require_once 'config.php';
require_once 'db.php';

echo "<h1>Logs do Sistema (Últimos 50)</h1>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Level</th><th>Event</th><th>Details</th><th>Time</th></tr>";

try {
    $stmt = $pdo->query("SELECT * FROM logs ORDER BY id DESC LIMIT 50");
    while ($row = $stmt->fetch()) {
        $color = $row['level'] == 'error' ? '#ffeeee' : ($row['level'] == 'success' ? '#eeffee' : '#ffffff');
        echo "<tr style='background: $color;'>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['level'] . "</td>";
        echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
        echo "<td><pre style='margin:0; font-size: 11px;'>" . htmlspecialchars(substr($row['details'], 0, 300)) . "</pre></td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
} catch (Exception $e) {
    echo "Erro ao ler logs: " . $e->getMessage();
}
echo "</table>";
