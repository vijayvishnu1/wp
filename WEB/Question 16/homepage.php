<?php
    include 'includes.php';
    include 'ReadSongAlbum.php';
?>

<html>
    <head>
        <link rel="icon" type="image/png" href="../images/music_icon.png">
        <title>Home - Sargam</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/home_css.css">
        <link rel="stylesheet" href="../css/font_css.css">
        <script src="../scripts/music_player_script.js"></script>

        <script type="text/javascript">

            function loadSampleAvatar(tag){
                tag.src= "../images/sample_music_icon.png";
            }

            function playSelectedSong(song_id, user_genre_name, filter_content, filter_val){
                if(user_genre_name=="null"){
                    window.open('category.php?filter_content='+filter_content+'&filter_val='+filter_val+'&playHomeSong=true&song_id='+song_id, '_blank');
                }
                else{
                    window.open('category.php?category='+user_genre_name+'&playHomeSong=true&song_id='+song_id, '_blank');
                }
            }
        </script>

    </head>

    <body>

        <div style="background-color: black;">
            <?php $page="home"; include 'navbar.php'; ?>
        </div>

        <div id="home_intro_container">
            <img src="../images/shape_bg.png" style="width: 100%;"/>

            <div id="music_anim">
                <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_8poYy8.json"  background="transparent"  speed="1"  style="width: 400px; height: 400px;"  loop  autoplay></lottie-player>
            </div>
            
            <div id="intro_welcome" style="display: <?php echo $check_user ? 'block' : 'none'; ?>">
                <p id="home_welcome_line1">Welcome, <?php echo $_SESSION['user_name']; ?></p>
                <p id="home_welcome_line2">Its good to see you back.</p>
            </div>

            <div id="intro_logreg_option" style="display: <?php echo $check_user ? 'none' : 'block'; ?>">
                <p id="home_logreg_line1">Welcome, my friend, its good to see you.<br></p>
                <p>Please feel to explore our website and enjoy the musics.<br><br></p>
                <div id="intro_btns">
                    <a href="login_register_page.php?isreg=false">Login</a>
                    <p style="margin-left: 10px;"> / </p>
                    <a href="login_register_page.php?isreg=true" style="margin-left: 10px;">Sign Up</a>
                    <p style="margin-left: 15px;">to Sargam to experience more features.</p>
                </div>
            </div>  
        </div>

        <div>
            <?php
                if(isset($_COOKIE['curr_song_id']) && $_COOKIE['curr_song_id']!=null && $_COOKIE['curr_song_id']!='null' && !empty($_COOKIE['curr_song_id'])){
                    $current_song_id= $_COOKIE['curr_song_id'];
            ?>
                    <div id="home_nowplaying_container">
                        <p id="home_nowplaying_title" style="margin-left: 20px;">Now Playing Song</p>
                        
                        <?php
                            $select_current_song_data= mysqli_query($conn,"SELECT * FROM songlists WHERE song_id='$current_song_id'");
                            $count=0;
                            while($res_all_list = mysqli_fetch_array($select_current_song_data)){
                                $song_genre= $res_all_list['song_genre'];
                                $song_id=$res_all_list['song_id'];
                                $songname = $res_all_list['songname'];
                                $song_fullpath = $res_all_list['song_fullpath'];
                                $song_artist_name= $res_all_list['artist_name'];
                                $image_src= getAlbumFromSong($song_fullpath);
                                echo '
                                <div class="home_nowplaying_div" onclick="playSelectedSong(`'.$song_id.'`,`'.$song_genre.'`,null, null)" title="Play Song">
                                    <img id="home_nowplaying_img" src="'.$image_src.'" alt="Album '.$count.'" onerror="loadSampleAvatar(this);" />
                                    <p>'.$songname.'</p>
                                    <small>Song by - '.$song_artist_name.'</small>
                                </div>
                                ';
                            }
                        ?>
                    </div>

            <?php } ?>
        </div>

        <div>
            <?php 
                if($user_status=='user_active'){ $user_genre_name= $_SESSION['user_genre'];?>

                    <div id="home_category_container" style="margin-top: 40px;">

                        <div id="home_your_category_title" style="display: flex; flex-direction: row;  margin-top: 20px;">
                            <p>Some Songs from your preferred genre</p>
                            <a href="category.php?category=<?php echo $user_genre_name; ?>" target="_blank">See All</a>
                        </div>

                        <div id="home_your_category_div">

                            <?php

                                $genre_songs_sql= "SELECT * FROM songlists WHERE song_genre='".$user_genre_name."' order by RAND() LIMIT 6";
                                $genre_songs_result= mysqli_query($conn,$genre_songs_sql);
                                $count=0;
                                while($res_all_list = mysqli_fetch_array($genre_songs_result)){
                                    $count+=1;
                                    $song_id=$res_all_list['song_id'];
                                    $songname = $res_all_list['songname'];
                                    $song_fullpath = $res_all_list['song_fullpath'];
                                    $song_genre= $res_all_list['song_genre'];
                                    $song_artist_name= $res_all_list['artist_name'];
                                    $image_src= getAlbumFromSong($song_fullpath);
                                    echo '
                                    <div class="home_your_category_item" onclick="playSelectedSong(`'.$song_id.'`,`'.$user_genre_name.'`,null, null)" title="Play Song">
                                        <img src="'.$image_src.'" alt="Album '.$count.'" class="cat_album_img" onerror="loadSampleAvatar(this);" />
                                        <p>'.$songname.'</p>
                                        <small>Genre - '.$song_genre.'</small>
                                        <div class="home_your_category_full_cover">
                                            <i class="fa fa-play-circle"></i>
                                        </div>
                                    </div>
                                    ';
                                }
                                echo '
                                <div class="home_your_category_item_last">
                                    <img src="../images/sample_music_icon.png" alt="Album 1" class="cat_album_img" style="opacity:0.7" />
                                    <small style="text-align:center;"><b>Click See All to view all</b></small>
                                </div>
                                    ';
                            ?>
                        </div>
                    </div> 

            <?php } ?>
        </div>

        <div id="home_category_container" style="margin-top: 40px;">

            <div id="home_your_category_title" style="display: flex; flex-direction: row;  margin-top: 20px;">
                <p>Famous Arijit Singh Songs</p>
                <a href="category.php?filter_content=artist_name&filter_val=Arijit Singh" target="_blank">See All</a>
            </div>

            <div id="home_your_category_div">

                <?php

                    $artist_name= "Arijit Singh";
                    $select_eng_songs= mysqli_query($conn,"SELECT * FROM songlists WHERE artist_name LIKE '%$artist_name%' order by RAND() LIMIT 6");
                    $count=0;
                    while($res_all_list = mysqli_fetch_array($select_eng_songs)){
                        $count+=1;
                        $song_id=$res_all_list['song_id'];
						$songname = $res_all_list['songname'];
						$song_fullpath = $res_all_list['song_fullpath'];
                        $song_artist_name= $res_all_list['artist_name'];
                        $image_src= getAlbumFromSong($song_fullpath);
						echo '
						<div class="home_your_category_item" onclick="playSelectedSong(`'.$song_id.'`,`null`,`artist_name`,`Arijit Singh`)" title="Play Song">
                            <img src="'.$image_src.'" alt="Album '.$count.'" class="cat_album_img" onerror="loadSampleAvatar(this);" />
                            <p>'.$songname.'</p>
                            <small>Song by - '.$song_artist_name.'</small>
                            <div class="home_your_category_full_cover">
                                <i class="fa fa-play-circle"></i>
                            </div>
                        </div>
						';
                    }
                    echo '
                    <div class="home_your_category_item_last">
                        <img src="../images/sample_music_icon.png" alt="Album 1" class="cat_album_img" style="opacity:0.7" />
                        <small style="text-align:center;"><b>Click See All to view all</b></small>
                    </div>
						';
                ?>
            </div>
        </div>

        <div id="home_category_container" style="margin-top: 40px;">

            <div id="home_your_category_title" style="display: flex; flex-direction: row;  margin-top: 20px;">
                <p>Trending Justin Bieber Albums</p>
                <a href="category.php?filter_content=artist_name&filter_val=Justin Bieber" target="_blank">See All</a>
            </div>


            <div id="home_your_category_div">

                <?php

                    $artist_name= "Justin Bieber";
                    $select_eng_songs= mysqli_query($conn,"SELECT * FROM songlists WHERE artist_name LIKE '%$artist_name%' order by RAND() LIMIT 6");
                    $count=0;
                    while($res_all_list = mysqli_fetch_array($select_eng_songs)){
                        $count+=1;
                        $song_id=$res_all_list['song_id'];
						$songname = $res_all_list['songname'];
						$song_fullpath = $res_all_list['song_fullpath'];
                        $song_artist_name= $res_all_list['artist_name'];
                        $image_src= getAlbumFromSong($song_fullpath);
						echo '
						<div class="home_your_category_item" onclick="playSelectedSong(`'.$song_id.'`,`null`,`artist_name`,`Justin Bieber`)" title="Play Song">
                            <img src="'.$image_src.'" alt="Album '.$count.'" class="cat_album_img" onerror="loadSampleAvatar(this);" />
                            <p>'.$songname.'</p>
                            <small>Song by - '.$song_artist_name.'</small>
                            <div class="home_your_category_full_cover">
                                <i class="fa fa-play-circle"></i>
                            </div>
                        </div>
						';
                    }
                    echo '
                    <div class="home_your_category_item_last">
                        <img src="../images/sample_music_icon.png" alt="Album 1" class="cat_album_img" style="opacity:0.7" />
                        <small style="text-align:center;"><b>Click See All to view all</b></small>
                    </div>
						';
                ?>
            </div>
        </div>

        <div id="home_category_container" style="margin-top: 40px;">

            <div id="home_your_category_title" style="display: flex; flex-direction: row;  margin-top: 20px;">
                <p>Some Viewed Malayalam Songs</p>
                <a href="category.php?filter_content=language&filter_val=Malayalam" target="_blank">See All</a>
            </div>

            <div id="home_your_category_div">

                <?php

                    $select_eng_songs= mysqli_query($conn,"SELECT * from songlists where song_language='Malayalam' order by RAND() LIMIT 6");
                    $count=0;
                    while($res_all_list = mysqli_fetch_array($select_eng_songs)){
                        $count+=1;
                        $song_id=$res_all_list['song_id'];
						$songname = $res_all_list['songname'];
						$song_fullpath = $res_all_list['song_fullpath'];
                        $song_artist_name= $res_all_list['artist_name'];
                        $image_src= getAlbumFromSong($song_fullpath);

						echo '
						<div class="home_your_category_item" onclick="playSelectedSong(`'.$song_id.'`,`null`,`language`,`Malayalam`)" title="Play Song">
                            <img src="'.$image_src.'" alt="Album '.$count.'" class="cat_album_img" onerror="loadSampleAvatar(this);" />
                            <p>'.$songname.'</p>
                            <small>Song by - '.$song_artist_name.'</small>
                            <div class="home_your_category_full_cover">
                                <i class="fa fa-play-circle"></i>
                            </div>
                        </div>
						';
                    }
                    echo '
                    <div class="home_your_category_item_last">
                        <img src="../images/sample_music_icon.png" alt="Album 1" class="cat_album_img" style="opacity:0.7" />
                        <small style="text-align:center;"><b>Click See All to view all</b></small>
                    </div>
						';
                ?>
            </div>
        </div>

        <div id="home_category_container" style="margin-top: 40px;">

            <div id="home_your_category_title" style="display: flex; flex-direction: row;  margin-top: 20px;">
                <p>Some English Songs</p>
                <a href="category.php?filter_content=language&filter_val=English" target="_blank">See All</a>
            </div>

            <div id="home_your_category_div">

                <?php

                    $not_art='Justin Bieber';
                    $select_eng_songs= mysqli_query($conn,"SELECT * from songlists where song_language='English' AND artist_name NOT LIKE '%$not_art%' order by RAND() LIMIT 6");
                    $count=0;
                    while($res_all_list = mysqli_fetch_array($select_eng_songs)){
                        $count+=1;
                        $song_id=$res_all_list['song_id'];
						$songname = $res_all_list['songname'];
						$song_fullpath = $res_all_list['song_fullpath'];
                        $song_artist_name= $res_all_list['artist_name'];
                        $image_src= getAlbumFromSong($song_fullpath);
						echo '
						<div class="home_your_category_item" onclick="playSelectedSong(`'.$song_id.'`,`null`,`language`,`English`)" title="Play Song">
                            <img src="'.$image_src.'" alt="Album '.$song_artist_name.'" class="cat_album_img" onerror="loadSampleAvatar(this);" />
                            <p>'.$songname.'</p>
                            <small>Song by - '.$song_artist_name.'</small>
                            <div class="home_your_category_full_cover">
                                <i class="fa fa-play-circle"></i>
                            </div>
                        </div>
						';
                    }
                    echo '
                    <div class="home_your_category_item_last">
                        <img src="../images/sample_music_icon.png" alt="Album 1" class="cat_album_img" style="opacity:0.7" />
                        <small style="text-align:center;"><b>Click See All to view all</b></small>
                    </div>
						';
                ?>
            </div>
        </div>

        <div id="home_category_container" style="margin-top: 40px;">

            <div id="home_your_category_title" style="display: flex; flex-direction: row;  margin-top: 20px;">
                <p>Famous Hindi Songs</p>
                <a href="category.php?filter_content=language&filter_val=Hindi" target="_blank">See All</a>
            </div>

            <div id="home_your_category_div">

                <?php
                    $select_eng_songs= mysqli_query($conn,"SELECT * from songlists where song_language='Hindi' order by RAND() LIMIT 6");
                    $count=0;
                    while($res_all_list = mysqli_fetch_array($select_eng_songs)){
                        $count+=1;
                        $song_id=$res_all_list['song_id'];
						$songname = $res_all_list['songname'];
						$song_fullpath = $res_all_list['song_fullpath'];
                        $song_artist_name= $res_all_list['artist_name'];
                        $image_src= getAlbumFromSong($song_fullpath);
						echo '
						<div class="home_your_category_item" onclick="playSelectedSong(`'.$song_id.'`,`null`,`language`,`Hindi`)" title="Play Song">
                            <img src="'.$image_src.'" alt="Album '.$count.'" class="cat_album_img" onerror="loadSampleAvatar(this);" />
                            <p>'.$songname.'</p>
                            <small>Song by - '.$song_artist_name.'</small>
                            <div class="home_your_category_full_cover">
                                <i class="fa fa-play-circle"></i>
                            </div>
                        </div>
						';
                    }
                    echo '
                    <div class="home_your_category_item_last">
                        <img src="../images/sample_music_icon.png" alt="Album 1" class="cat_album_img" style="opacity:0.7" />
                        <small style="text-align:center;"><b>Click See All to view all</b></small>
                    </div>
						';
                ?>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </body>
</html>