<?php require("header.php"); ?>

<main>
  <div class="reset-password-form">
    <form action="" method="post"> 
      <h2>Réinitialiser le mot de passe</h2>
      <div class="form-group">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="SecretResponse">Réponse à la question secrète :</label>
        <input type="text" id="SecretResponse" name="SecretResponse" required>
      </div>
      <div class="form-group">
        <label for="newPassword">Nouveau mot de passe :</label>
        <input type="password" id="newPassword" name="newPassword" required>
      </div>
      <button type="submit" id="resetPassword" name="resetPassword" value="resetPassword" class="reset-btn">Réinitialiser le mot de passe</button>
    </form>
    <form action="index.php" method="post">
      <button class="return-btn" id="bBack" name="bBack" value="bBack" type="submit">Retour</button>
    </form>
  </div>
</main>

<?php
if (isset($_POST['resetPassword'])) {
    $username = $_POST['username'];
    $SR = $_POST['SecretResponse'];
    $newPassword = $_POST['newPassword'];
    
    $mysqli = new mysqli("localhost", "root", "", "adb_login");

    if ($mysqli->connect_error) {
        die("La connexion a échoué : " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT SecretResponse FROM users WHERE username = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($storedResponse);
            $stmt->fetch();
            
            if ($SR === $storedResponse) {
                $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $mysqli->prepare("UPDATE users SET password = ? WHERE username = ?");
                
                if ($updateStmt) {
                    $updateStmt->bind_param("ss", $hashed_password, $username);
                    $updateStmt->execute();

                    $updateStmt->close();
                }
            }
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>

<?php
if (isset($_POST['bBack'])) {
    header("Location: index.php");
    exit();
}
?>

<?php require("footer.php"); ?>