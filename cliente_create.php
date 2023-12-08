<?php
session_start();
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if (!$conn) {
    echo 'Connessione al database fallita!';
    exit();
}

echo "AGGIUNGI CERTIFICATO MEDICO";

if (isset($_POST['update-certificato'])) {
    $codiceCertificato = isset($_POST['codice_certificato']) ? $_POST['codice_certificato'] : '';
    $medico = isset($_POST['medico']) ? $_POST['medico'] : '';
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    $cf = isset($_POST['cf']) ? $_POST['cf'] : '';

    $insertQuery = "INSERT INTO certificato (codice, medico, data, cliente) VALUES ('$codiceCertificato', '$medico', '$data', '$cf')";
    $insertResult = pg_query($conn, $insertQuery);

    if (!$insertResult) {
        echo 'Errore nell\'inserimento: ' . pg_last_error($conn);
    } else {
        echo 'Inserimento avvenuto con successo!';
    }
}

$query = "SELECT *
FROM certificato ce right join cliente on
ce.cliente = cliente.cf
where codice is null"; 

$result = pg_query($conn, $query);

if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
} else {
    while ($row = pg_fetch_assoc($result)) {
        // Display cliente information for adding certificato goes here
        echo '<form method="POST" action="cliente_create.php">';
        echo '<table>
            <tr>
                <th>Codice Fiscale</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Data di Nascita</th>
                <th>Codice Certificato</th>
                <th>CF Medico</th>
                <th>Data</th>
                <th>Aggiungi Codice Certificato</th>
            </tr>';

        echo '<tr>
            <td>' . $row['cf'] . '</td>
            <td>' . $row['nome'] . '</td>
            <td>' . $row['cognome'] . '</td>
            <td>' . $row['datadinascita'] . '</td>
            <td> <input type="text" name="codice_certificato"></td>
            <td> <input type="text" name="medico"></td>
            <td> <input type="date" name="data"></td>
            <td>
                <input type="hidden" name="cf" value="' . $row['cf'] . '">
                <button type="submit" name="update-certificato">Aggiungi Certificato</button>
            </td>
        </tr>';
        echo '</table>';
        echo '</form>';
    }
}

echo "AGGIUNGI ISCRIZIONE CORSO";

$query = "SELECT c.nome, c.cf,  iscritticorsi.*
FROM cliente c
LEFT JOIN iscritticorsi ON iscritticorsi.cliente = c.Cf
where corso is null";



$result = pg_query($conn, $query);

if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
} else {
    
    while ($row = pg_fetch_assoc($result)) {

        echo '<form method="POST" action="cliente_create.php">';
        echo '<table>
            <tr>
            
                <th>Nome</th>
                <th>CF</th>
                <th>Corso</th>
                <th>Aggiungi Corso</th>
                
            </tr>';

        echo '<tr>
            <td>' . $row['nome'] . '</td>
            <td>' . $row['cf'] . '</td>
            <td> <input type="text" name="corso"></td>
            <td>
            <input type="hidden" name="cf" value="' . $row['cf'] . '">
          <button type="submit" name="create-corso">Aggiungi Corso</button>
            </td>
        </tr>';
        echo '</table>';
        echo '</form>';
       
    }
}


if (isset($_POST['create-corso'])) {
    $codice=  $_POST['cf'];
  
    $corso = $_POST['corso'];
  

    $insertQuery = "INSERT INTO iscritticorsi (cliente,corso) VALUES ('$codice', '$corso')";

  

    $insertResult = pg_query($conn, $insertQuery);

    if (!$insertResult) {
        echo 'Errore nell\'inserimento: ' . pg_last_error($conn);
    } else {
        echo 'Inserimento avvenuto con successo!';
    }
}

echo "AGGIUNGI NUOVO CLIENTE";

if (isset($_POST['create-cliente'])) {
    $cf = isset($_POST['cf']) ? $_POST['cf'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $cognome = isset($_POST['cognome']) ? $_POST['cognome'] : '';
    $datadinascita = isset($_POST['datadinascita']) ? $_POST['datadinascita'] : '';
    $nomeGenitore = isset($_POST['nome_genitore']) ? $_POST['nome_genitore'] : '';
    $cognomeGenitore = isset($_POST['cognome_genitore']) ? $_POST['cognome_genitore'] : '';


    $insertQuery = "INSERT INTO cliente 
     VALUES ('$cf', '$nome', '$cognome', '$datadinascita', '$nomeGenitore', '$cognomeGenitore'";

 
    $insertQuery .= ")";

    $insertResult = pg_query($conn, $insertQuery);

    if (!$insertResult) {
        echo 'Errore nell\'inserimento: ' . pg_last_error($conn);
    } else {
        echo 'Inserimento avvenuto con successo!';
    }
}

// Display the form to add a new cliente
echo '<form method="POST" action="cliente_create.php">';
echo '<table>
    <tr>
        <th>Codice Fiscale</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Data di Nascita</th>
        <th>Nome Genitore</th>
        <th>Cognome Genitore</th>
        <th>Aggiungi Cliente</th>
    </tr>';

echo '<tr>
    <td><input type="text" name="cf" required></td>
    <td><input type="text" name="nome" required></td>
    <td><input type="text" name="cognome" required></td>
    <td><input type="date" name="datadinascita" required></td>
    <td><input type="text" name="nome_genitore" value="null"></td>
    <td><input type="text" name="cognome_genitore" value="null"></td>
    <td><button type="submit" name="create-cliente">Aggiungi Cliente</button></td>
</tr>';
echo '</table>';
echo '</form>';

echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";


