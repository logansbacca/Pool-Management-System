<?php
session_start();
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if (!$conn) {
    echo 'Connessione al database fallita!';
    exit();
}

if (isset($_POST['update'])) {
    $cf = $_POST['cf'];

    $cognome = isset($_POST['cognome']) ? $_POST['cognome'] : NULL;
    $nomegenitore = isset($_POST['nomegenitore']) ? $_POST['nomegenitore'] : '';
    $cognomegenitore = isset($_POST['cognomegenitore']) ? $_POST['cognomegenitore'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $datadinascita = isset($_POST['datadinascita']) ?  $_POST['datadinascita'] : NULL;
    $codice_certificato = isset($_POST['codice_certificato']) ? $_POST['codice_certificato'] : NULL;

    $updateQuery = "UPDATE cliente SET nome = '$nome', cognome = '$cognome', datadinascita = '$datadinascita', nomegenitore = '$nomegenitore', cognomegenitore = '$cognomegenitore' WHERE cf = '$cf'";

    $updateResult = pg_query($conn, $updateQuery);

    if (!$updateResult) {
        echo 'Errore nell aggiornamento del record: ' . pg_last_error($conn);
    } else {
        echo 'Record aggiornato con successo!';
    }
}

echo '<h3> Ecco i tuoi dati: </h3>';

$query = "SELECT cliente.*, certificato.codice AS codice_certificato, corso.nome AS nome_corso, corso.vasca, corsia.id
    FROM cliente 
    LEFT JOIN certificato ON certificato.cliente = cliente.Cf
    LEFT JOIN iscritticorsi ON iscritticorsi.cliente = cliente.Cf
    LEFT JOIN corso ON iscritticorsi.corso = corso.nome
    LEFT JOIN corsia on corsia.vasca = corso.vasca";

$result = pg_query($conn, $query);

if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
} else {
    while ($row = pg_fetch_assoc($result)) {
        fillTable($row);
    }
}

function fillTable($row)
{
    echo '<form method="POST" action="cliente_update.php">
    <table>
        <tr>
            <th>Codice Fiscale</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Data di Nascita</th>
            <th>Nome Genitore</th>
            <th>Cognome Genitore</th>           
            <th>Modifica</th>
        </tr>';

    echo '
        <tr>
            <td><input type="text" name="cf" value="' . $row['cf'] . '" readonly></td>
            <td><input type="text" name="nome" value="' . $row['nome'] . '"></td>
            <td><input type="text" name="cognome" value="' . $row['cognome'] . '"></td>
            <td><input type="text" name="datadinascita" value="' . $row['datadinascita'] . '"></td>
            <td><input type="text" name="nomegenitore" value="' . $row['nomegenitore'] . '"></td>
            <td><input type="text" name="cognomegenitore" value="' . $row['cognomegenitore'] . '"></td>
            <td><input type="submit" name="update" value="save"></td>
        </tr>';

    echo '</table></form>';
}

echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
