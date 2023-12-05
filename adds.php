<?php
session_start(); 

require("header.php"); 

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    exit('User ID not found in session.');
}
?>

<main>
    <div class="inscription-form">
        <form action="#" method="post">
            <h2>Add Contact</h2>
            <div class="form-group">
                <label for="lastname">Name:</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="firstname">Firstname:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="email">Mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone number:</label>
                <input type="phone" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
            <button type="submit" id="bCreate" name="bCreate" value="bCreate">Create</button>
        </form>
        <form action="" method="post">
      <button class="return-btn" id="bBack" name="bBack" value="bBack" type="submit">Back</button>
      </form>
    </div>
</main>

<small>(* Required fields)</small>

<?php
if (isset($_POST['bCreate'])) {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $user_id = $_POST['user_id'];

    $mysqli = mysqli_connect('localhost', 'root', '', 'adb_login');
    mysqli_query($mysqli, "INSERT INTO adds VALUES (NULL, '$lastname', '$firstname', '$email', '$phone', '$address', $user_id)") or die(mysqli_error($mysqli));

    if (mysqli_connect_errno()) {
        echo "Connection lost : " . mysqli_connect_error();
        exit();
    }

    $stmt = $mysqli->prepare("INSERT INTO ads (lastname, firstname, email, phone, address) VALUES (?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssi", $lastname, $firstname, $email, $phone, $address, $user_id);
        $stmt->execute();

        if ($stmt->errno) {
            echo "Failed to add user: " . $stmt->error;
        } else {
            echo "User added successfully";
        }

        $stmt->close();
    } else {
        echo "Error in query preparation.";
    }

    $mysqli->close();
} else {
    echo "Error.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (isset($_POST['bBack'])) {
      header("Location: listContact.php");
      exit();
  }
}
?>

<?php require("footer.php"); ?>