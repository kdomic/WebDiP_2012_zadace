<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('Location: kdomic_index.php');
        exit();
    }
?>
<?php include('kdomic_header.php'); ?>
            
<section>
    <?php
        if(isset($_POST['submit'])){
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " već postoji. ";
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);

                $thumbWidth = 100;
                $img = imagecreatefromjpeg( "upload/" . $_FILES["file"]["name"] );
                $width = imagesx( $img );
                $height = imagesy( $img );
                $new_width = $thumbWidth;
                $new_height = floor( $height * ( $thumbWidth / $width ) );
                $tmp_img = imagecreatetruecolor( $new_width, $new_height );
                imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
                imagejpeg( $tmp_img, "upload/thumb/" . $_FILES["file"]["name"] );

                echo "Pohranjeno: " . "upload/" . $_FILES["file"]["name"];
                echo "<br><br>";
                echo "<a href=kdomic_slike.php>Pogledaj galeriju</a>";
                echo "<br><br>";               
            }
        }
    ?>
    <form action="kdomic_upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Datoteka:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submit" value="Pohrani">
    </form>
</section>

<?php include('kdomic_footer.php'); ?>
