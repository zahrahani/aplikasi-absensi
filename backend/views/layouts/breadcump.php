<?php 

// Inisiasi uri untuk breadcump
$arrLink = $_SERVER['REQUEST_URI'];
$breadcumps = explode('/', $arrLink);
$endLink = end($breadcumps);



?>


<style>
    .link {
      font-family: sans-serif;
      color: black;
      font-style: italic;
      text-decoration: none;
    }

    .active {
      color: rgb(13, 110, 253);
    }

</style>


  <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <?php foreach($breadcumps as $text) { ?>

          <?php if ($text != 'presensi') { ?>  
            <li class="breadcrumb-item"><a class="link <?= ($endLink == $text)? 'active':'' ?>" href=""><?= ucwords($text) ?></a></li>
          <?php } ?>

        <?php } ?>

        </ol>
      </nav>