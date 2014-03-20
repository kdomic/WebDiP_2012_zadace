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
    <table>
        <tr>
            <th>ID</th>
            <th>Korisnik</th>
            <th>Log</th>
            <th>Zapis</th>
            <th>Datum</th>
        </tr>
        <?php
            $logs = $database->find_by_sql("SELECT * FROM logovi ORDER BY 1 DESC limit 10");
            foreach ($logs as $l) {
                $kor = $database->find_by_sql("SELECT * FROM korisnici WHERE id=".$l['id_korisnika']);
                $l['id_korisnika'] = $kor[0]['email'];
                $tip = $database->find_by_sql("SELECT * FROM log_tip WHERE id=".$l['id_tip']);
                $l['id_tip'] = $tip[0]['opis'];
                echo '<tr>';
                echo '<td>'.$l['id'].'</td>';
                echo '<td>'.$l['id_korisnika'].'</td>';
                echo '<td>'.$l['id_tip'].'</td>';
                echo '<td>'.$l['kljuc'].'</td>';
                echo '<td>'.$l['datum'].'</td>';                
                echo '</tr>';                
            }
        ?>
    </table>
</section>

<?php include('kdomic_footer.php'); ?>
