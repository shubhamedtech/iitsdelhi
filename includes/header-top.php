<?php
function session_error_function()
{
  header("Location: /");
}

set_error_handler('session_error_function');
session_start();
if (!isset($_SESSION['Role'])) {
  header("Location: /");
}
restore_error_handler();
date_default_timezone_set('Asia/Kolkata');
header('Content-Type: text/html; charset=utf-8');

include($_SERVER['DOCUMENT_ROOT'] . '/includes/db-config.php');
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title> <?= $organization_name ?> | <?= $_SESSION['Role'] == 'Administrator' ? 'Admin' : implode(', ', $_SESSION['university_name']) ?></title>
  <meta name="description" content=" " />
  <!--<link rel="apple-touch-icon" href="/assets/pages/ico/60.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/assets/pages/ico/76.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/assets/pages/ico/120.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/assets/pages/ico/152.png">-->
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
   <link rel="stylesheet" href="/assets/vendor/fonts/remixicon/remixicon.css" />
  <link rel="stylesheet" href="/assets/vendor/fonts/flag-icons.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="/assets/css/demo.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
  <link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
  <link rel="stylesheet" href="/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
  <link rel="stylesheet" href="/assets/vendor/libs/apex-charts/apex-charts.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
  <link rel="stylesheets" href="/assets/vendor/libs/toastr/toastr.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src="/assets/vendor/js/helpers.js"></script>
  <script src="/assets/vendor/js/template-customizer.js"></script>
  <script src="/assets/js/config.js"></script>


  <link rel="stylesheet" href="/assets/vendor/libs/bs-stepper/bs-stepper.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
  <link rel="stylesheet" href="/assets/vendor/libs/flatpickr/flatpickr.css" />
  <script>
    function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
      return true;
    }
  </script>

  <style>
    .table td {
      vertical-align: middle !important;
    }

    .toast {
      background-color: #030303 !important;
    }

    .toast-success {
      background-color: #51a351 !important;
    }

    .toast-error {
      background-color: #bd362f !important;
    }

    .toast-info {
      background-color: #2f96b4 !important;
    }

    .toast-warning {
      background-color: #f89406 !important;
    }
  </style>