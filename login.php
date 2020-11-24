<?php
if (isset($_POST["password"]) && isset($_POST["username"])){
    $dsn="mysql:host=localhost;dbname=notepro";
    $user="notepro_user";
    $mdp="password";
    $connexion = new PDO ($dsn,$user,$mdp);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $verification = $_POST['password']; //$verification prend la valeur de $_post['pass']
    $t = $connexion->prepare('SELECT password FROM utilisateurs WHERE username =:u');//prépare la requête "selectionne les valeur dans pass dans la table user où login saisi=celui enregistré"
    $t->bindvalue(':u',$_POST["username"]);//:l prend la valeur de $_POST['login']
    $t->execute();//execute les requetes présente dans $t
    $res = $t->fetch();//récupère la valeur de pass dans la BDD
    if (password_verify($verification,$res["password"]))//On regarde que la valeur saisie corresspond à celle de la BDD
    {
        session_start();
        $smt = $connexion->prepare('SELECT id_user FROM utilisateurs WHERE username =:u AND password = :p');//prépare la requête "selectionne les valeur dans pass dans la table user où login saisi=celui enregistré"
        $smt->bindvalue(':u',$_POST["username"]);//:l prend la valeur de $_POST['login']
        $smt->bindvalue(':p',$res['password']);
        $smt->execute();//execute les requetes présente dans $t
        $res2 = $smt->fetch();//récupère la valeur de pass dans la BDD
        $_SESSION['id']=$res2['id_user'];
        $_SESSION['username']=$_POST['username'];
        header('Location:index.php');//redirige vers la page resultat.php
        exit();
    } else {
        $message = "Nom d'utilisateur et/ou mot de passe incorrecte";//affiche ce message si les conditions ne sont pas respecter
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Connection à NotePro</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel="stylesheet" href="https://bootswatch.com/4/sketchy/bootstrap.min.css" crossorigin="anonymous">
        <!--<link rel="stylesheet" href="/src/style.css" crossorigin="anonymous">-->
        <script src="https://kit.fontawesome.com/058ba3cd65.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="jumbotron border-0">
            <div class="card mb-3">
                <h3 class="card-header">Connection à NotePro</h3>
                <div class="card-body bg-lightdark">
                    <img src="src/images/logo_notepro.png"/>
                </div>
                <div class="card-body bg-lightdark">
                    <form action="" method="post">
                        <fieldset>
                            <div class="form-group">
                                <label for="username">Nom d'utilisateur</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Entrez le nom d'utilisateur">
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Entre le mot de passe">
                            </div>
                            <button type="submit" class="btn btn-success">Connection</button>
                        </fieldset>
                    </form>
                </div>
                <div class="card-footer text-muted"> 
                    <?php if (!empty($message)) { ?>
                        <p class="text-danger"><?php echo $message; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>