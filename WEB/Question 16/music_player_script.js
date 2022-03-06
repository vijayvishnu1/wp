
var song_title_name= document.querySelector('#pmusic_song_titlename');
var song_artist_name= document.querySelector('#pmusic_song_artist_name');
var previous= document.querySelector('#pmusic_song_prev_btn');
var next= document.querySelector('#pmusic_song_next_btn');
var random_song= document.querySelector('#pmusic_song_random_play_btn');
var play_btn= document.querySelector('#pmusic_song_play_btn');
var repeat_btn= document.querySelector('#pmusic_song_repeat_btn');
var song_timebar= document.querySelector('#songtrackbar');
var song_volume_btn= document.querySelector('#pmusic_audio_volume_btn');
var song_volume_bar= document.querySelector('#pmusic_volume_rangebar');
var add_playlist= document.querySelector('#pmusic_add_playlist_btn');

var song_fav_btn= document.querySelector('#pmusic_song_fav_btn');
var song_play_time= document.querySelector('#pmusic_song_play_time');
var song_full_time= document.querySelector('#pmusic_song_full_time');
var web_music_player_div= document.querySelector('#web_music_player_div');

var abt_song_details= document.querySelector('#abt_song_details');
var abt_song_img= document.querySelector('#abt_song_img');
var abt_sdata_songname= document.querySelector('#abt_sdata_songname');
var abt_sdata_artistname= document.querySelector('#abt_sdata_artistname');
var abt_sdata_language= document.querySelector('#abt_sdata_language');
var abt_sdata_genre= document.querySelector('#abt_sdata_genre');
var abt_sdata_albumname= document.querySelector('#abt_sdata_albumname');
var abt_playlists_btn= document.querySelector('#abt_playlists_btn');
var abt_songadded_div= document.querySelector('#abt_songadded_div');
var playlist_dialog_close_btn= document.querySelector('#playlist_dialog_close_btn');
var playlist_background= document.querySelector('#playlist_background');
var create_playlist_form= document.querySelector('#create_playlist_form');

var add_playlist_foldername=document.querySelector('#add_playlist_foldername');
var create_playlist_folder_btn= document.querySelector('#create_playlist_folder_btn');
var abt_playlist_nouser_txt= document.querySelector('#abt_playlist_nouser_txt');

var add_playlist_foldername= document.querySelector('#add_playlist_foldername');
var playlist_folder= document.getElementsByClassName('playlist_folder');

var abt_plistadd_login_div= document.querySelector('#abt_plistadd_login_div');
var abt_plist_songdelete_btn= document.querySelector('.abt_plist_songdelete_btn');
var list_psong_delete_div= document.querySelector('#list_psong_delete_div');

abt_playlists_btn.style.display= "none";
abt_songadded_div.style.display= "none";

var timer;
var toggle_music_player_open=0;
var playing_song= false;
var track_song= document.createElement('audio');
var volume_counter=1;
var curr_volume_val=100;
var random_song_counter=false;

function playmusic(tag){
    if(tag.classList.contains("fa-play-circle")){
        tag.classList.remove("fa-play-circle");
        tag.classList.add("fa-pause");
    }
    else{
        tag.classList.remove("fa-pause");
        tag.classList.add("fa-play-circle");
    }
}

function closeMusicPlayer(){
    var web_music_player_div= document.getElementById("web_music_player_div");
    web_music_player_div.style.transform= "translateY(" + (100) + "px)";
    web_music_player_div.style.opacity = "0";
    web_music_player_div.style.transition = "ease-in-out 0.5s"
    toggle_music_player_open=0;
}

function toggleMusicPlayerVisibility(){
     if(toggle_music_player_open==1){
        toggle_music_player_open=0;
        closeMusicPlayer();
     }
     else{
        var web_music_player_div= document.getElementById("web_music_player_div");
        web_music_player_div.style.transform= "translateY(" + (0) + "px)";
        web_music_player_div.style.opacity = "1";
        web_music_player_div.style.transition = "ease-in-out 0.5s";
        toggle_music_player_open=1;
     }
}

function createNewPlaylistFolder(){
    create_playlist_form.style.display= "flex";
    add_playlist_foldername.focus();
}

