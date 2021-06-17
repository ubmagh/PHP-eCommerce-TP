<?php

    $title = "Boutique - Connexion";


    require_once("./DB/Connection.php");
    require("./Parts/Header.php");

    if( isset( $_SESSION['user'] ) )
        return header("Location: /index.php");

    if( isset($_POST['login']) ){

        $email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var( $_POST['pwd'], FILTER_SANITIZE_STRING);

        // Log-in
        $LoginQuery = $mysqli->query("SELECT *,count(*) AS num FROM users WHERE email='$email' AND password='$password' ");
        $result = $LoginQuery->fetch_assoc();
        $logginFails = true;
        if( ( (int) $result['num'] )==1 ){
            unset($result['num']);
            $user = (object) $result;
            $_SESSION['user'] = $user;
            $logginFails = !1;
            header("Location: /index.php");
        }
    }

?>

<div class="content">
    <div class="container">
        <div class="card rounded-3 bg-light py-3">
            <div class="card-body py-2 bg-light">
                <h3 class="h2 fw-normal align-center text-center mb-5 mt-3"> Connexion : </h3>
                <form action="/login.php" method="post" class="col-12 col-md-10 col-lg-8 mx-auto">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelpId" >
                    </div>
                    <div class="form-group mt-3">
                        <label for="pwd">Mot de Passe </label>
                        <input type="password" class="form-control" name="pwd" id="pwd" >
                    </div>
                    <?php if( isset($logginFails) && $logginFails ){ ?>
                        <div class="row col-11 col-md-8 mx-auto mt-3 mb-2">
                            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                Mot de passe ou email erroné !
                                <button type="button" class="btn-close pt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row  mt-3">
                        <button type="submit" name="login" value="1" class="btn btn-outline-primary col-auto mx-auto px-4"> <i class="fas fa-sign-in-alt"></i> se connecter </button>
                    </div>
                    <hr class="my-4">
                </form>

                <div class="row mt-4 mb-3">
                    <a class="btn btn-outline-success col-auto mx-auto px-4" href="/register.php" role="button"> <i class="fas fa-user-plus"></i> Créer votre compte </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require("./Parts/Footer.php");
?>
