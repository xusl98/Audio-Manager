<?php
$directorio = '../music';
//Array con las canciones
$canciones  = scandir( $directorio );

// print_r( $canciones );
?>
<html>
<link href="../fontawesome-free-5.11.2-web/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<link rel="stylesheet" type="text/css" href="../css/list.css">
<body class="container">
<h1>Music</h1>
<div id="rightMenuOver"><div id="musicIconOver"></div></div>
<div id="rightMenu">
   <div id="musicIcon"></div>
</div>
<div class="container">
<div style="padding-right: 5%; margin-bottom: 2%;">
   <span id="searchContainer"><input type="text" id="search" placeholder="Search for song.."></span>
   <span style="float:right; margin-left: 6%; display:inline-block;">
      <label style="margin-right:1%;">Random</label>
      <label class="switch">
         <input id="random" type="checkbox">
         <span class="slider round"></span>
      </label>
   </span>
</div>
<ul id="songs">
   <!-- Create the list dynamically -->
<?php
$cont = 0;
foreach ( $canciones as $cancion ) {
    if ( $cont > 1 ) {
        echo '<div>';
        echo '<li id="'.$cont.'">'.$cancion.'</li>';
        echo '</div>';
    }
    $cont++;
}

?>

</ul>
</div>
<input type="range" id="volume">
<div id="PlayPauseContainer">
<div id="playPause"></div>
</div>
<div id="chatContainer">
<div id="chatTitle">Chat</div>
<iframe src="../chat/index.html" id="chat"></iframe>
</div>
<div id = 'div'>

</div>

</body>
</html>
<script>

   // var clicked = undefined;
//Add onclick to every song li
   var lis = document.getElementsByTagName("li");
   for (var li of lis){
      li.addEventListener ("click", function(){
         if (this.parentElement.childNodes.length > 1){//if the li already has the iframe the iframe gets removed onclick
            this.nextSibling.removeChild(this.nextSibling.lastChild);
         } else {

            var iframe = document.createElement( 'iframe' );
            iframe.visibility = "hidden";
            var div = document.getElementById('div');
            iframe.name = this.innerText;
            iframe.id = "iframe";
            iframe.src = '../html/MiAudio.html';
            div.innerHTML = "";
            div.appendChild(iframe);
            iframe.classList.add("slideDown");
            this.parentElement.appendChild(div);



            //eventListener end of song, has to wait 1 sec to let the audio tag of the iframe load
            setTimeout(onEnd.bind(null, this, iframe), 500);
            setTimeout(playPause.bind(null, iframe), 500);
            
         }
         
      });
      
   }
   //dispatches a click on the next or a random song
   function onEnd(li, iframe){
      var lis = document.getElementsByTagName("li");
      var audio = iframe.contentWindow.document.getElementById("audio");
      audio.volume = document.getElementById("volume").value / 100;
      
      audio.addEventListener("ended", function(){
         if (window.parent.document.getElementById('random').checked == false){
            document.getElementById((parseInt(li.id) + 1)).click();
         } else {
            var randomSong = Math.round( Math.random() * ((lis.length-1) - 1) + 1);
            console.log(randomSong);
            document.getElementById(randomSong).click();
            window.location.hash = randomSong;
         }
            });
   }

