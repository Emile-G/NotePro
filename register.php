<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
    if(!isset($_SESSION["username"])){
        header("Location: login.php");
        exit(); 
    }

$res = false;
$dsn="mysql:host=localhost;dbname=notepro";
$user="notepro_user";
$mdp="password";
try{
    $connexion = new PDO ($dsn,$user,$mdp);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $smt = $connexion->prepare('select * From rang');
    $smt->execute();
    $data = $smt->fetchAll();
}catch(PDOException $e){
    die($dsn."erreur".$e->getMessage());
}

if(isset($_POST["password"]) && isset($_POST["username"]) && isset($_POST["grade"])){
//verification du mot de passe/
    $password = $_POST["password"];
    $username = $_POST["username"];
    $grade = $_POST["grade"];
    try{
        $connexion = new PDO ($dsn,$user,$mdp);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //insertion dans la table user
        $req="INSERT INTO utilisateurs(username,grade,password) values (:u , :g, :p)";
        $traitement = $connexion->prepare($req);
        $traitement->bindvalue(':u',$username);
        $traitement->bindvalue(':g',$grade);
        $traitement->bindvalue(':p',password_hash($password,PASSWORD_DEFAULT, ['cost' => 12]));
        $traitement->execute();
        if($grade == 2) {
            header("Location: dprof.php");
        }
        $connexion=NULL;
        $res = true;
    } catch(PDOException $e){
        die($dsn."erreur".$e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>Formulaire d'inscription</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel="stylesheet" href="https://bootswatch.com/4/sketchy/bootstrap.min.css" crossorigin="anonymous">
        <!--<link rel="stylesheet" href="/src/style.css" crossorigin="anonymous">-->
        <script src="https://kit.fontawesome.com/058ba3cd65.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="/">NotePro</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
        <div class="jumbotron border-0">
            <div class="card mb-3">
                <h3 class="card-header">Ajout d'un utilisateur</h3>
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
                            <div class="form-group">
                                <label for="grade">Selection du grade</label>
                                <select class="form-control" id="grade" name="grade">
                                    <?php foreach ($data as $row): ?>
                                        <option value="<?=$row["id"]?>"><?=$row["label_rang"]?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Valider</button>
                            <?php if (! empty($message)) { ?>
                                <p class="errorMessage"><?php echo $message; ?></p>
                            <?php } ?>
                        </fieldset>
                    </form>
                </div>
                <div class="card-footer text-muted">
                    <?php
                        if($res == true){
                            print "
                            <p class='lead text-success'>Ajout réussi !</p>
                            ";
                        }
                    ?>   
                </div>
            </div>
        </div>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>