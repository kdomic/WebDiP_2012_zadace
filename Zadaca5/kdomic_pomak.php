<?php session_start(); ?>
<?php
    $vrijeme = 0;
    require_once("korisnici.php");
    $database = new KorisnikBAZA();

    if(isset($_SESSION['user_id'])){
        $mod = $database->find_by_sql("SELECT * FROM korisnici WHERE id=".$_SESSION['user_id']);
        $mod = array_pop($mod);
        if($mod['ovlasti']!=3){
            header('Location: kdomic_index.php');
            exit();
        }
    } else {
        header('Location: kdomic_index.php');
        exit();
    }

    include('kdomic_header.php');

    if(isset($_GET['a'])){
        $link = "http://arka.foi.hr/PzaWeb/PzaWeb2004/config/pomak.xml";
        $xml =  simplexml_load_file($link);
        $vrijeme = $xml->vrijeme->pomak->attributes()->brojSati;
        $sql  = "UPDATE vrijeme SET pomak=".$vrijeme;
        $database->update_by_sql($sql);
    }
    $vrijeme = $database->find_by_sql("SELECT * FROM vrijeme");
    $vrijeme = $vrijeme[0]['pomak'];
?>
            
<section>
    Trenutni pomak: <b><?php echo $vrijeme; ?></b><br>
    Pomak vremena moguće je upisati: <a href="http://arka.foi.hr/PzaWeb/PzaWeb2004/config/vrijeme.html" target="_blank">ovdje</a> <br>
    Nakon promjene vremena pritisnite gumb za preuzimanje <br>
    <a href="kdomic_pomak.php?a=1"> Preuzmi vrijeme </a>
</section>

<?php include('kdomic_footer.php'); ?>
