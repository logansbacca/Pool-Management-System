<?php
session_start();

$query = "SELECT * FROM $tabellaSel ORDER BY cf";
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

$op = $_POST['operazione'];


// La query ha generato errori
if (!$conn) {
    echo "Si è verificato un errore!!<br />";
    exit();
} else { // La query non ha generato errori

    $query = "SELECT istruttore.cf, istruttore.nome, istruttore.cognome,istruttore.contatto, istruttore.contratto, istruttore.piscina, ipq.qualifica, te.*, st.sostituto, st.anno as anno_sostituzione, ferie.inizio, ferie.fine
    FROM istruttore 
    LEFT JOIN istruttorepossiedequalifica  ipq on istruttore.cf = ipq.istruttore
	LEFT JOIN ferie ON ferie.istruttore = istruttore.cf
    LEFT JOIN titolareedizione te ON istruttore.cf = te.titolare
    LEFT JOIN sostituzionetitolare st ON st.titolare = te.titolare AND te.anno = st.anno";

    $result = pg_query($conn, $query);

    // Controllo degli errori nella query
    if (!$result) {
        echo 'Errore nella query: ' . pg_last_error($conn);
        exit();
    } else {
        fillTable($result);
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
        
        echo '</tr>';
    }

    echo '</table>';
}

echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