function ActionToPlayList(whichAction, uid){
    closeAddToPlaylistDialog(1, uid);
    if(uid!=null){
        $(":radio[name='playlist_folder']").change(function() {
            console.log(this.value);
            var label_val= document.querySelector('label[for="'+this.value+'"]');
            if(whichAction==0){
                addSongToPlayList(uid, this.value, label_val.textContent);
            }
            else if(whichAction==1){
                updateSongToPlayList(uid, this.value, label_val.textContent);
            }
        });

        document.getElementById("foldername_submit").addEventListener('click',function (){
            
            plist_foldername= String(add_playlist_foldername.value);
            plist_nameid= plist_foldername.toLowerCase().replace(/ /g, "_");

            if(whichAction==0){
                addSongToPlayList(uid, plist_nameid, plist_foldername);
            }
            else if(whichAction==1){
                updateSongToPlayList(uid, plist_nameid, plist_foldername);
            }
        });
    }
}

function closeAddToPlaylistDialog(checkDisplay, uid){
    
    if(checkDisplay==0){
        playlist_background.style.display= "none";
        create_playlist_form.style.display="none";
        add_playlist_foldername.value="";
    }
    else{
        if(uid=='null' || uid==null || uid=='undefined' || uid==undefined || uid==''){
            abt_playlist_nouser_txt.style.display= "block";
        }
        else{
            playlist_background.style.display= "block";
        }
    }
}

function addSongToPlayList(uid, plist_nameid, plist_foldername){

    var curr_song_id= getCookie('curr_song_id');
    playlist_background.style.display= "none";
    create_playlist_form.style.display="none";

    $.ajax({
        url: 'process.php',
        method:"POST",
        data: {
            plist_addsong: true,
            plist_uid:uid,
            plist_song_id:curr_song_id,
            plist_name_id: plist_nameid,
            plist_folder_name:plist_foldername
        },
        success: function (resp) {
            console.log("From addSongToPlayList : "+resp);
            var myDataArray= resp.split("||plist_song||");
            if(myDataArray[0]=='true'){
                abt_playlists_btn.style.display="none";
                abt_playlist_nouser_txt.style.display="none";
                abt_songadded_div.style.display="flex";
                if ($('input[name=playlist_folder]:checked').length > 0) {
                    var radio = document.querySelector('input[type=radio][name=playlist_folder]:checked');
                    radio.checked = false;
                }
                add_playlist_foldername.value="";
            }
            else{
                abt_playlists_btn.style.display="block";
                abt_playlist_nouser_txt.style.display="none";
                abt_songadded_div.style.display="none";
            }
        },
        error: function () {
            reject(err)
        }
    });
    
}

function updateSongToPlayList(uid, plist_nameid, plist_foldername){
    
    var curr_song_id= getCookie('curr_song_id');
    playlist_background.style.display= "none";
    create_playlist_form.style.display="none";

    $.ajax({
        url: 'process.php',
        method:"POST",
        data: {
            plist_updatesong: true,
            plist_uid:uid,
            plist_song_id:curr_song_id,
            plist_name_id: plist_nameid,
            plist_folder_name:plist_foldername
        },
        success: function (resp) {
            console.log("From updateSongToPlayList : "+resp);
            var myDataArray= resp.split("||plist_song||");
            if(myDataArray[0]=='true'){
                if ($('input[name=playlist_folder]:checked').length > 0) {
                    var radio = document.querySelector('input[type=radio][name=playlist_folder]:checked');
                    radio.checked = false;
                }
                add_playlist_foldername.value="";
            }
        },
        error: function () {
            reject(err)
        }
    });
}

function removeSongFromPlaylist(plist_songid, plist_songname){
    $.ajax({
        url: 'process.php',
        method:"POST",
        data: {
            plist_deletesong: true,
            plist_songid:plist_songid,
        },
        success: function (resp) {
            console.log("From updateSongToPlayList : "+resp);
            if(resp=='true'){
                var delete_element= document.querySelector('.'+plist_songid);
                delete_element.style.display="none";
                alert("The Song : \""+plist_songname+"\" has been deleted from playlist successfully....\nYou can play the song even after the delete until reload, please enjoy them.");
            }
        },
        error: function () {
            reject(err)
        }
    });
}

function checkSongInPlayList(songID){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: 'process.php',
            method:"POST",
            data: {song_id:songID},
            success: function (resp) {
                resolve(resp)
            },
            error: function () {
                reject(err)
            }
        });
    });
}

