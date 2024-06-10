<?php
    include './include/config.php';

    if (isset($_GET['id'])) {
        runQuery("DELETE FROM student WHERE matricno='".$_GET["id"]."'");
        header("Location: student-list?delete");
    }
?>