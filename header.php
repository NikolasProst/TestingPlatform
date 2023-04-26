<?php 
session_start();
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <title>Online Test  | <?php echo ucfirst($title); ?></title>
        <link rel="stylesheet" type="text/css" href="css/style_v2.css">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container-fluid header">
            <div class="row">
                <a href="index.php"><img class="logo" src="images/logo.jpg" alt="online exam"></a>
            </div>
        </div>