function updateRadioButtonPlaylist(psong_id){

    $.ajax({
        url: 'process.php',
        method:"POST",
        data: {
            setradio_ajax: true,
            radio_psong_id:psong_id,
            
        },
        success: function (resp) {
            console.log("From updateRadioButtonPlaylist : "+resp);
            var myDataArray= resp.split("||radio_psong||");
            if(myDataArray[0]=='true'){
                var song_name_id= myDataArray[1];
                var radio_btn= document.querySelector('#'+song_name_id);
                radio_btn.checked= true;
            }
            else{
                var radio = document.querySelector('input[type=radio][name=playlist_folder]:checked');
                radio.checked = false;
            }
        },
        error: function () {
            reject(err)
        }
    });
}

function loadTrack(checkClick, thisSongId, thisSongLanguage, thisSongGenre, thisArtistName, thisAlbumMovieName, 
    thisSongname, thisSongFullPath, thisSongTimeMin, thisSongTimeSec, checkFromPlistPage, thisSongSrc){

    clearInterval(timer);
    resetSongRangeBar();
    updateRadioButtonPlaylist(thisSongId);
    toggle_music_player_open=1;
    console.log("loadTrack Function Entered ");
    web_music_player_div.style.transform= "translateY(" + (0) + "px)";
    web_music_player_div.style.opacity = "1";
    web_music_player_div.style.transition = "ease-in-out 0.5s";

    abt_song_details.style.display= "block";
    abt_song_img.src= thisSongSrc;
    abt_sdata_songname.innerHTML= "<b>Song Name : &nbsp;&nbsp;</b>"+thisSongname;
    abt_sdata_artistname.innerHTML= "<b>Artist Name : &nbsp;&nbsp;</b>"+thisArtistName;
    abt_sdata_language.innerHTML= "<b>Language : &nbsp;&nbsp;</b>"+thisSongLanguage;
    abt_sdata_genre.innerHTML= "<b>Song Genre : &nbsp;&nbsp;</b>"+thisSongGenre;
    abt_sdata_albumname.innerHTML= "<b>Movie / Album Name : &nbsp;&nbsp;</b>"+thisAlbumMovieName;

    if(checkFromPlistPage=='true')
        abt_songadded_div.innerHTML="";
    
    checkSongInPlayList(thisSongId).then(function(data) {
        result = (data=='true');
        abt_playlists_btn.style.display= result ? "none" : "block";
        abt_songadded_div.style.display= result ? "flex" : "none";
        
        if(result){
            playlist_dialog_title_txt.innerHTML="Change the Playlist";
        }
        else{
            playlist_dialog_title_txt.innerHTML="Add to Playlist";
        }
    }).catch(function(err) {
        result = null;
    });

    (random_song_counter) ? random_song.style.color= "#fff" : "rgb(168, 168, 168)";
    song_title_name.innerHTML= thisSongname;
    song_artist_name.innerHTML= thisArtistName;
    track_song.src= thisSongFullPath;
    song_full_time.innerHTML= time_convert(false, 0, thisSongTimeMin, thisSongTimeSec);

    var song_idcookie= getCookie('all_songid_arr');
    var newString = song_idcookie.replaceAll("%22%2C%22", ";").replaceAll("%5B%22","").replaceAll("%22%5D","");
    var songid_arr= newString.split(';');
    var index=songid_arr.indexOf(thisSongId);

    // console.log("Current song id : "+songId);
    // for(var i=0;i<songid_arr.length;i++)
    //     console.log(songid_arr[i]);

    // console.log(index);
    // console.log(index-1);
    // console.log(index+1);
    
    setCookie('curr_song_id',thisSongId);
    setCookie('prev_song_id',songid_arr[index-1],364);
    setCookie('next_song_id',songid_arr[index+1],364);

    track_song.load();
    timer= setInterval(range_slider, 100);
    
    if(checkClick=='main_song_container'){
        playSong();
    }
    else{
        justPlay();
    }
}

document.addEventListener('keydown', event => {
    var navitem_searchbar= document.getElementById('navitem_searchbar');
    if (event.code === 'Space' && !(navitem_searchbar==document.activeElement)) {
        event.preventDefault();
    }
    if(event.keyCode== 39){
        event.preventDefault();
    }
    if(event.keyCode== 37){
        event.preventDefault();
    }
});

document.addEventListener('keyup', event => {
    var navitem_searchbar= document.getElementById('navitem_searchbar');
    if (event.code === 'Space' && !(navitem_searchbar==document.activeElement)) {
        event.preventDefault();
        justPlay();
    }
    if(event.keyCode== 39){
        event.preventDefault();
        fast_forward_song();
    }
    if(event.keyCode== 37){
        event.preventDefault();
        fast_backward_song();
    }
});

