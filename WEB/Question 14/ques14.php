<!DOCTYPE html>
<html>

<head>
    <title>Students Details List</title>
</head>

<body style="text-align: center; margin-top: 50px;">
    <h1>Student List Details</h1>
    <p>Here are some student names listed in this page.</p>
    <br><br>
</body>

<?php
    $students_list = array("Vivek Singh","Albert Franky","Shane Francis","Sameeksha Salvi","Kirk Mathias");
    print_r("<b>Student List : </b>");
    print_r($students_list);

    print_r("<br><br><b>Asortted Student List : </b>");
    asort($students_list);
    print_r($students_list);

    print_r("<br><br><b>Arsortted Student List : </b>");
    arsort($students_list);
    print_r($students_list);
?>


</html>