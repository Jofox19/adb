<?php require("header.php"); ?>

  <main>
    <div class="inscription-form">
      <form action="" method="post"> 
        <h2>Inscription</h2>
        <div class="form-group">
          <label for="lastname">Name:</label>
          <input type="text" id="lastname" name="lastname" required>
        </div>
        <div class="form-group">
          <label for="firstname">Firstname:</label>
          <input type="text" id="firstname" name="firstname" required>
        </div>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="text" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="SecretQuestion">Secret question:</label>
          <select id="SecretQuestion" name="SecretQuestion" required>
            <option value="">choose your question</option>
            <option>What is your favorite movie ?</option>
            <option>What was your first car?</option>
            <option>What is the name of your first pet ?</option>
            <option>What is your mother's maiden name ?</option>
            <option>What is your favorite color ?</option>
          </select>
        </div>
        <div class="form-group">
          <label for="SecretResponse">Secret Response:</label>
          <input type="text" id="SecretResponse" name="SecretResponse" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" id="bConnect" name="bConnect" value="bConnect" class="signup-btn">Sign in</button>
      </form>
      <form action="" method="post">
      <button class="return-btn" id="bBack" name="bBack" value="bBack" type="submit">Back</button>
      </form>
    </div>
  </main>


<?php
if (isset($_POST['bConnect'])) {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $SQ = $_POST['SecretQuestion'];
    $SR = $_POST['SecretResponse'];
    $password = $_POST['password'];
    
    $mysqli = new mysqli("localhost", "root", "", "adb_login");

    if ($mysqli->connect_error) {
        die("La connexion a échoué : " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO users (lastname, firstname, username, email, SecretQuestion, SecretResponse, password) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("sssssss", $lastname, $firstname, $username, $email, $SQ, $SR, $hashed_password);
        $stmt->execute();

        if ($stmt->errno) {
            echo "Erreur lors de l'ajout de l'utilisateur : " . $stmt->error;
        } else {
            echo "Utilisateur ajouté avec succès!";
        }

        $stmt->close();
    } else {
        echo "Erreur de préparation de la requête.";
    }

    $mysqli->close();

    
}

?>

<?php
    if (isset($_POST['bBack'])) {
      header("Location: index.php");
      exit();
    }
  ?>

<?php require("footer.php"); ?>