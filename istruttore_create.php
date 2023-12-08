<?php
session_start();
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if (!$conn) {
    echo 'Connessione al database fallita!';
    exit();
}

echo "AGGIUNGI QUALIFICA";


$query = 'SELECT * FROM istruttore';
$result = pg_query($conn, $query);


if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
} else {
    fillTable($result);
}

function fillTable($result)
{
    echo '<form method="POST" action="istruttore_create.php">';
    echo '<table>';
    echo '<tr>';
    echo '<th>Codice Fiscale</th>';
    echo '<th>Nome</th>';
    echo '<th>Cognome</th>';
    echo '</tr>';

    // Loop per stampare i risultati
    while ($row = pg_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['cf'] . '</td>';
        echo '<td>' . $row['nome'] . '</td>';
        echo '<td>' . $row['cognome'] . '</td>';
        echo '<td><input type="radio" name="cf-qualifica" value="' . $row['cf'] . '"  ></td>';
        echo '</tr>';
    }

    echo 'Inserisci Qualifica <input type="text" name="nuova-qualifica" > 
<br> <br>';
    echo '<input type="submit" name="create-qualifica" value="Aggiugni">';
    echo '</table>';
}



if (isset($_POST['create-qualifica'])) {
    $cf = $_POST['cf-qualifica'] ;
    $qualifica = isset($_POST['nuova-qualifica']) ? $_POST['nuova-qualifica'] : '';
    $query = "INSERT INTO istruttorepossiedequalifica 
    values ('$cf','$qualifica')";
    if ($cf != ' ' || $cf != "") {
    $insertResult = pg_query($conn, $query);
    }
    if (!$insertResult) {
        echo 'Errore nell\'inserimento: ' . pg_last_error($conn);
    } else {
        echo 'Inserimento avvenuto con successo!';
    }
}


echo "AGGIUNGI ISTRUTTORE";
echo '<br';

if (isset($_POST['create-istruttore'])) {
    $cf = isset($_POST['cf']) || $_POST['cf'] ;
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $contatto = isset($_POST['contatto']) ? $_POST['contatto'] : '';
    $contratto = isset($_POST['contratto']) ? $_POST['contratto'] : '';
    $qualifica = isset($_POST['qualifica']) ? $_POST['qualifica'] : '';
    $piscina = isset($_POST['piscina']) ? $_POST['piscina'] : '';

    $insertQuery = "INSERT INTO istruttore (cf, nome, cognome, contatto, contratto, qualifica, piscina) VALUES ('$cf', '$nome','$cognome', '$contatto', '$contratto', '$qualifica', '$piscina')";
    $insertResult = pg_query($conn, $insertQuery);

    if (!$insertResult) {
        echo 'Errore nell\'inserimento: ' . pg_last_error($conn);
    } else {
        echo 'Inserimento avvenuto con successo!';
    }
}


$qualificaQuery = "SELECT * FROM qualifica";
$qualificaResult = pg_query($conn, $qualificaQuery);

$piscinaQuery = "SELECT * FROM piscina";
$piscinaResult = pg_query($conn, $piscinaQuery);

if (!$piscinaResult) {
    echo 'Errore nel recupero delle piscine: ' . pg_last_error($conn);
    exit();
}

echo '<form method="POST" action="istruttore_create.php">';
echo '<table>
    <tr>
        <th>Codice Fiscale</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Contatto</th>
        <th>Contratto</th>
        <th>Qualifica</th>
        <th>Piscina</th>
        <th>Aggiungi Istruttore</th>
    </tr>';

echo '<tr>
    <td><input type="text" name="cf"></td>
    <td><input type="text" name="nome"></td>
    <td><input type="text" name="cognome"></td>
    <td><input type="text" name="contatto"></td>
    <td><input type="text" name="contratto"></td>
    <td><select name="qualifica">';
    
while ($row = pg_fetch_assoc($qualificaResult)) {
    echo '<option value="' . $row['nome'] . '">' . $row['nome'] . '</option>';
}

echo '</select></td>';

echo '<td><select name="piscina">';
    
while ($row = pg_fetch_assoc($piscinaResult)) {
    echo '<option value="' . $row['nome'] . '">' . $row['nome'] . '</option>';
}

echo '</select></td>';

echo '<td><input type="submit" name="create-istruttore" value="Aggiungi Istruttore"></td>';

echo '</tr>';

echo '</table>';
echo '</form>';






echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
