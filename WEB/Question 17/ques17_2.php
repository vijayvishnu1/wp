<?php
    $bookname_arr= null;
    $authorname_arr= null;
    $publisher_arr= null;

    $con=mysqli_connect("localhost","root","","comicbooks");
    if(isset($_POST['search_submit'])){
        $bookname= $_POST['bookname'];
        $search_query= "SELECT * FROM bookdetails WHERE bookname LIKE '%$bookname%'";
        $search_res= mysqli_query($con,$search_query);
        if($search_res){
            $bookname_arr= array();
            $authorname_arr= array();
            $publisher_arr= array();
            while($item = mysqli_fetch_array($search_res)){
                array_push($bookname_arr,$item['bookname']);
                array_push($authorname_arr,$item['authorname']);
                array_push($publisher_arr,$item['publisher']);
            }
        }
    }
?>

<html>
    <head>
        <title>Search Book Page</title>

        <style>

            #search_form{
                width: 300px;
                padding: 10px;
                text-align: center;
            }

            #search_form div{
                display: flex;
                flex-direction: column;
                margin-top: 20px;
            }

            #search_form div *{
                margin-top: 10px;
                padding: 5px 10px;
            }

            table tr th, table tr td{
                padding: 5px 10px;
            }
        </style>

    </head>
    <body>
        <center>
            <form action="ques17_2.php" method="POST" id="search_form">
                <div>
                    <h2>Comic Book Search</h2>
                    <input type="text" name="bookname" id="bookname" placeholder="Type the book name here to search....." required/>
                    <input type="submit" name="search_submit" id="search_submit" />
                </div>
            </form>

            <p><a href="ques17_1.php">Click here</a> to go back to book adding page</p>

            <br><br>
           
            <div style="display: <?php if(!isset($_POST['search_submit'])){ echo 'none'; }else{ if($bookname_arr!=null){ echo'block'; }else{ echo 'none'; }} ?>">
                <h2>Results of the search</h2>

                <table border="1" cellspacing="5">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Book Name</th>
                            <th>Author Name</th>
                            <th>Publisher Name</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            if($bookname_arr!=null){
                                for ($i=0; $i < sizeof($bookname_arr); $i++) {
                                    $sr= $i+1;
                                    echo"<tr>
                                            <td>$sr</td>
                                            <td>$bookname_arr[$i]</td>
                                            <td>$authorname_arr[$i]</td>
                                            <td>$publisher_arr[$i]</td>
                                        </tr>
                                    ";
                                }
                            }
                        ?>
                       
                    </tbody>
                </table>
            </div>
        
            <p style="display: <?php if(!isset($_POST['search_submit'])){ echo 'none'; }else{ if($bookname_arr!=null){ echo'none'; }else{ echo'block'; }} ?>">No such book found !!!</p>
        </center>
    </body>
</html>