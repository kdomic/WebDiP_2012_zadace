<?php include('kdomic_header.php'); ?>
            
<section>
    <?php

        $handle = opendir('upload/');
        while (false !== ($entry = readdir($handle)))
            if(strpos($entry,'.jpg'))
                echo '<a href="upload/'.$entry.'" rel="lightbox" title="'.$entry.'"><img src="upload/thumb/'.$entry.'" alt="" /></a> <br>';
        closedir($handle);
    ?>
    
</section>

<?php include('kdomic_footer.php'); ?>
