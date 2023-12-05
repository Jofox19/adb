<?php
require("header2.php");

// Vérifier si l'utilisateur est connecté et récupérer son ID depuis la session
session_start();
//if (!isset($_SESSION['user_id'])) {
//  header("Location: login.php");
//  exit();
//}

// Récupération de l'ID utilisateur connecté
$user_id = $_SESSION['user_id'];

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "adb_login");

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Connexion lost : " . $mysqli->connect_error);
}

// Récupération des données de l'utilisateur connecté depuis la table 'adds'
$query = "SELECT * FROM adds WHERE id_users = ?";
$stmt = $mysqli->prepare($query);

if ($stmt) {
    // Liaison des valeurs et exécution de la requête préparée
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();

    // Affichage des données dans le tableau HTML ou message si le tableau est vide
    if ($result->num_rows > 0) {
        echo '<div class="table-style custom-table">
                <table>
                <thead>
                    <tr>
                        <th>LastName</th>
                        <th>Firstname</th>
                        <th>Email</th>
                        <th>Phone number</th>
                        <th>Address</th>
                        <th>Modify</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody class="colorInt">>';
    
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["lastname"] . '</td>';
            echo '<td>' . $row["firstname"] . '</td>';
            echo '<td>' . $row["email"] . '</td>';
            echo '<td>' . $row["phone"] . '</td>';
            echo '<td>' . $row["address"] . '</td>';
            echo '<td><a href="editContact.php?id=' . $row["id"] . '" class="edit-link">Modify</a></td>';
       
            echo '<td>
                <form action="delContact.php" method="POST" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer ce produit ?\')">
                    <input type="hidden" name="delete_user" value="' . $row["id"] . '">
                    <button type="submit" name="Contact" class="delete-button">Supprimer</button>
                </form>
            </td>';
            echo '</tr>';
        }
        
        echo '</tbody></table></div>';
    } else {
        echo '<div class="table-style custom-table">
        <h2 class="custom-heading">Tableau vide </h2>
        </div>';
    }

    $stmt->close();
} else {
    echo "Erreur dans la préparation de la requête.";
}

// Boutons pour se déconnecter (index.php) et accéder à adds.php
echo '<div class="button-container">';


echo '<form action="adds.php" method="GET">';
echo '<button type="submit" name="viewAdds" value="viewAdds" class="create-contact-btn">Create Contact</button>';
echo '</form>';
echo '<form action="logout_user.php" method="POST">';
echo '<button type="submit" name="bDisconnect" value="bDisconnect" class="logout-btn">Disconnect</button>';
echo '</form>';
echo '</div>';

if (isset($_SESSION['bDisconnect'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
$mysqli->close();

require_once("footer.php");
?>
<php?

?>