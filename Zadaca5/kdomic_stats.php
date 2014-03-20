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
?>
            
<section>
    <?php
        $k = $database->find_by_sql("SELECT count(*) AS br FROM korisnici");
        $ukupno = $k[0]['br'];
        $k = $database->find_by_sql("SELECT count(*) AS br FROM korisnici WHERE email_potvrda!='aktivan'");
        $neakriviranih = $k[0]['br'];
        $k = $database->find_by_sql("SELECT count(*) AS br FROM korisnici WHERE aktivan=0");
        $blokiranih = $k[0]['br'];
    ?>
    <canvas id="platno" width="600" height="400"></canvas>
    <script type="text/javascript">
        var platno = document.getElementById("platno");
        var ctx = platno.getContext("2d");
        ctx.fillStyle = "rgb(0, 0, 0)";
        ctx.strokeRect(40, 0, 320, 400);
        
        ctx.fillStyle = "rgb(0,0,255)";
        ctx.fillRect(100 + 40 * (0 - 1), 400 - <?php echo $ukupno*10; ?>, 38, 400);

        ctx.fillStyle = "rgb(0,0,0)";
        ctx.fillRect(100 + 40 * (1 - 1), 400 - <?php echo $neakriviranih*10; ?>, 38, 400);

        ctx.fillStyle = "rgb(0,255,0)";
        ctx.fillRect(100 + 40 * (2 - 1), 400 - <?php echo $blokiranih*10; ?>, 38, 400);
    </script>
    <p>
        <span style="color:rgb(0,0,255)">Ukupno: <?php echo $ukupno; ?><br></span>
        <span style="color:rgb(0,0,0)">Neaktivirani: <?php echo $neakriviranih; ?><br></span>
        <span style="color:rgb(0,255,0)">Blokirani: <?php echo $blokiranih; ?><br></span>        
    </p>
</section>

<?php include('kdomic_footer.php'); ?>
