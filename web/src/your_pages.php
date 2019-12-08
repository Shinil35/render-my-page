<?php
require_once('includes/login_manager.php');

if(!user_is_logged())
{
    header("Location: login.php");
    exit();
}

$user_data = get_user_data();
$page_name = $site_name;

$db = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);
$pages = $db->query("SELECT pages.*, users.username FROM pages JOIN users ON pages.user_id = users.id WHERE pages.user_id = ".intval($user_data['id']));
?>

<?php include('includes/template_header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Your pages</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php if($pages->num_rows <= 0): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        You don't have any page!<br>
                        <a href="new_page.php">Create one now</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-2">ID</th>
                            <th class="col-md-2">Page Name</th>
                            <th class="col-md-2">Owner</th>
                            <th class="col-md-2">Created on</th>
                            <th class="col-md-2">Image</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($page_row = $pages->fetch_assoc()) {
                                $id = $page_row['id'];
                                $name = htmlspecialchars($page_row['name']);
                                $created = $page_row['created'];
                                $owner = htmlspecialchars($page_row['username']);
                                $imagesrc = $page_row['image'];
                                $processed = $page_row['processed'];
                            ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><a href="page.php?id=<?= $id ?>"><?= $name ?></a></td>
                                    <td><?= $owner ?></td>
                                    <td><?= $created ?></td>
                                    <td>
                                        <?php if(!$processed): ?>
                                            <b>We are still processing your page</b>
                                        <?php else: ?>
                                            <a href="<?= $imagesrc ?>" download="<?= $name ?>">
                                                <img src="<?= $imagesrc ?>" style="width: 100%; maxheight: 200px;">
                                                <?php if($processed): ?>Click to Download<?php endif; ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    <?php endif; ?>
</div>
<!-- /#page-wrapper -->

<?php include('includes/template_footer.php'); ?>
