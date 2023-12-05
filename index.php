<?php 
require("header.php");

$errors = array();

session_start(); 

if (isset($_SESSION['user_id'])) {
    header("Location: listContact.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bConnect'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $mysqli = new mysqli("localhost", "root", "", "adb_login");

    if ($mysqli->connect_error) {
        die("La connexion a échoué : " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Wrong Password.";
        }
    } else {
        $errors[] = "User not found.";
    }

    $stmt->close();
    $mysqli->close();
}

?>

<main>
    <div class="login-table">
        <h2>Login</h2>

        <form action="index.php" method="POST" id="loginForm">

            <label for="username">username <small>*</small></label>
            <input type="text" id="username" name="username">

            <label for="password">password <small>*</small></label>
            <input type="password" id="password" name="password">
            <br>
            <button type="submit" id="bConnect" name="bConnect" value="bConnect" class="login-btn">Connect</button>
            <button class="signup-btn" id="bSign" name="bSign" value="bSign">Sign up</button> 
            <button class="forgot-password" id="bPF" name="bPF" value="bPF">Forget Password</button>

        </form>

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
        }
        ?>

    </div>
</main>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bSign'])) {
        header("Location: signin.php");
        exit();
    }
    if (isset($_POST['bPF'])) {
        header("Location: forgetpass.php");
        exit();
    }
}

require("footer.php");
?>