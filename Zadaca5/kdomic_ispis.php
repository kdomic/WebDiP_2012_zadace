<?php
    session_start();
    require_once("korisnici.php");
    $database = new KorisnikBAZA();

    if(isset($_GET['contentType'])){
        $korisnici = $database->find_by_sql("SELECT * FROM korisnici");
        if($_GET['contentType']=='xml'){
            header('Content-Type: application/xml');
            echo '<?xml version = "1.0" encoding = "utf-8" ?>';
            echo '<korisnici>';
            foreach ($korisnici as $kor) {
                echo "<korisnik";
                echo " korisnicko_ime='" . $kor['email'] . "' ";
                echo " ime='" . $kor['ime'] . "' ";
                echo " prezime='" . $kor['prezime'] . "' ";
                echo "/>";
            }
            echo '</korisnici>';            
        } else if($_GET['contentType']=='json') {
            header('Content-Type: application/json');
            echo json_encode($korisnici);
        }
        exit();
    }

    if(isset($_GET['id'])){
        $mod = '';
        if(isset($_SESSION['user_id'])){
            $mod = $database->find_by_sql("SELECT * FROM korisnici WHERE id=".$_SESSION['user_id']);
            $mod = array_pop($mod);
        }
        if(!isset($_SESSION['user_id']) || ($mod['ovlasti']!=3 && $_GET['id']!=$_SESSION['user_id'])){            
            $kor = 0;
            if(isset($_SESSION['user_id']))
                $kor = $_SESSION['user_id'];
            $vrijeme = $database->find_by_sql("SELECT * FROM vrijeme");
            $datum = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"))+(1*60*60*(int)$vrijeme[0]['pomak']));
            $query  = "INSERT INTO logovi (id_korisnika,id_tip,kljuc,datum) VALUES ";
            $query .= "(".$kor.",17,".$kor.",'".$datum."');";
            $database->insert_by_sql($query);
            header('Location: kdomic_ispis.php?error=3');
            exit();
        }
    }
    include('kdomic_header.php');
?>

            <section>
            <?php
                if(isset($_POST['submit'])):                    
                    $data = array();
                    array_pop($_POST);
                    foreach ($_POST as $key => $value)
                        $data[] = "{$key}='{$value}'";              
                    $sql  = "UPDATE korisnici SET " . join(", ", $data);
                    $sql .= " WHERE id={$_POST['id']}";
                    $database->update_by_sql($sql);
                endif;
                if(isset($_GET['id'])):   
                    $korisnici = $database->find_by_sql("SELECT * FROM korisnici WHERE id=".$_GET['id']);
                    $korisnik = array_pop($korisnici);
            ?>
                <form action="kdomic_ispis.php" method="POST" autocomplete="off">
                        <input type="hidden" name="id" value="<?php echo $korisnik['id']; ?>" />
                    <table class="register">
                        <tr>
                            <td><label for="ime">Ime</label></td>
                            <td><input type="text" name="ime" id="ime" required placeholder="Vaše ime" autofocus value="<?php echo $korisnik['ime'] ?>"/></td>
                        </tr>
                        <tr>
                            <td><label for="prezime">Prezime</label></td>
                            <td><input type="text" name="prezime" id="prezime" required placeholder="Vaše prezime" value="<?php echo $korisnik['prezime'] ?>"/></td>
                        </tr>
                        <tr>
                            <td><label for="email">Email</label></td>
                            <td><input type="email" name="email" id="email" required placeholder="nesto@foi.hr" value="<?php echo $korisnik['email'] ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="password">Lozinka</label></td>
                            <td><input type="password" name="password" id="password"  pattern="[a-zA-Z0-9]{6,}" required placeholder="Lozinka" value="<?php echo $korisnik['password'] ?>"/></td>
                        </tr>
                        <tr>
                            <td><label for="telefon">Broj telefona</label></td>
                            <td><input type="tel" name="telefon" id="telefon" pattern="\d{3}\ \d{6,7}" required placeholder="123 1234567" value="<?php echo $korisnik['telefon'] ?>"/></td>
                        </tr>                       
                        <tr>
                            <td><label for="mjesto">Grad</label></td>
                            <td><input id="mjesto" name="mjesto" placeholder="Grad" required value="<?php echo $korisnik['mjesto'] ?>" /></td>
                        </tr>

                        <tr>
                            <td><label for="aktivan">Aktivan (1-da / 0-ne)</label></td>
                            <td><input id="aktivan" name="aktivan" placeholder="aktivan" required value="<?php echo $korisnik['aktivan'] ?>" /></td>
                        </tr>

                        <tr>
                            <td colspan="2" class="center"><input type="submit" name="submit" id="posalji" value="Pošalji"/></td>
                        </tr>
                    </table>
                </form>
            <?php
                    else:
                        echo "<table>";
                        echo "<thead>";
                            echo "<tr><th>ID</th><th>Ime</th><th>Korisničko</th><th>Lozinka</th><th>uredi</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        $korisnici = $database->find_by_sql("SELECT * FROM korisnici");                        
                        foreach ($korisnici as $korisnik) {
                            echo '<tr>';
                                echo '<td>'.$korisnik['id'].'</td>';
                                echo '<td>'.$korisnik['ime'].' '.$korisnik['prezime'].'</td>';
                                echo '<td>'.$korisnik['email'].'</td>';
                                echo '<td>'.$korisnik['password'].'</td>';
                                if(isset($_SESSION['user_id']))
                                    if($mod['ovlasti']==3 || $korisnik['id']==$_SESSION['user_id'])
                                    echo '<td><a href=kdomic_ispis.php?id='.$korisnik['id'].'>uredi</a></td>';
                            echo '</tr>';
                        }
                        echo "</tbody>";
                        echo "</table>";
                    endif;
            ?>
            </section>
<?php include('kdomic_footer.php'); ?>