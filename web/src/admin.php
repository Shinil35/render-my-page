<?php
require_once('includes/login_manager.php');

if(!user_is_logged())
{
    header("Location: login.php");
    exit();
}

$user_data = get_user_data();

if($user_data['rank'] < $admin_rank)
{
    header("Location: index.php");
    exit();
}

$page_name = $site_name;

?>

<?php include('includes/template_header.php'); ?>



<?php include('includes/template_footer.php'); ?>
