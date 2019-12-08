<?php
require_once('includes/login_manager.php');

if(user_is_logged())
{
	header("Location: index.php");
	exit();
}

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirmation']))
{
    if(empty($_POST['username']))
    {
        $reg_error = "Username cannot be empty!";
    }
    elseif(empty($_POST['password']))
    {
        $reg_error = "Password cannot be empty!";
    }
    elseif(strlen($_POST['password']) < 8)
    {
        $reg_error = "Password must be at least 8 characters long";
    }
    elseif(empty($_POST['password_confirmation']))
    {
        $reg_error = "Password confirmation cannot be empty";
    }
    elseif($_POST['password_confirmation'] !== $_POST['password'])
    {
        $reg_error = "Password confirmation doesn't match";
    }
    elseif(!validate_username($_POST['username']))
    {
        $reg_error = "Username must contain only letters and numbers";
    }
    elseif(username_exists($_POST['username']))
    {
        $reg_error = "Username already taken";
    }
    elseif(!register($_POST['username'], $_POST['password']))
    {
        $reg_error = "Registration failed, please ask to support";
    }
    else
    {
        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $site_name ?> - Registration</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php if(isset($reg_error)): ?>
                <div class="login-panel alert alert-danger">
                    <?= $reg_error ?>
                </div>
            <?php endif; ?>

            <div class="<?php if(!isset($reg_error) && !(isset($_GET['logout']) && $_GET['logout'] == 1)) echo("login-panel"); ?> panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Registration</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="registration.php" method="POST">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password Confirmation" name="password_confirmation" type="password" value="">
                            </div>

                            <input type="submit" class="btn btn-lg btn-success btn-block" value="Submit">
                            <a href="login.php" class="btn btn-default btn-lg btn-block">Already registered?</a>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="vendor/metisMenu/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

</body>

</html>