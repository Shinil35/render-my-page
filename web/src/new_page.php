<?php
require_once('includes/login_manager.php');
require_once('includes/page_manager.php');

if(!user_is_logged())
{
    header("Location: login.php");
    exit();
}

$user_data = get_user_data();

$page_name = $site_name;

if(isset($_POST['name']) && isset($_POST['content']) && isset($_POST['csrf_token'])) {
    if($user_data['id'] === 1) {
        // Disable page creation for admin
        $new_error = "Hey! You are here to get the flag, don't mess it up ;)";
    }
    elseif($enable_csrf_new_page_creation && $_SESSION['csrf_token'] != $_POST['csrf_token'])
    {
        $new_error = "CSRF Token mismatch.";
    }
    elseif(empty($_POST['name']))
    {
        $new_error = "Page name can't be empty!";
    }
    elseif(empty($_POST['content']))
    {
        $new_error = "Page content can't be empty!";
    }
    elseif(strlen($_POST['name']) > 128)
    {
        $new_error = "Page name can't be more than 128 characters long.";
    }
    elseif($enable_script_tag_detection && strpos($_POST['content'], '<script>') !== false)
    {
        // Avoid by using onload tag
        $new_error = "Hey, I don't like scripts.";
    }
    elseif($enable_document_cookie_detection && strpos($_POST['content'], 'document.cookie') !== false)
    {
        // Avoid by using eval+atob
        $new_error = "Sorry, I don't want to give you my nutella biscuits.";
    }
    else
    {
        $result = insert_page($user_data['id'], $_POST['name'], $_POST['content']);

        if($result) {
            $new_success = "Page created.";
        } else {
            $new_error = "Creation failed, please ask to support";
        }
    }
}

$csrf_token = gen_uuid4();
$_SESSION['csrf_token'] = $csrf_token;

?>

<?php include('includes/template_header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create a new page</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <?php if(isset($new_success)): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <?= htmlspecialchars($new_success) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(isset($new_error)): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <?= htmlspecialchars($new_error) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-12">
            <form id="page-form" method="POST" action="new_page.php">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">Page name </span>
                    <input name="name" type="text" class="form-control" aria-describedby="sizing-addon1">
                </div>
                <input type="hidden" id="content" name="content"/>
                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>"/>
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div id="editor" form="page-form">
            <h1><strong style="color: rgb(102, 163, 224);">Write your beautiful page here</strong></h1><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <button id="create-button" class="btn btn-success btn-lg">Create page</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Initialize Quill editor -->
<script>
var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['blockquote', 'code-block'],

  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['clean']                                         // remove formatting button
];

var quill = new Quill('#editor', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});

$(document).ready(function () {
    $("#create-button").click(function() {
        $("#content").val(quill.container.firstChild.innerHTML);
        $("#page-form").submit();
    });
});

</script>

<?php include('includes/template_footer.php'); ?>
