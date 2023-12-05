<?php 
require("header.php");
session_start();

$mysqli = new mysqli("localhost", "root", "", "adb_login");

if ($mysqli->connect_error) {
    die("La connexion a échoué : " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bModify'])) { 
        $user_id = $_POST['user_id']; 
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $query = "UPDATE adds SET lastname = '$lastname', firstname = '$firstname', email = '$email', phone = '$phone', address = '$address' WHERE id = $user_id";

        if ($mysqli->query($query) === TRUE) {
            echo "Les informations de l'utilisateur ont été mises à jour avec succès.";
            header("Location: listContact.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $mysqli->error;
        }
    }

    if (isset($_POST['bBack'])) {
        header("Location: listContact.php");
        exit();
    }
    if (isset($_POST['bModify'])) {
        header("Location: listContact.php");
        exit();
    }
}

if (isset($_GET['id']) && !empty($_GET['id'])) { 
    $user_id = $_GET['id']; 

    $query = "SELECT * FROM adds WHERE id = $user_id";
    $result = $mysqli->query($query);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc(); 
?>
        <main>
            <div class="inscription-form">
                <form action="#" method="post">
                    <h2>Modify Contact</h2>
                    <div class="form-group">
                        <label for="lastname">Name:</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="firstname">Firstname:</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Mail:</label>
                        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone number:</label>
                        <input type="phone" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>
                    </div>
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
                    <button type="submit" id="bModify" name="bModify" value="bModify">Modify</button>
                    <button class="return-btn" id="bBack" name="bBack" value="bBack" type="submit">Back</button>
                </form>
            </div>
        </main>
        <small>(* Required fields)</small>
<?php
    } else {
        echo "Aucun utilisateur trouvé avec cet identifiant.";
    }
} else {
    echo "Identifiant de l'utilisateur non fourni.";
}

$mysqli->close();
require_once("footer.php");
?>