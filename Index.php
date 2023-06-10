<!-- Gabriel Uratdo -->


<?php
require 'idex.php';
session_start();
display_html_head();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['submit'] == 'Submit'){
        $errors = validate_form();
        if($errors){
            display_form($errors);
        }else{
            confirm_form();
        } 
    }elseif($_POST['submit'] == 'Confirm'){
        process_form();
    }elseif ($_POST['submit'] == 'Edit'){
        display_form();
    }
}else{
    display_form();
    session_unset();

    }
display_html_foot();
?>