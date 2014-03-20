<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/kdomic_main.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" />
        <link rel="stylesheet" type="text/css" href="css/lightbox.css" />        

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="js/lightbox.js"></script>            
        <script type="text/javascript" src="js/kdomic_main.js"></script>
    </head>

    <body>
        <div id="outerDiv">
            <header>
                <nav id="mainMenu">
                    <ul>
                        <li><a href="kdomic_index.php">Početna</a></li>
                        <?php if(!isset($_SESSION['user_id'])): ?>                        
                            <li><a href="kdomic_register.php">Registracija</a></li>
                            <li><a href="kdomic_login.php">Prijava</a></li>
                        <?php endif; ?>
                        <li><a href="kdomic_dijagrami.html">Dijagrami</a></li>
                        <li><a href="privatno/kdomic_ispis.php">Privatno</a></li>
                        <?php 
                            if(isset($_SESSION['user_id'])): 
                                require_once("korisnici.php");
                                $database = new KorisnikBAZA();
                                $mod = $database->find_by_sql("SELECT * FROM korisnici WHERE id=".$_SESSION['user_id']);
                                $mod = array_pop($mod);
                        ?>
                            <li><a href="kdomic_logout.php">Odjava[<?php echo $mod['ime']; ?>]</a></li>        
                        <?php endif; ?>                                                            
                    </ul>
                </nav>
                <h1>Krunoslav Domić </h1>
            </header>
            <div class="subMenu"> </div>