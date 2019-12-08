<?php
require_once('includes/login_manager.php');

if(!user_is_logged())
{
    header("Location: login.php");
    exit();
}

$user_data = get_user_data();

$page_name = $site_name;

?>

<?php include('includes/template_header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">About our service</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-body">
                    <p>
                        We like so much <b>HTML</b> syntax that we want to be able to write documents on it and get beautiful screenshots!
                    </p>
                    <p>
                        Take a look of these examples:
                        <!-- TODO: BETTER SERVICE DESCRIPTION -->
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->

<?php include('includes/template_footer.php'); ?>
