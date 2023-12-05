<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id_to_delete = $_POST['delete_user'];

    // Connexion à la base de données
    $mysqli = new mysqli("localhost", "root", "", "adb_login");

    // Vérification de la connexion
    if ($mysqli->connect_error) {
        die("Connexion lost : " . $mysqli->connect_error);
    }

    // Requête pour supprimer l'utilisateur de la table 'adds'
    $query = "DELETE FROM adds WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Liaison des valeurs et exécution de la requête préparée
        $stmt->bind_param("i", $user_id_to_delete);
        $stmt->execute();

        // Redirection vers la page précédente (pour l'instant, renvoyer vers index.php)
        header("Location: index.php");
        exit();
    } else {
        echo "Erreur dans la préparation de la requête de suppression.";
    }

    $stmt->close();
    $mysqli->close();
} else {
    // Redirection si la méthode de requête n'est pas POST ou si 'delete_user' n'est pas défini
    header("Location: index.php");
    exit();
}
?>