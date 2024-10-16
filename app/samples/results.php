<?php
  include($_SERVER['DOCUMENT_ROOT'] . '/includes/header-top.php');
  require ('../../extras/vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
  
  if($_SESSION['university_id']=='48'){ 
    $header[] = array('Course', 'Sub-Course','Duration','Enrollment No.', 'Subject Name', 'Internal Marks', 'External Marks');
  }else{
    $header[] = array('Course', 'Sub-Course', 'Semester', 'Enrollment No.', 'Subject Name', 'Internal Marks', 'External Marks');
  }

  $xlsx = SimpleXLSXGen::fromArray( $header )->downloadAs('Results Sample.xlsx');
