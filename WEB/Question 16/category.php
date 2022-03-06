<?php

    include 'includes.php';
    include 'ReadSongAlbum.php';
    
    $category_item= isset($_GET['category']) ? $_GET['category'] : (isset($_GET['filter_val']) ? $_GET['filter_val'] : ((isset($_GET['frompage']) && isset($_GET['songname_id'])) ? "playlist_name_id" : "romantic"));

    $cat_title_name= null;
    $cat_des= null;

    if($category_item=='classic'){
        $cat_src= "../images/about_music_des_1_img.jpg";
        $cat_title_name= "Classic / Melody Songs";
        $cat_des= "Melody, the aesthetic product of a given succession of pitches in musical time, implying rhythmically ordered movement from pitch to pitch. Melody in Western music by the late 19th century was considered to be the surface of a group of harmonies.";
    }
    else if($category_item=="rock"){
        $cat_src= "../images/about_music_des_4_img.jpg";
        $cat_title_name= "Rock / Jazz Songs";
        $cat_des= "The rock / jazz that all kind of people likes that has the greatest evolution in the music world and the songs that influenced many poeple are also added for the users. A type of pop music that developed out of rock'n'roll in the 1960s and 1970s.";
    }
    else if($category_item=='romantic'){
        $cat_src= "../images/about_music_des_2_img.jpg";
        $cat_title_name= "Romantic Songs";
        $cat_des= "A love song is a song about romantic love, falling in love, heartbreak after a breakup, and the feelings that these experiences bring.";
    }
    else if($category_item=='Malayalam'){
        $cat_src= "../images/about_music_des_2_img.jpg";
        $cat_title_name= "Malayalam Songs";
        $cat_des= "Here the malayalam songs are offered to you according to people feelings and likes. Malayalam songs with traditional, classic, romantic and much more have been added according to user's needs.";
    }
    else if($category_item=='Hindi'){
        $cat_src= "../images/hindi_songs.jpg";
        $cat_title_name= "Classic Hindi - Bollywood Songs";
        $cat_des= "Here the hindi songs are offered to you according to people feelings and likes. Hindi songs with rock, classic, romantic and much more have been added according to user's needs.";
    }
    else if($category_item=='English'){
        $cat_src= "../images/english_songs.jpg";
        $cat_title_name= "Latest Famous English Songs";
        $cat_des= "Here the english songs are offered to you according to people feelings and likes. English songs with traditional, classic, romantic and much more have been added according to user's needs.";
    }
    else if($category_item=='Arijit Singh'){
        $cat_src= "../images/arijit_songs.jpg";
        $cat_title_name= "Famous Romantic Songs By Arijit Singh";
        $cat_des= "Here are some Arijit Singh songs for Arijit Fans. Arijit Singh is an Indian singer and music composer. He sings predominantly in Hindi and Bengali, but has also performed in various other Indian languages. He is the recipient of a National Award and a record six Filmfare Awards.";
    }
    else if($category_item=='Justin Bieber'){
        $cat_src= "../images/justin_bieber_songs.jpg";
        $cat_title_name= "Classic Justin Bieber Songs";
        $cat_des= "Feel free to play classic Justin Bieber Songs. Justin Drew Bieber is a Canadian singer. He was discovered by American record executive Scooter Braun and signed with RBMG Records in 2008, gaining recognition with the release of his debut seven-track EP My World and soon establishing himself as a teen idol.";
    }
    else if($category_item=='playlist_name_id'){
        $cat_src= "../images/log_reg_bg_5.png";
        $cat_title_name= "Playlist ".$_GET['plist_foldername']." Songs";
        $cat_des= "";
    }
?>

