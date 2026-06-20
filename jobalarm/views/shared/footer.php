    <?php
    if (!Config::get('noheader')):
    ?>
        </div>
    </div>
    </div>
<?php 
    if (Config::get('chatblock')) :
?>
    <div id="chatContainer">
        <div class="chatContent">

        </div>
    </div>
<?php
    endif;
    endif;
?>

</body>
</html>