<?php

    $title = "Boutique - Connexion";
    require("./Parts/Header.php");

    if( isset( $_SESSION['user'] ) )
        return header("Location: /index.php");

    require_once("./DB/Connection.php");

    if( isset($_POST['reg']) ){

        $name = filter_var( $_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var( $_POST['pwd'], FILTER_SANITIZE_STRING);
        $password2 = filter_var( $_POST['pwd2'], FILTER_SANITIZE_STRING);

        $errors=[];
        $status = false;
        // validate name:
        if( strlen($name) <3 )
            $errors['name'] = "Nom invalide, trop cout !";

        // email validation :
        if( !filter_var( $email, FILTER_VALIDATE_EMAIL) )
            $errors['email'] = "Adresse email invalide !";
        else{
            $MailQuery = $mysqli->query("SELECT count(*) as num FROM users WHERE email='$email' ");
            $MailQuery = $MailQuery->fetch_assoc();
            if( ((int) $MailQuery['num']) !== 0 )
                $errors['email'] = "Adresse email déjà enregistré !";
        }

        // validate passwords
        if( strlen($password)<6 )
            $errors['pwd'] = "mot de passe trop court, utilisez 6 caractères au min.";
        if( strcmp( $password, $password2)!==0 )
            $errors['pwd2'] = " confirmation de mot de passe est erronée ! ";

        if( !count($errors) ){
            $insertQuery = $mysqli->prepare("INSERT INTO users(name,email,password) values( ?, ?, ?) ");
            $insertQuery->bind_param('sss', $name, $email, $password);
            $insertQuery->execute();
            $status=!0;
        }


    }


?>

<div class="content">
    <div class="container">
        <div class="card rounded-3 bg-light py-3">
            <div class="card-body py-2 bg-light">
                <h3 class="h2 fw-normal align-center text-center mb-5 mt-3"> Créer un compte : </h3>
                <?php if( isset($status) && $status ){ ?>
                    <div class="alert alert-success py-4 px-2" role="alert">
                        <h3 class="h3 fw-normal text-center">
                            <i class="fas fa-check"></i> Votre compte est bien créé, vous pouvez vous-connectez !
                        </h3>
                    </div>
                <?php }else{ ?>
                    <form action="/register.php" method="post" class="col-12 col-md-10 col-lg-8 mx-auto">
                        <div class="form-group">
                            <label for="name">Votre nom</label>
                            <input type="text" class="form-control" name="name" required id="name" value="<?= isset($name) ? $name:null ?>" aria-describedby="emailHelpId" maxlength="50" >
                            <?php if( isset($errors) && isset($errors['name']) ) { ?>
                                <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                                    <?= $errors['name']?>
                                    <button type="button" class="btn-close pt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" required name="email" id="email" aria-describedby="emailHelpId" >
                            <?php if( isset($errors) && isset($errors['email']) ) { ?>
                                <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                                    <?= $errors['email']?>
                                    <button type="button" class="btn-close pt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group mt-3">
                            <label for="pwd">Mot de Passe </label>
                            <input type="password" class="form-control" required name="pwd" id="pwd" >
                            <?php if( isset($errors) && isset($errors['pwd']) ) { ?>
                                <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                                    <?= $errors['pwd']?>
                                    <button type="button" class="btn-close pt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group mt-3">
                            <label for="pwd2">Rentrez le mot de Passe </label>
                            <input type="password" class="form-control" required name="pwd2" id="pwd2" >
                            <?php if( isset($errors) && isset($errors['pwd2']) ) { ?>
                                <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                                    <?= $errors['pwd2']?>
                                    <button type="button" class="btn-close pt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row mt-3">
                            <button type="submit" name="reg" value="1" class="btn btn-outline-success col-auto mx-auto px-4"> <i class="fas fa-user-plus"></i> Créer votre compte </button>
                        </div>
                        <hr class="my-4">
                    </form>
                <?php } ?>

                <div class="row mt-4 mb-3">
                    <a class="btn btn-outline-primary col-auto mx-auto px-4" href="/login.php" role="button"> <i class="fas fa-sign-in-alt"></i> se connecter </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require("./Parts/Footer.php");
?>