<html>
    <head>
        <link rel="icon" type="image/png" href="../images/music_icon.png">
        <title>Category - Sargam</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?501"></script>
        <link rel="stylesheet" href="../css/category_css.css">
        <link rel="stylesheet" href="../css/footer_css.css">
        <link rel="stylesheet" href="../css/music_player_css.css">
        <link rel="stylesheet" href="../css/nav_css.css">
        <link rel="stylesheet" href="../css/font_css.css">

        <script>

            function loadSampleAvatar(tag){
                tag.src= "../images/sample_music_icon.png";
            }

            function playlistFromMusicplayer(){
                var cur_songId= getCookie('curr_song_id');
                checkSongInPlayList(cur_songId).then(function(data) {
                    if(data=='true'){
                        ActionToPlayList(1, <?php echo isset($_SESSION['uid']) ? $_SESSION['uid'] : null ?>);
                    }
                    else{
                        ActionToPlayList(0, <?php echo isset($_SESSION['uid']) ? $_SESSION['uid'] : null ?>);
                    }
                }).catch(function(err) {});
            }
        </script>
    
    </head>

    <body style="background:none; color: white">

        <div id="playlist_background">
            <div id="thispage_playlist_dialog_box">
                <div id="playlist_dialog_title_close">
                    <p id="playlist_dialog_title_txt">Add to Playlist</p>
                    <i class="fa fa-times-circle" id="playlist_dialog_close_btn" onclick="closeAddToPlaylistDialog(0, null);"></i>
                </div>
                <hr style="margin: 10px 0px; height: 0.5px; width:200px; background-color: black;">
                <div id="playlist_optns_div">
                <div id="playlist_optns"><input type="radio" value="default" id="default" name="playlist_folder" /><label for="default">Default Playlist</label></div>
                    <?php
                        if(isset($_SESSION['uid'])){
                            $uid= $_SESSION['uid'];
                            $plist_sql= mysqli_query($conn,"SELECT * FROM sargam_playlists WHERE plist_userid=".$uid." AND plist_nameid!='default' GROUP BY plist_nameid");
                            $plist_result= mysqli_fetch_all($plist_sql, MYSQLI_ASSOC);
                    
                            foreach($plist_result as $each_plist_folder){
                                $plist_nameid= $each_plist_folder['plist_nameid'];
                                $plist_foldername= $each_plist_folder['plist_foldername'];
                    
                                echo '<div id="playlist_optns"><input type="radio" value="'.$plist_nameid.'" id="'.$plist_nameid.'" name="playlist_folder" /><label for="'.$plist_nameid.'">'.$plist_foldername.'</label></div>';
                            }
                        }
                    ?>
                </div>
                <hr style="margin: 10px 0px; height: 0.5px; width:200px; background-color: black;">
                <div id="create_playlist_folder_btn" onclick="createNewPlaylistFolder();">&#x2795; Create Playlist Folder</div>
                <div id="create_playlist_form">
                    <input type="text" id="add_playlist_foldername" name="add_playlist_foldername" placeholder="Enter playlist name" minlength="3" maxlength="20" pattern="^[-a-zA-Z0-9-()]+(\s+[-a-zA-Z0-9-()]+)*$" required oninvalid="this.setCustomValidity('Invalid Name !! No characters and spaces at start and end !!')" oninput="this.setCustomValidity('')"/>
                    <button id="foldername_submit">CREATE PLAYLIST</button>
                </div>
            </div>
        </div>
        
        <div id="category_navbar">
            <?php $page="category"; include 'navbar.php'; ?>
        </div>

        <div id="thispage_maincontent_div">

            <div id="thispage_main_container">

                <div id="list_options_left_side">
                </div>

                <div id="list_musics_right_side">

                    <div id="thispage_music_title_list_div">

                        <div id="thispage_intro_div">
                            <img src="<?php echo $cat_src; ?>">
                            <div id="intro_title_div">
                                <p><?php echo $cat_title_name; ?></p>
                                <small><?php echo $cat_des; ?></small>
                            </div>
                        </div>

                        <hr id="title_list_divider">

                        <div id="thispage_musics_div">

                            <div id="listitems_div">
                                <?php

                                    if(isset($_GET['filter_content'])){
                                        $filter_content= isset($_GET['filter_val']) ? $_GET['filter_content'] : "language";
                                        $filter_val= isset($_GET['filter_val']) ? $_GET['filter_val'] : "Hindi";
                                        if($filter_content=='language'){
                                            $select_eng_songs= mysqli_query($conn,"SELECT * FROM songlists WHERE song_language='".$filter_val."' order by RAND()");
                                        }
                                        else{
                                            $select_eng_songs= mysqli_query($conn,"SELECT * FROM songlists WHERE artist_name LIKE '%".$filter_val."%' order by RAND()");
                                        }
                                    }
                                    else if(isset($_GET['frompage']) && $_GET['frompage']=='playlists'){
                                        $playlist_name= $_GET['songname_id'];
                                        $select_eng_songs= mysqli_query($conn,"SELECT * from songlists WHERE song_id IN (SELECT plist_songid FROM sargam_playlists WHERE plist_userid=".$_SESSION['uid']." AND plist_nameid='".$playlist_name."')");
                                    }
                                    else{
                                        $select_eng_songs= mysqli_query($conn,"SELECT * FROM songlists WHERE song_genre='".$category_item."' order by RAND()");
                                    }
                                    $count=0;

                                    $all_songs= mysqli_fetch_all($select_eng_songs, MYSQLI_ASSOC);

                                    $song_id_arr= array();
                                    foreach($all_songs as $list)
                                        $song_id_arr[]= $list['song_id'];
                                    setcookie('all_songid_arr',json_encode($song_id_arr));

                                    $checkFromPlistPage= isset($_GET['frompage']) ? (($_GET['frompage']=='playlists') ? true : false ) : false;
                                    $setDisplayPlistSongDeleteDiv = $checkFromPlistPage ? "block" : "none";
                                    
                                    if(count($all_songs) <= 0){
                                        header('Location: homepage.php');
                                    }

                                    foreach($all_songs as $res_all_list){
                                        $count++;

                                        $sr_no= $res_all_list['sr_no'];
                                        $song_id=$res_all_list['song_id'];
                                        $song_genre= $res_all_list['song_genre'];
                                        $songartist_name= $res_all_list['artist_name'];
                                        $movie_album_name= $res_all_list['movie_album_name'];

                                        $songname = $res_all_list['songname'];
                                        $song_language= $res_all_list['song_language'];
                                        $song_fullpath = $res_all_list['song_fullpath'];
                                        $song_artist_name= $res_all_list['artist_name'];
                                        $image_src= getAlbumFromSong($song_fullpath);
                                        $song_time_data= getSongTime($song_fullpath);
                                        $str_arr = explode (":", $song_time_data); 
                                        $song_time = $str_arr[0]." minutes ".$str_arr[1].' sec';

                                        echo '
                                    
                                        <div id="list_item_main_container">
                                            <div id="list_each_song_div" onclick="loadTrack(`main_song_container`,`'.$song_id.'`,`'.$song_language.'`,
                                            `'.$song_genre.'`,`'.$songartist_name.'`,`'.$movie_album_name.'`,`'.$songname.'`,`'.$song_fullpath.'`,
                                            '.(int)$str_arr[0].','.(int)$str_arr[1].',`'.$checkFromPlistPage.'`,`'.$image_src.'`);" title="Play Song">

                                                <p id="cat_song_count">'.$count.'</p>
                                                <img id="cat_song_image" alt="Album '.$songname.'" src="'.$image_src.'" onerror="loadSampleAvatar(this);"/>
                                                <div id="cat_song_details">
                                                    <p id="cat_song_songname">'.$songname.'</p>
                                                    <p id="cat_song_langtime">'.$song_language.'<br>'.$song_time.'</p>
                                                </div>
                                            </div>

                                            <div id="list_psong_delete_div" class="'.$song_id.'" style="display : '.$setDisplayPlistSongDeleteDiv.'"><i class="fa fa-trash abt_plist_songdelete_btn" onclick="removeSongFromPlaylist(`'.$song_id.'`,`'.$songname.'`);" title="Delete song from playlist"></i></div>
                                        </div>
                                        <hr>
                                        ';
                                        $index++;
                                    }
                                ?>
                            </div>

                            <div id="list_songs_about">
                                <h2 style="text-decoration: underline;">&nbsp;About&nbsp;</h2>
                                <div id="abt_song_details">
                                    
                                    <img id="abt_song_img" onerror="loadSampleAvatar(this);"/>
                                    
                                    <button id="abt_toggle_player_btn" onclick="toggleMusicPlayerVisibility();">Open / Close Music Player</button>

                                    <div class="abt_songeachdata_class">
                                        <p id="abt_sdata_songname"></p>
                                        <p id="abt_sdata_artistname"></p>
                                        <p id="abt_sdata_language"></p>
                                        <p id="abt_sdata_genre"></p>
                                        <p id="abt_sdata_albumname"></p>
                                        <div>
                                            <div id="abt_plistadd_login_div">
                                                <button id="abt_playlists_btn" onclick="ActionToPlayList(0, <?php echo isset($_SESSION['uid']) ? $_SESSION['uid'] : null ?>);">Add to playlist</button>
                                                <p id="abt_playlist_nouser_txt"><a href="login_register_page.php?isreg=false">Login / Register</a> to your account first to add songs to playlists</p>
                                            </div>
                    
                                            <div id="abt_songadded_div">
                                                <i class="fa fa-check-circle fa-2x playlist_added_icon"></i>
                                                <p style="margin-left: 10px;"><b>Saved to playlist</b></p>
                                                <p id="abt_songchange_playlist_btn" onclick="ActionToPlayList(1, <?php echo isset($_SESSION['uid']) ? $_SESSION['uid'] : null ?>);">change</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <div id="thispage_footerdiv">
                <?php include 'footer.php'; ?>
            </div>
        </div>

        <div id="music_player_bar_div">
            
            <div id="web_music_player_div">

                <i class="fa fa-times-circle" id="music_player_close_btn" onclick="closeMusicPlayer()" title="Close Music Player"></i>

                <div id="pmusic_song_data_div">

                    <div id="pmusic_song_texts_data">
                        <div id="song_text_contents">
                            <p id="pmusic_song_titlename"></p>
                            <small id="pmusic_song_artist_name"></small>
                        </div>
                    </div>

                    <div id="pmusic_audio_othr_opts_controller">
                        <i id="pmusic_audio_volume_btn" class="fa fa-volume-up" onclick="toggle_volume();" title="Mute / Unmute Volume"></i>
                        <input id="pmusic_volume_rangebar" type="range" min="0" max="100" value="100" onchange="volume_change();"/>
                        <p id="pmusic_display_volume">100</p><p>%</p>
                    </div>
                </div>

                <div id="pmusic_player_controller">
                    <div id="pmusic_song_action_btns">
                        <i class="fa fa-random" id="pmusic_song_random_play_btn" onclick="randomSong();" title="Shuffle Play"></i>
                        <i class="fa fa-step-backward " id="pmusic_song_prev_btn" onclick="playActionSong('prev');" title="Play Previous Song"></i>
                        <i class="fa fa-fast-backward" onclick="fast_backward_song();" title="Fast Backward"></i>
                        <i class="fa fa-play-circle" id="pmusic_song_play_btn" onclick="justPlay();" title="Play / Pause"></i>
                        <i class="fa fa-fast-forward" onclick="fast_forward_song();" title="Fast Forward"></i>
                        <!-- <i class="fa fa-spinner fa-spin"></i> -->
                        <i class="fa fa-step-forward" id="pmusic_song_next_btn" onclick="playActionSong('next');" title="Play Next Song"></i>
                        <svg id="pmusic_add_playlist_btn" onclick="playlistFromMusicplayer();" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g fill="#000000"><path d="M155.11354,23.03411l-37.1211,7.95052c-5.28613,1.12947 -9.05911,5.80258 -9.05911,11.20911v65.09349c0,11.17427 -34.4,1.83252 -34.4,33.05625c0,5.73333 3.45604,20.17864 19.8763,20.17864c20.18133,0.00573 25.99036,-15.33398 25.99036,-32.36198v-67.7474l34.85911,-7.64817c3.0788,-0.67653 5.27422,-3.40291 5.27422,-6.56198v-18.7901c0,-2.8552 -2.63339,-4.98039 -5.41979,-4.37839zM22.93333,34.4c-3.1648,0 -5.73333,2.56853 -5.73333,5.73333c0,3.1648 2.56853,5.73333 5.73333,5.73333h68.8c3.1648,0 5.73333,-2.56853 5.73333,-5.73333c0,-3.1648 -2.56853,-5.73333 -5.73333,-5.73333zM22.93333,63.06667c-3.1648,0 -5.73333,2.56853 -5.73333,5.73333c0,3.1648 2.56853,5.73333 5.73333,5.73333h68.8c3.1648,0 5.73333,-2.56853 5.73333,-5.73333c0,-3.1648 -2.56853,-5.73333 -5.73333,-5.73333zM22.93333,91.73333c-3.1648,0 -5.73333,2.56853 -5.73333,5.73333c0,3.1648 2.56853,5.73333 5.73333,5.73333h68.8c3.1648,0 5.73333,-2.56853 5.73333,-5.73333c0,-3.1648 -2.56853,-5.73333 -5.73333,-5.73333zM22.93333,120.4c-3.1648,0 -5.73333,2.56853 -5.73333,5.73333c0,3.1648 2.56853,5.73333 5.73333,5.73333h40.90599c0.83133,-4.51213 2.30668,-8.30187 4.22161,-11.46667z"></path></g></g></svg>
                    </div>
                    <div id="songrange_div">
                        <small id="pmusic_song_play_time">00:00</small>
                        <input id="songtrackbar" type="range" min="0" max="100" value="0" onchange="song_changeduration();"/>
                        <small id="pmusic_song_full_time">00:00</small>
                    </div>
                </div>
                    
            </div>
        </div>

        <script src="../scripts/music_player_script.js"></script>

        <?php

            $playHomeSong= isset($_GET['playHomeSong']) ? $_GET['playHomeSong'] : 'false';
            if($playHomeSong=='true'){

                $home_songID= isset($_GET['song_id']) ? $_GET['song_id'] : 'no_ID';
                if($home_songID!='no_ID'){

                    $song_sql= mysqli_query($conn,"SELECT * FROM songlists WHERE song_id='".$home_songID."'");
                    $index= 0;
        
                    $song_data= mysqli_fetch_all($song_sql, MYSQLI_ASSOC);
        
                    $sr_no= $song_data[0]['sr_no'];
                    $song_id= $song_data[0]['song_id'];
                    $song_genre= $song_data[0]['song_genre'];
                    $songartist_name= $song_data[0]['artist_name'];
                    $movie_album_name= $song_data[0]['movie_album_name'];
        
                    $songname = $song_data[0]['songname'];
                    $song_language= $song_data[0]['song_language'];
                    $song_fullpath = $song_data[0]['song_fullpath'];
                    $song_artist_name= $song_data[0]['artist_name'];
                    $image_src= getAlbumFromSong($song_fullpath);
                    $song_time_data= getSongTime($song_fullpath);
                    $str_arr = explode (":", $song_time_data); 
                    $song_time = $str_arr[0]." minutes ".$str_arr[1].' sec';
        
                    echo '<script>loadTrack(`main_song_container`,`'.$song_id.'`,`'.$song_language.'`,
                    `'.$song_genre.'`,`'.$songartist_name.'`,`'.$movie_album_name.'`,`'.$songname.'`,`'.$song_fullpath.'`,
                    '.(int)$str_arr[0].','.(int)$str_arr[1].',`false`,`'.$image_src.'`);</script>';
                }
            }
        ?>

    </body>

</html>
