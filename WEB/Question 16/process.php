<?php

    include 'includes.php';

    if(isset($_GET['page'])){
        $page= $_GET['page'];
        if($page=='loginreg'){
            if(isset($_GET['acc_process'])){
                $acc_process= $_GET['acc_process'];
                if($acc_process=='login'){
                    $log_uemail= $_POST['log_uemail'];
                    $log_upass= $_POST['log_upass'];
                    $log_passwrd= md5($log_upass);

                    $select = mysqli_query($conn,"SELECT * FROM users WHERE uemail_id='".mysqli_real_escape_string($conn,$log_uemail)."' AND upasswrd='".mysqli_real_escape_string($conn,$log_passwrd)."'");
                    if(mysqli_num_rows($select)==1){
                        $s = mysqli_fetch_array($select);
                        $_SESSION['user_status']= 'user_active';
                        $_SESSION['uid'] = $s['user_id'];
                        $_SESSION['user_name'] = $s['uname'];
                        $_SESSION['user_genre'] = $s['music_genre_pref'];
                        $_SESSION['user_email'] = $s['uemail_id'];

                        header('Location:login_register_page.php?isreg=false&fromprocess=true&login_status=success&msg=Login Successful.');
                    }
                    else{
                        header('Location:login_register_page.php?isreg=false&fromprocess=true&login_status=failed&msg=Username or password are not correct.<br>Please try again.');
                    }

                }
                else if($acc_process=='reg'){
                    $uname = $_POST['reg_uname'];
                    $genre_pref = $_POST['reg_genre'];
                    $uemail = $_POST['reg_uemail'];
                    $upass = $_POST['reg_upass'];
                    if(isset($uname) && isset($genre_pref) && isset($uemail) && isset($upass)){
                        $email_check = mysqli_query($conn,"SELECT uemail_id FROM users WHERE uemail_id='$uemail'");
                        if(mysqli_num_rows($email_check) >= 1){
                            header('Location:login_register_page.php?isreg=true&fromprocess=true&reg_status=failed&msg=Email already registered !!');
                        }
                        else{
                            $upasswrd = md5($upass);
                            $reg_query = "INSERT INTO users VALUES(NULL,'".mysqli_real_escape_string($conn,$uname)."','".mysqli_real_escape_string($conn,$genre_pref)."','".mysqli_real_escape_string($conn,$uemail)."','".$upasswrd."')";

                            if(mysqli_query($conn,$reg_query)){

                                $select = mysqli_query($conn,"SELECT * FROM users WHERE uemail_id='".mysqli_real_escape_string($conn,$uemail)."' AND upasswrd='".mysqli_real_escape_string($conn,$upasswrd)."'");
                                if(mysqli_num_rows($select)==1){
                                    $s = mysqli_fetch_array($select);
                                    $_SESSION['user_status']= 'user_active';
                                    $_SESSION['uid'] = $s['user_id'];
                                    $_SESSION['user_name'] = $s['uname'];
                                    $_SESSION['user_genre'] = $s['music_genre_pref'];
                                    $_SESSION['user_email'] = $s['uemail_id'];

                                    header('Location:login_register_page.php?isreg=true&fromprocess=true&reg_status=success&msg=Registeration Successful.');
                                }
                                else{
                                    header('Location:login_register_page.php?isreg=false&fromprocess=true&login_status=failed&msg=Some issues occurred !! Please try to login to your account !!');
                                }
                            }
                            else {
                                header('Location:login_register_page.php?isreg=true&fromprocess=true&reg_status=failed&msg='.mysqli_error($conn));
                            }
                        }
                    }
                }
            }
        }
    }

    if(isset($_GET['islogin'])){
        $islogin= $_GET['islogin'];
        if($islogin=='false'){
            session_destroy();
            session_start();
            $_SESSION['user_status']="user_inactive";
            $user_status= "user_inactive";
            header('Location: homepage.php');
        }
    }

    if(isset($_POST['query_submit'])){
        $query_uname= $_POST['query_uname'];
        $query_emailid= $_POST['query_emailid'];
        $query_subject= $_POST['query_subject'];
        $query_status= "pending";

        $query_res= mysqli_query($conn, "INSERT INTO sargam_queries VALUES (NULL, '$query_uname', '$query_emailid', '$query_subject', '$query_status')");
        if($query_res){
            echo "<script>
                alert('Your query has been recording successfully. We will updates your queries or solve your issues ASAP. Thank You.');
                window.location.href='about_us.php';
            </script>";
        }
        else{
            echo "<script>alert('Your query submission failed !! Please try later again.');</script>";
        }
    }

    if(isset($_POST['song_id'])){
        $song_id= $_POST['song_id'];
        $song_check_sql= "SELECT * from sargam_playlists WHERE plist_userid=".$_SESSION['uid']." AND plist_songid='".$song_id."'";
        $result = mysqli_query($conn,$song_check_sql);
        $exists = mysqli_num_rows($result);
        if($exists > 0) {
            echo "true";
        }
        else{
            echo "false";
        }
    }

    if(isset($_POST['plist_addsong']) && $_POST['plist_addsong']){
        $plist_uid= $_POST['plist_uid'];
        $plist_song_id= $_POST['plist_song_id'];
        $plist_name_id= $_POST['plist_name_id'];
        $plist_folder_name= $_POST['plist_folder_name'];

        $addplaylist_sql= "INSERT INTO sargam_playlists VALUES (null, '".$plist_name_id."', '".$plist_song_id."', ".$plist_uid.",'".$plist_folder_name."')";
        
        if(mysqli_query($conn,$addplaylist_sql)){
            echo "true"."||plist_song||";
            echo $addplaylist_sql;
        }
        else{
            echo "false"."||plist_song||";
            echo $addplaylist_sql;
        }
    }

    if(isset($_POST['plist_updatesong']) && $_POST['plist_updatesong']){
        $plist_uid= $_POST['plist_uid'];
        $plist_song_id= $_POST['plist_song_id'];
        $plist_name_id= $_POST['plist_name_id'];
        $plist_folder_name= $_POST['plist_folder_name'];

        $addplaylist_sql= "UPDATE sargam_playlists SET plist_nameid='".$plist_name_id."', plist_foldername='".$plist_folder_name."' WHERE plist_userid=".$plist_uid." AND plist_songid='".$plist_song_id."'";
        
        if(mysqli_query($conn,$addplaylist_sql)){
            echo "true"."||plist_song||";
            echo $addplaylist_sql;
        }
        else{
            echo "false"."||plist_song||";
            echo $addplaylist_sql;
        }
    }

    if(isset($_POST['change_puid']) && isset($_POST['change_psongid'])){

        $change_puid= $_POST['change_puid'];
        $change_psongid= $_POST['change_psongid'];

        $song_check_sql= "SELECT * from sargam_playlists WHERE plist_userid=".$change_puid." AND plist_songid='".$change_psongid."' LIMIT 1";
        $result = mysqli_query($conn,$song_check_sql);
        $song_data= mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach($song_data as $row){
            echo "true"."||plist_song||";
            echo $row['plist_nameid']."||plist_song||";
            echo $row['plist_foldername'];
        }
    }

    if(isset($_POST['setradio_ajax'])  && ($_POST['setradio_ajax'])){
        $radio_psong_id= $_POST['radio_psong_id'];
        $uid= isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
        if($uid!=null){
            $song_check_sql= "SELECT * from sargam_playlists WHERE plist_userid=".$uid." AND plist_songid='".$radio_psong_id."' LIMIT 1";
            $radio_res=mysqli_query($conn,$song_check_sql);
            if($radio_res!=0 && $radio_res!=false && $radio_res!='false'){
                $result = mysqli_query($conn,$song_check_sql);
                $song_data= mysqli_fetch_all($result, MYSQLI_ASSOC);
                foreach($song_data as $row){
                    echo "true"."||radio_psong||";
                    echo $row['plist_nameid']."||radio_psong||";
                }
            }
            else{
                echo 'false';
            }
        }
        else{
            echo 'false';
        }
    }

    if(isset($_POST['search_txt'])){
        $search_txt= $_POST['search_txt'];
        
        $search_query = "SELECT * FROM songlists WHERE songname LIKE '%$search_txt%' 
                    OR movie_album_name LIKE '%$search_txt%' 
                    OR artist_name LIKE '%$search_txt%' 
                    OR song_genre LIKE '%$search_txt%' 
                    OR song_language LIKE '%$search_txt%'";

        $search_result = mysqli_query($conn,$search_query);

        if($search_result){
            while($item = mysqli_fetch_array($search_result)){

                $searched_songname= $item['songname'];
                $searched_songgenre= $item['song_genre'];
                $searched_songid= $item['song_id'];
                $searched_song_artistname= $item['artist_name'];

                echo '
                    <div id="search_item_maincontainer" onclick="playSearchedSong(`'.$searched_songid.'`, `'.$searched_songgenre.'`);">
                        <div id="search_item_maindata_div">
                            <div id="search_item_contenttxt">
                                <p id="search_item_songname">'.$searched_songname.'</p>
                                <p id="search_item_songartist">'.$searched_song_artistname.'</p>
                            </div>
                            <i class="fa fa-play-circle" onclick="playSearchedSong(`'.$searched_songid.'`, `'.$searched_songgenre.'`);"></i>
                        </div>
                        <hr>
                    </div>
                ';
            }
        }
    }

    if(isset($_POST['plist_deletesong'])  && ($_POST['plist_deletesong'])){
        
        $uid= isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
        $plist_songid= $_POST['plist_songid'];

        if($uid!=null){
            $song_delete_sql= "DELETE from sargam_playlists WHERE plist_userid=".$uid." AND plist_songid='".$plist_songid."'";
            $songdel_res=mysqli_query($conn,$song_delete_sql);
            if($songdel_res){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        else{
            echo 'false';
        }
    }

?>