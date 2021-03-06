<?php
require_once('includes/login_manager.php');
require_once('includes/page_manager.php');
require_once('includes/flag_manager.php');

if(!user_is_logged())
{
    header("Location: login.php");
    exit();
}

$user_data = get_user_data();

$page_row = get_page($_GET['id']);
$page_name = $site_name;

// Controllo auth
if($page_row === null) {
    $page_error = "This page doesn't exists.";
}
elseif($page_row['user_id'] !== $user_data['id'] && $user_data['rank'] < $admin_rank)
{
    $page_error = "Not authorized.";
}
elseif(isset($_SESSION['adminOnlyForUser']) && $_SESSION['adminOnlyForUser'] !== $page_row['user_id'] && $page_row['user_id'] !== 1)
{
    // Il cookie del bot ha in realtà permessi limitati, può accedere solo alle pagine del suo utente
    $page_error = "Not authorized.";
}
else
{
    $page_name = htmlspecialchars($page_row['name']);
    $page_content = $page_row['content'];

    // If we are in the admin page, return the personal user flag
    if($page_row['id'] === 1 && isset($_SESSION['adminOnlyForUser'])) {
        $username = get_name_by_id($_SESSION['adminOnlyForUser']);
        $user_flag = get_flag_for_user($username);

        $page_content = str_replace('flag{}', $user_flag, $page_content);
    }
}

?>

<?php include('includes/template_header.php'); ?>

<div id="page-wrapper">

<?php if(isset($page_error)): ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Page browser</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <?= htmlspecialchars($page_error) ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $page_name ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?= $page_content ?>
        </div>
    </div>
<?php endif; ?>

<?php include('includes/template_footer.php'); ?>