//Filter
   document.getElementById("search").addEventListener ("keyup", function(){
      var toSearch = this.value.toUpperCase();
      for (var li of lis){
         if (li.innerText.toUpperCase().indexOf(toSearch) == -1){
            li.style.display = "none";
         } else {
            li.style.display = "";
         }
      }

   });

   //RightMenu
   // show
   document.getElementById("rightMenuOver").addEventListener("mouseover", function(){
      document.getElementById("rightMenu").classList.add("rightMenuAnim");
   });
   // hide
   document.getElementById("rightMenuOver").addEventListener("mouseout", function(){
      document.getElementById("rightMenu").classList.remove("rightMenuAnim");
   });
   
   // MusicFocus onclick
   document.getElementById("musicIconOver").addEventListener("click", function(){
      console.log(document.getElementById("iframe").parentNode.parentNode);
      // window.location.hash = document.getElementById("iframe").parentNode.parentNode.firstChild.id;
      document.getElementById(document.getElementById("iframe").parentNode.parentNode.firstChild.id).scrollIntoView();
   });

   document.getElementById("musicIconOver").addEventListener("mouseover", function(){
      document.getElementById("musicIcon").style.background = "url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4Igp3aWR0aD0iNjQiIGhlaWdodD0iNjQiCnZpZXdCb3g9IjAgMCAxNzIgMTcyIgpzdHlsZT0iIGZpbGw6IzAwMDAwMDsiPjxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0ibm9uemVybyIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIHN0cm9rZS1saW5lY2FwPSJidXR0IiBzdHJva2UtbGluZWpvaW49Im1pdGVyIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHN0cm9rZS1kYXNoYXJyYXk9IiIgc3Ryb2tlLWRhc2hvZmZzZXQ9IjAiIGZvbnQtZmFtaWx5PSJub25lIiBmb250LXdlaWdodD0ibm9uZSIgZm9udC1zaXplPSJub25lIiB0ZXh0LWFuY2hvcj0ibm9uZSIgc3R5bGU9Im1peC1ibGVuZC1tb2RlOiBub3JtYWwiPjxwYXRoIGQ9Ik0wLDE3MnYtMTcyaDE3MnYxNzJ6IiBmaWxsPSJub25lIj48L3BhdGg+PGcgZmlsbD0iIzM0OThkYiI+PHBhdGggZD0iTTgzLjMxMjUsNS4zNzVjLTQyLjk2ODUxLDAgLTc3LjkzNzUsMzQuOTY4OTkgLTc3LjkzNzUsNzcuOTM3NWMwLDQyLjk2ODUxIDM0Ljk2ODk5LDc3LjkzNzUgNzcuOTM3NSw3Ny45Mzc1YzQyLjk2ODUxLDAgNzcuOTM3NSwtMzQuOTY4OTkgNzcuOTM3NSwtNzcuOTM3NWMwLC00Mi45Njg1MSAtMzQuOTY4OTksLTc3LjkzNzUgLTc3LjkzNzUsLTc3LjkzNzV6TTgzLjMxMjUsMTAuNzVjNDAuMDA4MDYsMCA3Mi41NjI1LDMyLjU1NDQ0IDcyLjU2MjUsNzIuNTYyNWMwLDQwLjAwODA2IC0zMi41NTQ0NCw3Mi41NjI1IC03Mi41NjI1LDcyLjU2MjVjLTQwLjAwODA2LDAgLTcyLjU2MjUsLTMyLjU1NDQ0IC03Mi41NjI1LC03Mi41NjI1YzAsLTQwLjAwODA2IDMyLjU1NDQ0LC03Mi41NjI1IDcyLjU2MjUsLTcyLjU2MjV6TTgyLjEzNjcyLDIxLjUyMWMtMTUuODMxMDUsMC4zMjU0NCAtMzEuMTc5Miw2LjcxODc1IC00Mi41MzgwOSwxOC4wNzc2NGMtMjQuMDkzMDIsMjQuMTAzNTIgLTI0LjA5MzAyLDYzLjMxMzcyIDAsODcuNDE3MjRjMC41MjQ5LDAuNTI0OSAxLjIxNzc3LDAuNzg3MzUgMS45MDAxNSwwLjc4NzM1YzAuNjkyODcsMCAxLjM4NTc0LC0wLjI2MjQ1IDEuOTEwNjUsLTAuNzg3MzVjMS4wNDk4LC0xLjA0OTggMS4wNDk4LC0yLjc1MDQ5IDAsLTMuODAwMjljLTIyLjAxNDQsLTIyLjAwMzkxIC0yMi4wMDM5MSwtNTcuODAyMjQgMCwtNzkuODA2MTVjMTIuNzU1MTMsLTEyLjc2NTYyIDMxLjAzMjIzLC0xOC42ODY1MiA0OC44Nzg5MSwtMTUuODIwNTZjMS40NTkyMywwLjI0MTQ2IDIuODM0NDcsLTAuNzY2MzYgMy4wNzU5MywtMi4yMjU1OWMwLjI0MTQ2LC0xLjQ2OTczIC0wLjc2NjM2LC0yLjg1NTQ3IC0yLjIyNTU5LC0zLjA4NjQyYy0zLjY3NDMyLC0wLjU4Nzg5IC03LjM0ODYzLC0wLjgyOTM1IC0xMS4wMDE5NSwtMC43NTU4NnpNMTAzLjg4ODY3LDI1LjIyNjgxYy0xLjAzOTMxLDAuMDQxOTkgLTIuMDA1MTMsMC43MDMzNyAtMi4zOTM1NSwxLjc0MjY3Yy0wLjUyNDksMS4zOTYyNCAwLjE3ODQ3LDIuOTM5NDUgMS41NzQ3MSwzLjQ2NDM2YzEuOTEwNjQsMC43MTM4NyAzLjgwMDI5LDEuNTMyNzEgNS42NDc5NSwyLjQ1NjU0YzAuMzg4NDMsMC4xOTk0NiAwLjc5Nzg1LDAuMjkzOTUgMS4yMDcyOCwwLjI5Mzk1YzAuOTc2MzIsMCAxLjkyMTE0LC0wLjUzNTQgMi40MDQwNSwtMS40ODAyMmMwLjY2MTM4LC0xLjMxMjI2IDAuMTI1OTgsLTIuOTM5NDUgLTEuMTk2NzgsLTMuNjExMzNjLTIuMDA1MTMsLTEuMDA3ODEgLTQuMDk0MjQsLTEuOTIxMTQgLTYuMTgzMzUsLTIuNzA4NDljLTAuMzQ2NDMsLTAuMTI1OTggLTAuNzAzMzcsLTAuMTY3OTcgLTEuMDYwMywtMC4xNTc0N3pNMTE5LjQxNTI4LDM0LjA2NjE2Yy0wLjY5Mjg3LDAuMDgzOTggLTEuMzMzMjUsMC40MzA0MiAtMS43OTUxNywxLjAwNzgxYy0wLjkxMzMzLDEuMTc1NzggLTAuNzEzODcsMi44NTU0NyAwLjQ2MTkxLDMuNzY4OGMxLjc5NTE3LDEuNDA2NzQgMy41MjczNCwyLjk0OTk1IDUuMTMzNTQsNC41NjY2NWMyMi4wMTQ0MSwyMi4wMDM5MSAyMi4wMTQ0MSw1Ny44MDIyNCAwLDc5LjgwNjE1Yy0xLjA0OTgsMS4wNjAzIC0xLjA0OTgsMi43NTA0OSAwLDMuODEwNzljMC41MjQ5LDAuNTE0NCAxLjIxNzc3LDAuNzg3MzUgMS45MTA2NSwwLjc4NzM1YzAuNjgyMzcsMCAxLjM3NTI0LC0wLjI3Mjk1IDEuOTAwMTUsLTAuNzg3MzVjMjQuMDkzMDIsLTI0LjEwMzUyIDI0LjA5MzAyLC02My4zMjQyMiAwLC04Ny40Mjc3M2MtMS43NzQxNywtMS43NjM2NyAtMy42NjM4MiwtMy40NDMzNiAtNS42MjY5NSwtNC45ODY1N2MtMC41ODc4OSwtMC40NTE0MSAtMS4zMDE3NiwtMC42Mjk4OCAtMS45ODQxMywtMC41NDU5ek03OC4zNzg0Miw0My4wNDE5OWMtMC43NzY4NiwtMC4xMjU5OCAtMS41NzQ3MSwwLjA4Mzk4IC0yLjE3MzEsMC41OTgzOWMtMC42MDg4OSwwLjUwMzkxIC0wLjk1NTMyLDEuMjU5NzcgLTAuOTU1MzIsMi4wNDcxMnYzNS41MzU4OWMtMS43NTMxNywtMC4zOTg5MiAtMy41NDgzNCwtMC41OTgzOSAtNS4zNzUsLTAuNTk4MzljLTExLjg1MjI5LDAgLTIxLjUsOC40NDA0MyAtMjEuNSwxOC44MTI1YzAsMTAuMzcyMDcgOS42NDc3MSwxOC44MTI1IDIxLjUsMTguODEyNWMxMS44NTIyOSwwIDIxLjUsLTguNDQwNDMgMjEuNSwtMTguODEyNXYtMjkuOTcxOTJsMTguMzcxNTgsMy4wNTQ5M2MwLjc4NzM1LDAuMTM2NDcgMS41NzQ3MSwtMC4wODM5OCAyLjE3MzA5LC0wLjU4Nzg5YzAuNjA4ODksLTAuNTE0NCAwLjk1NTMyLC0xLjI3MDI2IDAuOTU1MzIsLTIuMDU3NjJ2LTE4LjgxMjVjMCwtMS4zMTIyNiAtMC45NTUzMiwtMi40MzU1NSAtMi4yNDY1OCwtMi42NDU1MXpNODAuNjI1LDQ4Ljg2ODQxbDI2Ljg3NSw0LjQ3MjE3djEzLjM1MzUybC0xOC4zNzE1OCwtMy4wNTQ5M2MtMC43NzY4NiwtMC4xMjU5OCAtMS41NzQ3MSwwLjA5NDQ4IC0yLjE3MzEsMC42MDg4OWMtMC42MDg4OSwwLjUwMzkxIC0wLjk1NTMyLDEuMjU5NzcgLTAuOTU1MzIsMi4wNDcxMnYzMy4xNDIzM2MwLDcuNDExNjIgLTcuMjMzMTUsMTMuNDM3NSAtMTYuMTI1LDEzLjQzNzVjLTguODkxODUsMCAtMTYuMTI1LC02LjAyNTg4IC0xNi4xMjUsLTEzLjQzNzVjMCwtNy40MTE2MiA3LjIzMzE1LC0xMy40Mzc1IDE2LjEyNSwtMTMuNDM3NWMyLjQzNTU1LDAgNC44MDgxMSwwLjQ2MTkxIDcuMDQ0MTksMS4zNzUyNGMwLjgzOTg0LDAuMzQ2NDMgMS43NzQxNywwLjI0MTQ2IDIuNTE5NTMsLTAuMjYyNDVjMC43NDUzNiwtMC41MDM5MSAxLjE4NjI4LC0xLjMzMzI1IDEuMTg2MjgsLTIuMjI1NTl6TTU3LjQ0NTMxLDEyNy4zNzI4Yy0xLjA0OTgsLTAuMTI1OTggLTIuMTEwMTEsMC4zNTY5MyAtMi42NTYwMSwxLjMyMjc2bC0yLjY4NzUsNC42NTA2NGMtMC43NDUzNiwxLjI5MTI2IC0wLjMwNDQ0LDIuOTI4OTUgMC45NzYzMiwzLjY3NDMyYzAuNDE5OTIsMC4yNDE0NiAwLjg4MTg0LDAuMzU2OTMgMS4zNDM3NSwwLjM1NjkzYzAuOTIzODMsMCAxLjgyNjY2LC0wLjQ4MjkxIDIuMzIwMDcsLTEuMzQzNzVsMi42ODc1LC00LjY1MDY0YzAuNzQ1MzYsLTEuMjgwNzYgMC4zMTQ5NCwtMi45Mjg5NiAtMC45NzYzMiwtMy42NzQzMmMtMC4zMjU0NCwtMC4xNzg0NyAtMC42NjEzOCwtMC4yOTM5NSAtMS4wMDc4MSwtMC4zMzU5NHpNMTA5LjE3OTY5LDEyNy4zNzI4Yy0wLjM0NjQzLDAuMDQxOTkgLTAuNjkyODcsMC4xNTc0NyAtMS4wMDc4MSwwLjMzNTk0Yy0xLjI5MTI2LDAuNzQ1MzYgLTEuNzIxNjgsMi4zOTM1NSAtMC45NzYzMiwzLjY4NDgybDIuNjg3NSw0LjY1MDY0YzAuNDkzNDEsMC44NjA4NCAxLjM5NjI0LDEuMzQzNzUgMi4zMjAwNywxLjM0Mzc1YzAuNDYxOTEsMCAwLjkyMzgzLC0wLjEyNTk4IDEuMzQzNzUsLTAuMzY3NDNjMS4yOTEyNiwtMC43NDUzNiAxLjcyMTY4LC0yLjM4MzA2IDAuOTc2MzIsLTMuNjYzODJsLTIuNjg3NSwtNC42NTA2NGMtMC41NTY0LC0wLjk2NTgyIC0xLjYxNjcsLTEuNDU5MjMgLTIuNjU2MDEsLTEuMzMzMjV6TTY5LjM4MTU5LDEzMi42MTEzM2MtMS4wMzkzLDAuMTQ2OTcgLTEuOTQyMTQsMC44OTIzMyAtMi4yMzYwOCwxLjk3MzYzbC0xLjM4NTc0LDUuMTk2NTNjLTAuMzg4NDMsMS40Mjc3MyAwLjQ3MjQxLDIuODk3NDYgMS45MDAxNSwzLjI4NTg5YzAuMjMwOTYsMC4wNjI5OSAwLjQ2MTkxLDAuMDgzOTggMC43MDMzNywwLjA4Mzk4YzEuMTc1NzgsMCAyLjI2NzU4LC0wLjc4NzM1IDIuNTkzMDIsLTEuOTg0MTNsMS4zOTYyNCwtNS4xOTY1M2MwLjM3NzkzLC0xLjQyNzczIC0wLjQ3MjQxLC0yLjkwNzk2IC0xLjkwMDE1LC0zLjI4NTg5Yy0wLjM2NzQzLC0wLjEwNDk4IC0wLjcyNDM2LC0wLjExNTQ4IC0xLjA3MDgsLTAuMDczNDl6TTk3LjI1MzkxLDEzMi42MTEzM2MtMC4zNDY0MywtMC4wNDE5OSAtMC43MTM4NywtMC4wMzE0OSAtMS4wODEzLDAuMDczNDljLTEuNDI3NzMsMC4zNzc5MyAtMi4yNzgwOCwxLjg1ODE1IC0xLjkwMDE1LDMuMjg1ODlsMS4zOTYyNCw1LjE5NjUzYzAuMzI1NDQsMS4xOTY3OCAxLjQwNjc0LDEuOTg0MTMgMi41OTMwMiwxLjk4NDEzYzAuMjQxNDYsMCAwLjQ3MjQxLC0wLjAyMDk5IDAuNzAzMzcsLTAuMDgzOThjMS40Mjc3MywtMC4zODg0MyAyLjI4ODU3LC0xLjg1ODE1IDEuOTAwMTUsLTMuMjg1ODlsLTEuMzg1NzQsLTUuMTk2NTNjLTAuMjkzOTUsLTEuMDgxMyAtMS4xOTY3OCwtMS44MjY2NiAtMi4yMjU1OSwtMS45NzM2M3pNODMuMzEyNSwxMzQuMzc1Yy0xLjQ5MDcyLDAgLTIuNjg3NSwxLjE5Njc4IC0yLjY4NzUsMi42ODc1djUuMzc1YzAsMS40OTA3MiAxLjE5Njc4LDIuNjg3NSAyLjY4NzUsMi42ODc1YzEuNDkwNzIsMCAyLjY4NzUsLTEuMTk2NzggMi42ODc1LC0yLjY4NzV2LTUuMzc1YzAsLTEuNDkwNzIgLTEuMTk2NzgsLTIuNjg3NSAtMi42ODc1LC0yLjY4NzV6Ij48L3BhdGg+PC9nPjwvZz48L3N2Zz4=')";
      document.getElementById("musicIcon").style.backgroundSize = "cover";
   });
   
   document.getElementById("musicIconOver").addEventListener("mouseout", function(){
      document.getElementById("musicIcon").style.background = "url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4Igp3aWR0aD0iNjQiIGhlaWdodD0iNjQiCnZpZXdCb3g9IjAgMCAxNzIgMTcyIgpzdHlsZT0iIGZpbGw6IzAwMDAwMDsiPjxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0ibm9uemVybyIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIHN0cm9rZS1saW5lY2FwPSJidXR0IiBzdHJva2UtbGluZWpvaW49Im1pdGVyIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHN0cm9rZS1kYXNoYXJyYXk9IiIgc3Ryb2tlLWRhc2hvZmZzZXQ9IjAiIGZvbnQtZmFtaWx5PSJub25lIiBmb250LXdlaWdodD0ibm9uZSIgZm9udC1zaXplPSJub25lIiB0ZXh0LWFuY2hvcj0ibm9uZSIgc3R5bGU9Im1peC1ibGVuZC1tb2RlOiBub3JtYWwiPjxwYXRoIGQ9Ik0wLDE3MnYtMTcyaDE3MnYxNzJ6IiBmaWxsPSJub25lIj48L3BhdGg+PGcgZmlsbD0iI2ZmZmZmZiI+PHBhdGggZD0iTTgzLjMxMjUsNS4zNzVjLTQyLjk2ODUxLDAgLTc3LjkzNzUsMzQuOTY4OTkgLTc3LjkzNzUsNzcuOTM3NWMwLDQyLjk2ODUxIDM0Ljk2ODk5LDc3LjkzNzUgNzcuOTM3NSw3Ny45Mzc1YzQyLjk2ODUxLDAgNzcuOTM3NSwtMzQuOTY4OTkgNzcuOTM3NSwtNzcuOTM3NWMwLC00Mi45Njg1MSAtMzQuOTY4OTksLTc3LjkzNzUgLTc3LjkzNzUsLTc3LjkzNzV6TTgzLjMxMjUsMTAuNzVjNDAuMDA4MDYsMCA3Mi41NjI1LDMyLjU1NDQ0IDcyLjU2MjUsNzIuNTYyNWMwLDQwLjAwODA2IC0zMi41NTQ0NCw3Mi41NjI1IC03Mi41NjI1LDcyLjU2MjVjLTQwLjAwODA2LDAgLTcyLjU2MjUsLTMyLjU1NDQ0IC03Mi41NjI1LC03Mi41NjI1YzAsLTQwLjAwODA2IDMyLjU1NDQ0LC03Mi41NjI1IDcyLjU2MjUsLTcyLjU2MjV6TTgyLjEzNjcyLDIxLjUyMWMtMTUuODMxMDUsMC4zMjU0NCAtMzEuMTc5Miw2LjcxODc1IC00Mi41MzgwOSwxOC4wNzc2NGMtMjQuMDkzMDIsMjQuMTAzNTIgLTI0LjA5MzAyLDYzLjMxMzcyIDAsODcuNDE3MjRjMC41MjQ5LDAuNTI0OSAxLjIxNzc3LDAuNzg3MzUgMS45MDAxNSwwLjc4NzM1YzAuNjkyODcsMCAxLjM4NTc0LC0wLjI2MjQ1IDEuOTEwNjUsLTAuNzg3MzVjMS4wNDk4LC0xLjA0OTggMS4wNDk4LC0yLjc1MDQ5IDAsLTMuODAwMjljLTIyLjAxNDQsLTIyLjAwMzkxIC0yMi4wMDM5MSwtNTcuODAyMjQgMCwtNzkuODA2MTVjMTIuNzU1MTMsLTEyLjc2NTYyIDMxLjAzMjIzLC0xOC42ODY1MiA0OC44Nzg5MSwtMTUuODIwNTZjMS40NTkyMywwLjI0MTQ2IDIuODM0NDcsLTAuNzY2MzYgMy4wNzU5MywtMi4yMjU1OWMwLjI0MTQ2LC0xLjQ2OTczIC0wLjc2NjM2LC0yLjg1NTQ3IC0yLjIyNTU5LC0zLjA4NjQyYy0zLjY3NDMyLC0wLjU4Nzg5IC03LjM0ODYzLC0wLjgyOTM1IC0xMS4wMDE5NSwtMC43NTU4NnpNMTAzLjg4ODY3LDI1LjIyNjgxYy0xLjAzOTMxLDAuMDQxOTkgLTIuMDA1MTMsMC43MDMzNyAtMi4zOTM1NSwxLjc0MjY3Yy0wLjUyNDksMS4zOTYyNCAwLjE3ODQ3LDIuOTM5NDUgMS41NzQ3MSwzLjQ2NDM2YzEuOTEwNjQsMC43MTM4NyAzLjgwMDI5LDEuNTMyNzEgNS42NDc5NSwyLjQ1NjU0YzAuMzg4NDMsMC4xOTk0NiAwLjc5Nzg1LDAuMjkzOTUgMS4yMDcyOCwwLjI5Mzk1YzAuOTc2MzIsMCAxLjkyMTE0LC0wLjUzNTQgMi40MDQwNSwtMS40ODAyMmMwLjY2MTM4LC0xLjMxMjI2IDAuMTI1OTgsLTIuOTM5NDUgLTEuMTk2NzgsLTMuNjExMzNjLTIuMDA1MTMsLTEuMDA3ODEgLTQuMDk0MjQsLTEuOTIxMTQgLTYuMTgzMzUsLTIuNzA4NDljLTAuMzQ2NDMsLTAuMTI1OTggLTAuNzAzMzcsLTAuMTY3OTcgLTEuMDYwMywtMC4xNTc0N3pNMTE5LjQxNTI4LDM0LjA2NjE2Yy0wLjY5Mjg3LDAuMDgzOTggLTEuMzMzMjUsMC40MzA0MiAtMS43OTUxNywxLjAwNzgxYy0wLjkxMzMzLDEuMTc1NzggLTAuNzEzODcsMi44NTU0NyAwLjQ2MTkxLDMuNzY4OGMxLjc5NTE3LDEuNDA2NzQgMy41MjczNCwyLjk0OTk1IDUuMTMzNTQsNC41NjY2NWMyMi4wMTQ0MSwyMi4wMDM5MSAyMi4wMTQ0MSw1Ny44MDIyNCAwLDc5LjgwNjE1Yy0xLjA0OTgsMS4wNjAzIC0xLjA0OTgsMi43NTA0OSAwLDMuODEwNzljMC41MjQ5LDAuNTE0NCAxLjIxNzc3LDAuNzg3MzUgMS45MTA2NSwwLjc4NzM1YzAuNjgyMzcsMCAxLjM3NTI0LC0wLjI3Mjk1IDEuOTAwMTUsLTAuNzg3MzVjMjQuMDkzMDIsLTI0LjEwMzUyIDI0LjA5MzAyLC02My4zMjQyMiAwLC04Ny40Mjc3M2MtMS43NzQxNywtMS43NjM2NyAtMy42NjM4MiwtMy40NDMzNiAtNS42MjY5NSwtNC45ODY1N2MtMC41ODc4OSwtMC40NTE0MSAtMS4zMDE3NiwtMC42Mjk4OCAtMS45ODQxMywtMC41NDU5ek03OC4zNzg0Miw0My4wNDE5OWMtMC43NzY4NiwtMC4xMjU5OCAtMS41NzQ3MSwwLjA4Mzk4IC0yLjE3MzEsMC41OTgzOWMtMC42MDg4OSwwLjUwMzkxIC0wLjk1NTMyLDEuMjU5NzcgLTAuOTU1MzIsMi4wNDcxMnYzNS41MzU4OWMtMS43NTMxNywtMC4zOTg5MiAtMy41NDgzNCwtMC41OTgzOSAtNS4zNzUsLTAuNTk4MzljLTExLjg1MjI5LDAgLTIxLjUsOC40NDA0MyAtMjEuNSwxOC44MTI1YzAsMTAuMzcyMDcgOS42NDc3MSwxOC44MTI1IDIxLjUsMTguODEyNWMxMS44NTIyOSwwIDIxLjUsLTguNDQwNDMgMjEuNSwtMTguODEyNXYtMjkuOTcxOTJsMTguMzcxNTgsMy4wNTQ5M2MwLjc4NzM1LDAuMTM2NDcgMS41NzQ3MSwtMC4wODM5OCAyLjE3MzA5LC0wLjU4Nzg5YzAuNjA4ODksLTAuNTE0NCAwLjk1NTMyLC0xLjI3MDI2IDAuOTU1MzIsLTIuMDU3NjJ2LTE4LjgxMjVjMCwtMS4zMTIyNiAtMC45NTUzMiwtMi40MzU1NSAtMi4yNDY1OCwtMi42NDU1MXpNODAuNjI1LDQ4Ljg2ODQxbDI2Ljg3NSw0LjQ3MjE3djEzLjM1MzUybC0xOC4zNzE1OCwtMy4wNTQ5M2MtMC43NzY4NiwtMC4xMjU5OCAtMS41NzQ3MSwwLjA5NDQ4IC0yLjE3MzEsMC42MDg4OWMtMC42MDg4OSwwLjUwMzkxIC0wLjk1NTMyLDEuMjU5NzcgLTAuOTU1MzIsMi4wNDcxMnYzMy4xNDIzM2MwLDcuNDExNjIgLTcuMjMzMTUsMTMuNDM3NSAtMTYuMTI1LDEzLjQzNzVjLTguODkxODUsMCAtMTYuMTI1LC02LjAyNTg4IC0xNi4xMjUsLTEzLjQzNzVjMCwtNy40MTE2MiA3LjIzMzE1LC0xMy40Mzc1IDE2LjEyNSwtMTMuNDM3NWMyLjQzNTU1LDAgNC44MDgxMSwwLjQ2MTkxIDcuMDQ0MTksMS4zNzUyNGMwLjgzOTg0LDAuMzQ2NDMgMS43NzQxNywwLjI0MTQ2IDIuNTE5NTMsLTAuMjYyNDVjMC43NDUzNiwtMC41MDM5MSAxLjE4NjI4LC0xLjMzMzI1IDEuMTg2MjgsLTIuMjI1NTl6TTU3LjQ0NTMxLDEyNy4zNzI4Yy0xLjA0OTgsLTAuMTI1OTggLTIuMTEwMTEsMC4zNTY5MyAtMi42NTYwMSwxLjMyMjc2bC0yLjY4NzUsNC42NTA2NGMtMC43NDUzNiwxLjI5MTI2IC0wLjMwNDQ0LDIuOTI4OTUgMC45NzYzMiwzLjY3NDMyYzAuNDE5OTIsMC4yNDE0NiAwLjg4MTg0LDAuMzU2OTMgMS4zNDM3NSwwLjM1NjkzYzAuOTIzODMsMCAxLjgyNjY2LC0wLjQ4MjkxIDIuMzIwMDcsLTEuMzQzNzVsMi42ODc1LC00LjY1MDY0YzAuNzQ1MzYsLTEuMjgwNzYgMC4zMTQ5NCwtMi45Mjg5NiAtMC45NzYzMiwtMy42NzQzMmMtMC4zMjU0NCwtMC4xNzg0NyAtMC42NjEzOCwtMC4yOTM5NSAtMS4wMDc4MSwtMC4zMzU5NHpNMTA5LjE3OTY5LDEyNy4zNzI4Yy0wLjM0NjQzLDAuMDQxOTkgLTAuNjkyODcsMC4xNTc0NyAtMS4wMDc4MSwwLjMzNTk0Yy0xLjI5MTI2LDAuNzQ1MzYgLTEuNzIxNjgsMi4zOTM1NSAtMC45NzYzMiwzLjY4NDgybDIuNjg3NSw0LjY1MDY0YzAuNDkzNDEsMC44NjA4NCAxLjM5NjI0LDEuMzQzNzUgMi4zMjAwNywxLjM0Mzc1YzAuNDYxOTEsMCAwLjkyMzgzLC0wLjEyNTk4IDEuMzQzNzUsLTAuMzY3NDNjMS4yOTEyNiwtMC43NDUzNiAxLjcyMTY4LC0yLjM4MzA2IDAuOTc2MzIsLTMuNjYzODJsLTIuNjg3NSwtNC42NTA2NGMtMC41NTY0LC0wLjk2NTgyIC0xLjYxNjcsLTEuNDU5MjMgLTIuNjU2MDEsLTEuMzMzMjV6TTY5LjM4MTU5LDEzMi42MTEzM2MtMS4wMzkzLDAuMTQ2OTcgLTEuOTQyMTQsMC44OTIzMyAtMi4yMzYwOCwxLjk3MzYzbC0xLjM4NTc0LDUuMTk2NTNjLTAuMzg4NDMsMS40Mjc3MyAwLjQ3MjQxLDIuODk3NDYgMS45MDAxNSwzLjI4NTg5YzAuMjMwOTYsMC4wNjI5OSAwLjQ2MTkxLDAuMDgzOTggMC43MDMzNywwLjA4Mzk4YzEuMTc1NzgsMCAyLjI2NzU4LC0wLjc4NzM1IDIuNTkzMDIsLTEuOTg0MTNsMS4zOTYyNCwtNS4xOTY1M2MwLjM3NzkzLC0xLjQyNzczIC0wLjQ3MjQxLC0yLjkwNzk2IC0xLjkwMDE1LC0zLjI4NTg5Yy0wLjM2NzQzLC0wLjEwNDk4IC0wLjcyNDM2LC0wLjExNTQ4IC0xLjA3MDgsLTAuMDczNDl6TTk3LjI1MzkxLDEzMi42MTEzM2MtMC4zNDY0MywtMC4wNDE5OSAtMC43MTM4NywtMC4wMzE0OSAtMS4wODEzLDAuMDczNDljLTEuNDI3NzMsMC4zNzc5MyAtMi4yNzgwOCwxLjg1ODE1IC0xLjkwMDE1LDMuMjg1ODlsMS4zOTYyNCw1LjE5NjUzYzAuMzI1NDQsMS4xOTY3OCAxLjQwNjc0LDEuOTg0MTMgMi41OTMwMiwxLjk4NDEzYzAuMjQxNDYsMCAwLjQ3MjQxLC0wLjAyMDk5IDAuNzAzMzcsLTAuMDgzOThjMS40Mjc3MywtMC4zODg0MyAyLjI4ODU3LC0xLjg1ODE1IDEuOTAwMTUsLTMuMjg1ODlsLTEuMzg1NzQsLTUuMTk2NTNjLTAuMjkzOTUsLTEuMDgxMyAtMS4xOTY3OCwtMS44MjY2NiAtMi4yMjU1OSwtMS45NzM2M3pNODMuMzEyNSwxMzQuMzc1Yy0xLjQ5MDcyLDAgLTIuNjg3NSwxLjE5Njc4IC0yLjY4NzUsMi42ODc1djUuMzc1YzAsMS40OTA3MiAxLjE5Njc4LDIuNjg3NSAyLjY4NzUsMi42ODc1YzEuNDkwNzIsMCAyLjY4NzUsLTEuMTk2NzggMi42ODc1LC0yLjY4NzV2LTUuMzc1YzAsLTEuNDkwNzIgLTEuMTk2NzgsLTIuNjg3NSAtMi42ODc1LC0yLjY4NzV6Ij48L3BhdGg+PC9nPjwvZz48L3N2Zz4=')";
      document.getElementById("musicIcon").style.backgroundSize = "cover";
   });


   //Volume control
   document.getElementById("volume").addEventListener("change", function(){
      iframe.contentWindow.document.getElementById("audio").volume = (this.value / 100);
   });



   // EVENT ONPLAY AND ONPAUSE TO CHANGE THE IMG OF THE PLAY PAUSE BTN
   // image swapping
   function playPause(iframe){
      var audio = iframe.contentWindow.document.getElementById("audio");
      audio.addEventListener("play", function(){
         document.getElementById("playPause").style.background = "url(../imgs/pause.png)";
         document.getElementById("playPause").style.backgroundSize = "cover";
      });
      audio.addEventListener("pause", function(){
         document.getElementById("playPause").style.background = "url(../imgs/play.png)";
         document.getElementById("playPause").style.backgroundSize = "cover";
      });
      // onclick method
      var playPauseBtn = document.getElementById("playPause").addEventListener("click", function(){
         var audio = document.getElementById("iframe").contentWindow.document.getElementById("audio");
         if (audio.paused){
            audio.play();
         } else {
            audio.pause();
         }
      });
   }
      
   

   



</script>