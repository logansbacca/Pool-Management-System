<?php
session_start();

echo '<link rel="stylesheet" type="text/css" href="style.css">';

$conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

if(isset($_POST['crea-sostituto'])){
    $qualifica = $_POST['qualifica'];
    $iniziocontratto = $_POST['iniziocontratto'];
    $finecontratto = $_POST['finecontratto'];

    $query = "INSERT INTO sostituto (istruttore, inziocontratto, finecontratto) VALUES ('$qualifica', '$iniziocontratto', '$finecontratto')";
    $connection = pg_query($conn, $query);
    
    if (!$connection){
        echo "Errore: " . pg_last_error();
    } else {
        echo "Aggiunto con successo";
    }
}

$getIstruttore = 'SELECT cf FROM istruttore';
$istruttoreResult = pg_query($conn, $getIstruttore);

echo 'Aggiungi Sostituto';
echo '<form method="POST" action="responsabile_create.php">';
echo '<table>';
echo '<tr>';
echo '<th>CF</th>';
echo '<th>Inizio Contratto</th>';
echo '<th>Fine Contratto</th>';
echo '</tr>';
echo '<tr>';
echo '<td><select name="qualifica">';
    
while ($row = pg_fetch_assoc($istruttoreResult)) {
    echo '<option value="' . $row['cf'] . '">' . $row['cf'] . '</option>';
}

echo '</select></td>';

echo '<td><input type="date" name="iniziocontratto" ></td>';
echo '<td><input type="date" name="finecontratto"  ></td>';
echo '</tr>';
echo '<td><input type="submit" name="crea-sostituto" value="Aggiungi"></td>';
echo '</table>';
echo '</form>';
echo "Se vuoi puoi <a href='home.php'>tornare alla home</a>";
?>