function justPlay(){
    console.log("justPlay Function Entered ");
    if(playing_song==false){
        playSong();
    }
    else{
        pauseSong();
    }
}

function playSong(){
    console.log("playSong Function Entered ");
    track_song.play();
    playing_song= true;
    play_btn.classList.remove("fa-play-circle");
    play_btn.classList.add("fa-pause");
}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function pauseSong(){
    song_timebar.value=50;
    console.log("pauseSong Function Entered ");
    track_song.pause();
    playing_song= false;
    play_btn.classList.remove("fa-pause");
    play_btn.classList.add("fa-play-circle");
}

function playActionSong(actioncheck){

    var song_idcookie= getCookie('all_songid_arr');
    var newString = song_idcookie.replaceAll("%22%2C%22", ";").replaceAll("%5B%22","").replaceAll("%22%5D","");
    var songid_arr= newString.split(';');

    var random_song_id= songid_arr[Math.floor(Math.random()*songid_arr.length)];
    var song_id= (random_song_counter) ? random_song_id : (actioncheck=='next' ? getCookie('next_song_id') : getCookie('prev_song_id'));
    console.log(songid_arr.length);

    if(songid_arr.indexOf(song_id) >= 0 & songid_arr.indexOf(song_id) < songid_arr.length){
        $.ajax({
            url:"playNextSong.php",
            method:"POST",
            data:{
                next_song_id:song_id
            },
            success:function(data){
                var myDataArray= data.split("||song_data||");
                loadTrack('main_song_container',myDataArray[1],myDataArray[6],myDataArray[2],
                myDataArray[3],myDataArray[4],myDataArray[5],myDataArray[7],
                    parseInt(myDataArray[10]), parseInt(myDataArray[11],'false',myDataArray[9]));
            }
        });
    }
    else{
        console.log("No more songs !!");
    }
}

function volume_change(){
    pmusic_display_volume.innerHTML= song_volume_bar.value;
    curr_volume_val= song_volume_bar.value;
    track_song.volume= song_volume_bar.value / 100;
}

function resetSongRangeBar(){
    song_timebar.value=0;
}

function toggle_volume(){
    if(volume_counter==1){
        volume_counter=0;
        track_song.volume=0;
        song_volume_bar.value=0;
        pmusic_display_volume.innerHTML= 0;
        song_volume_btn.classList.remove("fa-volume-up");
        song_volume_btn.classList.add("fa-volume-off");
    }
    else{
        volume_counter=1;
        track_song.volume= curr_volume_val / 100;
        song_volume_bar.value= curr_volume_val;
        pmusic_display_volume.innerHTML= curr_volume_val;
        song_volume_btn.classList.remove("fa-volume-off");
        song_volume_btn.classList.add("fa-volume-up");
    }
}

function song_changeduration(){
    song_bar_pos= track_song.duration * (song_timebar.value / 100);
    track_song.currentTime= song_bar_pos;
}

function fast_forward_song(){
    track_song.currentTime += 10;
}

function fast_backward_song(){
    track_song.currentTime -= 10;
}

function time_convert(actioncheck,num, min, sec){
    if(actioncheck==true){
        var minutes = Math.floor(num / 60);
        var seconds = Math.floor(num - minutes * 60);
    }
    else{
        var minutes = min;
        var seconds= sec;
    }
    
    var final_min= (minutes==0) ? "00" : ((minutes < 10) ? "0"+minutes : minutes);
    var final_sec= (seconds==0) ? "00" : ((seconds < 10) ? "0"+seconds : seconds);
    return final_min + ":" + final_sec;
}

function range_slider(){
    let position= 0;
    if(!isNaN(track_song.duration)){
        position= track_song.currentTime * (100 / track_song.duration);
        song_timebar.value= position;
        song_play_time.innerHTML= time_convert(true, track_song.currentTime, 0, 0);
    }

    if(track_song.ended){
        play_btn.classList.add("fa-play-circle");
        abt_song_details.style.display= "none";
        playActionSong('next');
    }
}

function randomSong(){
    if(random_song_counter){
        random_song.style.color= "rgb(168, 168, 168)";
        random_song_counter=false;
    }
    else{
        random_song.style.color= "#fff";
        random_song_counter=true;
    }
}