<?php
    $con=mysqli_connect("localhost","root","","sampledb");
    $sql = " SELECT * FROM users" ;
    $result=$con->query($sql);
?>
<html>
    <head>
        <title>User Details</title>
        <style>
            .container{
                margin-top: 50px;
            }

            table tr th, table tr td{
                padding: 5px 10px;
            }
        </style>
    </head>
    <body>
        <center>
        <div class="container">
            <h2>User Details</h2>
            <p>Basic user details is shown below using the basic database connection.</p>
            <table border="1">
                <tr>
                    <th>User ID</th>
                    <th>User's Name</th>
                    <th>User's Degree</th>
                    <th>User's Designation</th>
                </tr>
                <?php
                    if($result->num_rows>0)
                    {
                        while($row=$result->fetch_assoc())
                        {
                            ?>
                            <tr>
                                <td><?php echo $row['user_id'];?></td>
                                <td><?php echo $row['user_name'];?></td>
                                <td><?php echo $row['user_degree'];?></td>
                                <td><?php echo $row['user_designation'];?></td>
                            </tr>
                            <?php
                        }
                    }
                ?>
            </table>
        </div>
        </center>
    </body>
</html>
