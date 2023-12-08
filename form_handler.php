<?php
session_start();

if (isset($_POST['ruolo']) && isset($_POST['operazione'])) {
    $tabellaSel = $_POST['ruolo'];
    $op = $_POST['operazione'];

    $_SESSION['selectedTable'] = $tabellaSel;

    $conn = pg_connect("host=localhost port=5432 dbname=piscina user=postgres password=pwd");

    if (!$conn) {
        echo 'Connessione al database fallita!';
        exit();
    }

    $tableOperations = ['select', 'delete', 'update', 'create'];

    if (in_array($tabellaSel, ['cliente', 'responsabile', 'istruttore']) && in_array($op, $tableOperations)) {
        require_once "{$tabellaSel}_{$op}.php";
    } else {
        echo 'Operazione non valida!';
    }
} else {
    echo "Non risultano dati passati<br>";
    echo "Se vuoi puoi <a href='home.php'>riprovare</a>";
}
?>
