<?php
session_start();
echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if (!$conn) {
    echo 'Connessione al database fallita!';
    exit();
}

if (isset($_POST['update'])) {
  
 
    $telefono = isset($_POST['contatto']) ? $_POST['contatto'] : NULL;
    $cf = isset($_POST['cf']) || $_POST['cf']  ;


    $pattern = '/^[0-9]+$/';
    $tel = preg_match($pattern, $telefono) ? $tel : 'not valid';

    $query = "UPDATE istruttore SET contatto = '$telefono' where cf = '$cf' ";



    if ($tel != 'not valid') {
   
        $result = pg_query($conn, $query);
        if (!$result) {
            echo "Si è verificato un errore.<br/>";
            echo pg_last_error($conn);
            exit();
        } else {

            echo "Aggiornamento avvenuto con successo<br><a href='istruttore_update.php'>ritorna</a>";
        };
    }else {
      
       echo $telefono;
    } 
}

$query = "SELECT * from istruttore ";

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
    echo '<form method="POST" action="istruttore_update.php">';
   
    echo '<table>';
    echo '<tr>';
    echo '<th>Codice Fiscale</th>';
    echo '<th>Nome</th>';
    echo '<th>Cognome</th>';
    echo '<th>Contatto</th>';
    echo '</tr>';


        echo '<tr>';
        echo '<td><input type="text" name="cf" value="' . $row['cf'] . '" readonly></td>';
        echo '<td><input type="text" name="nome" value="' . $row['nome'] . '" readonly></td>';
        echo '<td><input type="text" name="cognome" value="' . $row['cognome'] . '" readonly></td>';
        echo '<td><input type="text" name="contatto" value="' . $row['contatto'] . '" ></td>';
       
        echo '</tr>';
    
    echo '<td><input type="submit" name="update" value="save"></td>';

    echo '</table>';
    echo '</form>';

}
echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
