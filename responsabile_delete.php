<?php
session_start();


echo '<link rel="stylesheet" type="text/css" href="style.css">';


$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

// testing connection to db
if (!$conn) {
    echo "Si è verificato un errore!!<br />";
    exit();
}

$query = "SELECT responsabile.*, dp.piscina, dp.inizio, dp.fine, dp.data, cp.corso
FROM direzionepiscina dp
RIGHT JOIN responsabile ON responsabile.cf = dp.responsabile
LEFT JOIN corsiproposti cp on dp.piscina= cp.piscina";


$result = pg_query($conn, $query);

// Controllo degli errori nella query
if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
} else {
    echo '<form method="POST" action="responsabile_delete.php">';
    fillTable($result);
    echo '<br>';
    echo '<input type="submit" name="delete" value="delete">';
    echo '<input type="submit" name="elimina-profilo" value="elimina profilo">';
    echo '</form>';
}



if (isset($_POST['elimina-profilo'])) {
    $profilo =  pg_escape_string($conn, $_POST['eliminoprofilo']);
    if (empty($profilo) ) {
        echo 'Please select a profile to delete.';
        exit();
    }
    $deleteQuery = "delete FROM responsabile WHERE cf = '$profilo'";
    $deleteResult = pg_query($conn, $deleteQuery);
    if (!$deleteResult) {
        echo 'Errore nella disiscrizione: ' . pg_last_error($conn);
    } else {
        echo 'Eliminazione avvenuta con successo!';
    }
}

if (isset($_POST['delete'])) {
    $corsoToDelete = $_POST['eliminocorso'];
    $separateValues = explode('-', $corsoToDelete);
    $corso = pg_escape_string($conn, $separateValues[0]);
    $piscina = pg_escape_string($conn, $separateValues[1]);
 
    $deleteQuery = "delete FROM corsiproposti WHERE corso = '$corso' and piscina = '$piscina'";

    if (empty($corso) || empty($piscina)) {
        echo 'Please select a profile to delete.';
        exit();
    }
  
    $deleteResult = pg_query($conn, $deleteQuery);
    if (!$deleteResult) {
        echo 'Errore nella disiscrizione: ' . pg_last_error($conn);
    } else {
        echo 'Disiscrizione avvenuta con successo!';
    }
}


function fillTable($result)
{
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
    echo '<th>Corso</th>';
    echo '<th>Elimina Corso</th>';
    echo '<th>Elimina Profilo</th>';
    echo '<tr>';

    while ($row = pg_fetch_assoc($result)) {
        echo '<td>' . $row['cf'] . '</td>';
        echo '<td>' . $row['nome'] . '</td>';
        echo '<td>' . $row['cognome'] . '</td>';
        echo '<td>' . $row['contatto'] . '</td>';
        echo '<td>' . $row['piscina'] . '</td>';
        echo '<td>' . $row['inizio'] . '</td>';
        echo '<td>' . $row['fine'] . '</td>';
        echo '<td>' . $row['data'] . '</td>';
        echo '<td>' . $row['corso'] . '</td>';
        echo '<td>  <input type="radio" name="eliminocorso" value="' . $row['corso'] . '-' . $row['piscina'] . '" > </td>';
        echo '<td>  <input type="radio" name="eliminoprofilo" value="' . $row['cf'] .'" > </td>';
        echo '</tr>';
    }

    echo '</table>';
}
echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";

