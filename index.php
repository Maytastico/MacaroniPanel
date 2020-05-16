<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.10.2019
 * Time: 00:51
 */
include_once "assets/php/Config.php";
include_once "_includes/autoloader.inc.php";
Loader::jump(0);
include_once "_includes/header.inc.php";

Loader::importJavaScript("Table.js");
?>
<body>
<section id="coolTable">

</section>

</body>
<script>let tablee = new Table({
        header: ["hi", "makaroni", "was"],
        drawHeader: true,
        generateTableContainer: "#coolTable",
        pageButtons: true
    });</script>
