<?php
session_start();
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if (!$conn) {
    echo 'Connessione al database fallita!';
    exit();
}

echo '<h3> Rinuncia Insegnamento Corso: </h3>';

if (isset($_POST['delete'])) {
    $datiEliminazioneCorso = $_POST['elimino-corso'];

    $parts = explode('-', $datiEliminazioneCorso);
    $titolare = pg_escape_string($conn, $parts[0]);
    $corso = pg_escape_string($conn, $parts[1]);
    $anno = pg_escape_string($conn, $parts[2]);

    $deleteQuery = "delete FROM titolareedizione 
                   WHERE titolare = '$titolare' 
                   AND corso = '$corso' 
                   AND anno = '$anno'";

    $deleteResult = pg_query($conn, $deleteQuery);

    if (!$deleteResult) {
        echo 'Errore: ' . pg_last_error($conn);
    } else {
        echo 'Disiscrizione avvenuta con successo!';
    }
}

$query = "SELECT istruttore.*, te.*, st.sostituto, st.anno as anno_sostituzione, ferie.inizio, ferie.fine
FROM istruttore 
LEFT JOIN ferie ON ferie.istruttore = istruttore.cf
LEFT JOIN titolareedizione te ON istruttore.cf = te.titolare
LEFT JOIN sostituzionetitolare st ON st.titolare = te.titolare AND te.anno = st.anno";

$result = pg_query($conn, $query);

if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
} else {
    echo '<form method="POST" action="istruttore_delete.php">';
    fillTable($result);
    echo '<input type="submit" name="delete" value="delete">';
    echo '<input type="submit" name="elimina-profilo" value="elimina profilo">';
    echo '</form>';
}


if (isset($_POST['elimina-profilo'])) {
    $profilo =  pg_escape_string($conn, $_POST['eliminoprofilo']);

    if (empty($profilo)) {
        echo 'Please select a profile to delete.';
        exit();
    }
    $deleteQuery = "
    
    BEGIN TRANSACTION;

DELETE FROM  TITOLARE WHERE istruttore = '$profilo';
DELETE FROM ISTRUTTORE WHERE CF = '$profilo';
DELETE FROM SOSTITUZIONETITOLARE WHERE titolare = '$profilo';

COMMIT

    ";

    $deleteResult = pg_query($conn, $deleteQuery);
    if (!$deleteResult) {
        echo 'Errore nella disiscrizione: ' . pg_last_error($conn);
    } else {
        echo 'Eliminazione avvenuta con successo!';
    }
}

function fillTable($result)
{

    echo '<table>';
    echo '<tr>';
    echo '<th>Codice Fiscale</th>';
    echo '<th>Nome</th>';
    echo '<th>Cognome</th>';
    echo '<th>Contatto</th>';
    echo '<th>Assunzione</th>';
    echo '<th>Piscina</th>';
    echo '<th>Qualifica</th>';
    echo '<th>Inizio ferie</th>';
    echo '<th>Fine ferie</th>';
    echo '<th>Edizione</th>';
    echo '<th>Corso</th>';
    echo '<th>CF Sostituto</th>';
    echo '<th>Sostituzione Anno</th>';
    echo '<th>Abbandona Corso</th>';
    echo '<th>Elimina Profilo</th>';
    echo '</tr>';

    // Loop per stampare i risultati
    while ($row = pg_fetch_assoc($result)) {
        $qualifica = $row['qualifica'];
        $dataInizioFerie = $row['inizio'];
        $dataFineFerie = $row['fine'];
        $edizione = $row['anno'];
        $corso = $row['corso'];
        $sostituto = $row['sostituto'];
        echo '<tr>';
        echo '<td>' . $row['cf'] . '</td>';
        echo '<td>' . $row['nome'] . '</td>';
        echo '<td>' . $row['cognome'] . '</td>';
        echo '<td>' . $row['contatto'] . '</td>';
        echo '<td>' . $row['contratto'] . '</td>';
        echo '<td>' . $row['piscina'] . '</td>';
        echo '<td>' . $qualifica . '</td>';
        echo '<td>' . $dataInizioFerie . '</td>';
        echo '<td>' . $dataFineFerie . '</td>';
        echo '<td>' . $edizione . '</td>';
        echo '<td>' . $corso . '</td>';
        echo '<td>' . $sostituto . '</td>';
        echo '<td>' . $row['anno_sostituzione'] . '</td>';
        echo '<td>  <input type="radio" name="elimino-corso" value="' . $row['cf'] . "-" . $row['corso'] . "-" . $row['anno'] . '" > </td>';
        echo '<td>  <input type="radio" name="eliminoprofilo" value="' . $row['cf'] . '" > </td>';

        echo '</tr>';
    }

    echo '</table>';
}

echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
