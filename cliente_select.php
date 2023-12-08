<?php
session_start();
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn =  pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if (!$conn) {
    echo 'Connessione al database fallita!';
    exit();
}

echo '<h3> Ecco i tuoi dati: </h3>';

        $query = "SELECT cliente.*, certificato.codice AS codice_certificato, corso.nome AS nome_corso, corso.vasca
        ,corsia.id
        FROM cliente 
        LEFT JOIN certificato ON certificato.cliente = cliente.Cf
        LEFT JOIN iscritticorsi ON iscritticorsi.cliente = cliente.Cf
        LEFT JOIN corso ON iscritticorsi.corso = corso.nome
        LEFT JOIN corsia on corsia.vasca = corso.vasca
        ";
        
        $result = pg_query($conn, $query);

        if (!$result) {
            echo 'Errore nella query: ' . pg_last_error($conn);
            exit();
        }else {
            fillTable($result);
        }

     
function fillTable($result) {
 
    echo '

    <table>
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
        </tr>
    ';
    
            while ($row = pg_fetch_assoc($result)) {
                echo '
        <tr>
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
            ';
            }
    
            echo '</tr></table>';
}

echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";