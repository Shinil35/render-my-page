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

$page_name = $site_name." - User management";

$user_count = 6;
?>

<?php include('includes/template_header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">User management</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $user_count; ?></div>
                            <div>Users Registered!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">

            </div>
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

<?php include('includes/template_footer.php'); ?>
