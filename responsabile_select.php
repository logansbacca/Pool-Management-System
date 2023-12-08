<?php
session_start();

$query = "SELECT * FROM $tabellaSel ORDER BY cf";
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

$op = $_POST['operazione'];

echo "<h2>Responsabili Registrati</h2>";

// La query ha generato errori
if (!$conn) {
    echo "Si è verificato un errore!!<br />";
    exit();
} else { // La query non ha generato errori

    $query = "SELECT responsabile.*, dp.piscina, dp.inizio, dp.fine, dp.data
                    FROM responsabile 
                    LEFT JOIN direzionepiscina dp ON responsabile.cf = dp.responsabile";

    $result = pg_query($conn, $query);

    // Controllo degli errori nella query
    if (!$result) {
        echo 'Errore nella query: ' . pg_last_error($conn);
        exit();
    }

    // Creazione della tabella
    echo '<table>';
    echo '<tr>';
    echo '<th>CF</th>';
    echo '<th>Nome</th>';
    echo '<th>Cognome</th>';
    echo '<th>Contatto</th>';
    echo '<th>Piscina</th>';
    echo '<th>Inizio</th>';
    echo '<th>Fine</th>';
    echo '<th>Data</th>';
    echo '</tr>';

    // Loop per stampare i risultati
    while ($row = pg_fetch_assoc($result)) {
        $qualifica = $row['qualifica'];
        $dataInizioFerie = $row['inizio'];
        $dataFineFerie = $row['fine'];
        $edizione = $row['anno'];
        $corso = $row['corso'];
        echo '<tr>';
        echo '<td>' . $row['cf'] . '</td>';
        echo '<td>' . $row['nome'] . '</td>';
        echo '<td>' . $row['cognome'] . '</td>';
        echo '<td>' . $row['contatto'] . '</td>';
        echo '<td>' . $row['piscina'] . '</td>';
        echo '<td>' . $row['inizio'] . '</td>';
        echo '<td>' . $row['fine'] . '</td>';
        echo '<td>' . $row['data'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}




$query = "SELECT * FROM sostituto ORDER BY istruttore";
$result = pg_query($conn, $query);

if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
}

echo "<h2>Sostituti Registrati</h2>";

echo '<table>';
echo '<tr>';
echo '<th>CF</th>';
echo '<th>Inizio Contratto</th>';
echo '<th>Fine Contratto</th>';
echo '</tr>';

while ($row = pg_fetch_assoc($result)) {
    $cf = $row['istruttore'];
    $inizioContratto = $row['inziocontratto'];
    $fineContratto = $row['finecontratto'];

    echo '<tr>';
    echo '<td>' . $cf . '</td>';
    echo '<td>' . $inizioContratto . '</td>';
    echo '<td>' . $fineContratto . '</td>';
    echo '</tr>';
}

echo '</table>';

echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
?>
