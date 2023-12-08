<?php
session_start();
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if (!$conn) {
    echo 'Connessione al database fallita!';
    exit();
}

echo '<h3> Disiscrizione corso: </h3>';

if (isset($_POST['delete'])) {
    $cfToDelete = $_POST['cf_to_delete'];

    $deleteQuery = "DELETE FROM iscritticorsi WHERE cliente = '$cfToDelete'";
    $deleteResult = pg_query($conn, $deleteQuery);

    if (!$deleteResult) {
        echo 'Errore nella disiscrizione: ' . pg_last_error($conn);
    } else {
        echo 'Disiscrizione avvenuta con successo!';
    }
}


if (isset($_POST['elimina-profilo'])) {
    $account = $_POST['delete-account'];
    $connectionSuccessful = false;

    $query1 = "DELETE FROM iscritto WHERE cliente = '$account'";
    $query2 = "DELETE FROM iscritticorsi WHERE cliente = '$account'";
    $query3 = "DELETE FROM cliente WHERE cliente = '$account'";

    if (pg_query($conn, $query1)) {
        $connectionSuccessful = true;
    }
    if (pg_query($conn, $query2)) {
        $connectionSuccessful = true;
    }
    if (pg_query($conn, $query3)) {
        $connectionSuccessful = true;
    }

    if (!$connectionSuccessful) {
        echo 'Errore nella cancellazione: ' . pg_last_error($conn);
    } else {
        echo 'Eliminazione avvenuta con successo!';
    }
}








$query = "SELECT cliente.*, certificato.codice AS codice_certificato, corso.nome AS nome_corso, corso.vasca
    ,corsia.id
    FROM cliente 
    LEFT JOIN certificato ON certificato.cliente = cliente.Cf
    LEFT JOIN iscritticorsi ON iscritticorsi.cliente = cliente.Cf
    LEFT JOIN corso ON iscritticorsi.corso = corso.nome
    LEFT JOIN corsia ON corsia.vasca = corso.vasca
";

$result = pg_query($conn, $query);

if (!$result) {
    echo 'Errore nella query: ' . pg_last_error($conn);
    exit();
} else {
    echo '<form method="POST" action="cliente_delete.php">';
    echo '<table>
        <tr>
            <th>Codice Fiscale</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Data di Nascita</th>
            <th>Nome Genitore</th>
            <th>Cognome Genitore</th>
            <th>Codice Certificato</th>
            <th>Corsi</th>
            <th>Vasca</th>
            <th>Corsia</th>
            <th>Disiscrivi</th>
            <th>Elimina Profilo</th>
        </tr>';

    while ($row = pg_fetch_assoc($result)) {
        echo '<tr>
            <td>' . $row['cf'] . '</td>
            <td>' . $row['nome'] . '</td>
            <td>' . $row['cognome'] . '</td>
            <td>' . $row['datadinascita'] . '</td>
            <td>' . $row['nomegenitore'] . '</td>
            <td>' . $row['cognomegenitore'] . '</td>
            <td>' . $row['codice_certificato'] . '</td>
            <td>' . ($row['nome_corso'] ?? '') . '</td>
            <td>' . ($row['vasca'] ?? '') . '</td>
            <td>' . ($row['id'] ?? '') . '</td>
            <td><input type="radio" name="cf_to_delete" value="' . $row['cf'] . '" ></td>
            <td><input type="radio" name="delete-account" value="' . $row['cf'] . '" ></td>
        </tr>';
    }
    echo '</table>';
    echo '<input type="submit" name="delete" value="Delete">';
    echo '<input type="submit" name="elimina-profilo" value="Elimina Cliente">';
    echo '</form>';
}

echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
?>
