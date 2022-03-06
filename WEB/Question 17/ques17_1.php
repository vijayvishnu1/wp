<?php

    if(isset($_POST['reg_submit'])){
        $bookname= $_POST['bookname'];
        $authorname= $_POST['authorname'];
        $publisher= $_POST['publisher'];

        $con=mysqli_connect("localhost","root","","comicbooks");
        $reg= "INSERT INTO bookdetails values(NULL,'$bookname','$authorname','$publisher')";
        if(mysqli_query($con, $reg)){
            echo "<script>
                    alert('\"$bookname\" added to our comic book collections.');
                    window.location.href='ques17_1.php';
                </script>";
        }
    }
?>

    <!DOCTYPE html>

    <html>

    <head>
        <title>Comic-Book Registration</title>

        <style>
            .wrap {
                text-align: center;
                margin-top: 100px;
            }
            
            #book_reg_form * {
                margin-top: 10px;
            }
            
            #text_inputs_div {
                display: flex;
                flex-direction: column;
            }
            
            #reg_submit {
                margin-top: 30px;
                margin-right: 10px;
            }
            
            input {
                padding: 5px 10px;
            }
        </style>
    </head>

    <body>
        <div class="wrap">
            <h2>Comic Book Registration Form</h2>
            <p>Add your desired book with appropiate information to our collections.</p>
            <form action="ques17_1.php" method="POST" id="book_reg_form">
                <center>
                    <div style="width: 300px;">
                        <input type="text" name="bookname" id="bookname" placeholder="Book Name here..." required />
                        <input type="text" name="authorname" id="authorname" placeholder="Author Name here..." required />
                        <input type="text" name="publisher" id="publisher" placeholder="Publisher Name here..." required />
                    </div>
                    <input type="submit" name="reg_submit" id="reg_submit" value="submit" />
                    <input type="reset" name="reg_reset" id="reg_reset" value="reset" />
                    <br><br>
                    <p><a href="ques17_2.php">Click here</a> to search books</p>
                </center>
            </form>
        </div>
    </body>

    </html>