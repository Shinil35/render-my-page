<?php
// Get real IP even if using cloudflare
$cloudflare_enabled = false;

// Challenge difficulty settings
$enable_csrf_new_page_creation = true;      // Enable CSRF token check on page creation form
$enable_script_tag_detection = true;        // Blocks page creation with `<script>`
$enable_document_cookie_detection = true;   // Blocks page creation with `document.cookie`
$enable_redirect_detection = true;          // Blocks page creation with `location.href` or `location.replace`
$enable_check_b64_strings = true;           // Search also in b64 encoded strings for the latest 2 rules

$base64_regex = '((?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?)';

// Use PHP Sessions only if we have an HTTPS connection
$session_https_strict = false;

$site_name = "Render My Page";
$site_version = "v3.0";

$db_host = "18.185.53.173";
$db_user = "web";
$db_pass = "E2ZAmXjDfXV0hjXC";
$db_name = "web";

$bot_backdoor_key = "3074bc8c-0a1f-48ae-aedc-54fe9fdd8a32";

$admin_rank = 7;
