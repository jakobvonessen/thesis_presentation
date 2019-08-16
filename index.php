<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
	<title>Thesis presentation</title>
	<script src="jquery.min.js"></script>
	<style>
		body{
			margin:0;
			overflow:hidden;
		}
		p{
		    font-size:20px;
		}
		h1{
		    font-size:30px;
		}
        #primer{
            position: absolute;
            top: 12%;
            left:25%;
            width:50%;
            background:rgba(255,255,255,0.8);
            text-align:center;
            margin:auto;
            z-index:3;
            padding:10px;
            
        }
		#container, #loaded {
			color: red;
			font-weight: 700;
		}

		#container {
			position: absolute;
			top: 10px;
			font-size:100px;
		}

		#loaded {
			position: absolute;
			bottom: 10px;
			right: 10px;
			font-size:30px;
		}

		#debug {
			visibility:hidden;
		}

		#video-background{
			margin:0;
			padding:0;
			width:100%;
		    height:100vh;
		    overflow: hidden;
		    background:rgb(47,76,123);
		}

		#playState {
			position:fixed;
			bottom: 10px;
			left: 10px;
			width: 10px;
			height: 10px;
			background:white;
			border-radius:5px;
		}

		#video-background video{
			object-fit: contain;
		    width:100%;
		    height:100%;
		}
		
		#btns {
		    display:inline-block;
		    width: 100%;
		    height: 100%;
		}
		
		#leftbtn, #rightbtn {
		    position:absolute;
		    top:0;
		    width: 50%;
		    height:100%;
		}
		
		#leftbtn {
		    left: 0px;
		    z-index:2;
		}
		
		#rightbtn {
		    right: 0px;
		    z-index:2;
		}

	</style>
</head>
<body>
	<div id="video-background">
		<video
		    id="video-active"
		    class="video-active"
		    width="640"
		    height="390"
		    controls="">
		    <!--<source src="presentation.webm" controls="" type="video/webm">-->
		    <source src="video.mp4" controls="" type="video/mp4"> // Include reversed and several filetypes
			<!--<source src="test1232.ogg" type="video/ogg"> // Include reversed and several filetypes-->
		</video>
	</div>
	<div id="btns">
	    <div id="leftbtn"></div>
	    <div id="rightbtn"></div>
	    
	</div>
	<div id="container">
		<div id="playState"></div>
		<div id="debug">
			<div id="currentFrame">0:00</div>
			<div id="currentTime">0:00</div>
			<div id="duration">0:00</div>
			<div id="frameSkip">0:00</div>
			<div id="nextFrame">0:00</div>
		<</div>
	</div>
	<div id="primer" align="center">
        <h1>Pyrolysis presentation</h1>
    	<p>This is a presentation used for the thesis project I completed for my M.Sc. degree in Sustainable Energy Engineering, between 02/2018 and 06/2019.</p>
    	<p>To go forward, press the <strong>right</strong> keyboard button when you see a small white circle in the lower left corner.</p>
    	<p>To get in touch, see <a href="http://jakobve.com/linkedin">jakobve.com/linkedin</a>.</p>
    	<button>View the presentation</button>
	</div>
	<div id="loaded"></div>
</body>
<script src="Tocca.js"></script>
<script>

    $("body").on('swipeleft',function(e,data){goForward();});
    $("body").on('swiperight',function(e,data){goBack();});
    $("button").on("click",function(){$("#primer").fadeOut();});//css('display','none');});
	video = document.querySelector("#video-active");
	framesPath = "frames.txt";
	repeatPath = "repeat.txt";
	framerate = 60;
	//framesToRepeat = 80;
	framesToRepeat = 100;
	framesToRepeatOrig = 100;
	timeToRepeat = framesToRepeat/framerate;
	frames = [];
	nextFrameIndx = 1;
	currentFrame = 0;
	previousFrame = 0;
	skipFrames = 0;
	skipBuffer = false;

	$.get(framesPath, function(data,status){
		if(status == "success"){
			var splitStrings = data.split("\n");
			for(var i = 1; i < splitStrings.length; i++){
				var rowStr = splitStrings[i];
				if(rowStr == "Start..."){
					break;
				}
				frames.push(parseInt(rowStr));
			}
		}
	});
	
	repeatIndxs = [15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,55,56,57,58,59,60, 65, 66, 67, 70, 71, 72, 73, 74, 75, 77, 78]; //79, 82, 83
	longRepeatIndxs = [29,30,31];
	molTwoIndxs = [17,18,24,25];
	molTwoRepeat = 110;
	microwaveIndxs = [54,55,56,57,58,59,60,77];
	microwaveRepeat = 140;
	heaterIndxs = [65,66];
	heaterRepeat = 90;
	
    //Different: 29 (long)
	//repeatIndxs = [4, 6, 8];

	$(document).ready(function(){
		document.querySelector("#video-active").defaultPlaybackRate = 1;
  	$("#video-active").on(
    "timeupdate", 
    function(event){
      onTrackedVideoFrame(this.currentTime, this.duration);
    });
  	$('#rightbtn').click(function() {
        goForward();
    });
    $('#leftbtn').click(function() {
        goBack();
    });
    /*$('#video-active').bind('touchstart', function() {
        goForward();
        //clearInterval(intervalRewind);
        //this.paused ? this.play() : this.pause();
    });*/
	//$("#video-active").attr('controls','');
	$("#video-active").removeAttr('controls');
	$("#video-active")[0].width = $(window).width();
	//$("#video-active")[0].play();
	$("#video-active")[0].addEventListener('progress',function(){
		//var loadedFraction = Math.round(this.buffered.end(0)/this.duration * 100);
		//$("#loaded").text(loadedFraction + " %");
		//if(loadedFraction == 100){
		  //  $("#loaded").css("visibility","hidden");
		});
	$("#loaded").css("visibility","hidden");
	//$("#video-active")[0].requestFullscreen();
	});

function pauseVideo(){
    if(!video.paused){
        //video.currentTime = nextFrame*framerate;
        //console.log("Current: "+video.currentTime);
        newTime = frames[nextFrameIndx]/framerate;
        //console.log("New: "+newTime)
        //console.log("NextFrame: "+frames[nextFrameIndx]);
        //console.log("Frame rate: "+framerate);
        video.currentTime = newTime;
        video.pause();
    }
}

function playVideo(){
    if(video.paused){
        video.play();
        //video.playbackRate = 0.5;
    }
    /*var promise = video.play();
    if (promise !== undefined) {
    promise.then(_ => {
        // Autoplay started!
    }).catch(error => {
        // Autoplay was prevented.
        // Show a "Play" button so that user can start playback.
    });*/
}

function onTrackedVideoFrame(currentTime, duration){
    timeToRepeat = longRepeatIndxs.includes(nextFrameIndx) ? 100/framerate : microwaveIndxs.includes(nextFrameIndx) ? microwaveRepeat/framerate : heaterIndxs.includes(nextFrameIndx) ? heaterRepeat/framerate : molTwoIndxs.includes(nextFrameIndx) ? molTwoRepeat/framerate : framesToRepeat/framerate;
    console.log(timeToRepeat);
    currentFrame = Math.round(currentTime*framerate);
    //currentTime = Math.round(currentTime*10)/10;
    $("#currentFrame").text(currentFrame);
    $("#currentTime").text(currentTime);
    $("#duration").text(duration);
    skipFrames = currentFrame - previousFrame;
    $("#frameSkip").text(skipFrames);
    var nextFrame = frames[nextFrameIndx] - skipFrames;
    $("#nextFrame").text(nextFrame);
    //alert(repeatIndxs[3]);
    // Video control
    if(currentFrame >= nextFrame - skipFrames){
        if(!repeatIndxs.includes(nextFrameIndx)){
            if(skipBuffer){
                playVideo();
                //video.play();
                skipBuffer = false;
                nextFrameIndx+=1;
            } else {
                pauseVideo();
                //video.pause();    
            }
            //nextFrameIndx+=1;
        }
        else{
            if(skipBuffer){
                //video.play();
                playVideo();
                skipBuffer = false;
                nextFrameIndx+=1;
                if(microwaveIndxs.includes(nextFrameIndx)){
                    framesToRepeat = microwaveRepeat;
                } else if(heaterIndxs.includes(nextFrameIndx)) {
                    framesToRepeat = heaterRepeat;
                } else {
                    framesToRepeat = framesToRepeatOrig;
                }
            } else {
                pauseVideo();
                //video.pause();
                video.currentTime -= timeToRepeat;
                playVideo();
                //video.play();
            }        
        }
		/*if(currentlyBuffering){
		    video.currentTime -= timeToRepeat;
		    alert("a");
		} else if(currentlyBuffering && skipBuffer){
		    alert("b");
		    currentlyBuffering = false;
		    skipBuffer = false;
		    nextFrameIndx+=1;
		}
		else{
        	video.pause();
        	alert("c");
        	nextFrameIndx+=1;    
        	skipBuffer = false;
        	if(repeatIndxs.includes(nextFrameIndx-1)){
        	    currentlyBuffering = true;
        	}
		}*/
    }
    $("#playState").css("visibility",video.paused ? "visible" : "hidden");
    previousFrame = currentFrame;
    currentPercent = (currentTime/duration) * 100;
    //$("#loaded").css("visibility",currentPercent < loadedFraction - 1 ? "hidden" : "visible");
    text = nextFrameIndx;
    if(repeatIndxs.includes(nextFrameIndx)){
        text = "repeat, "+skipBuffer+", "+text;    
    }

    $("#loaded").text(text);
    /*if(currentPercent < loadedFraction){
        $("#loaded").css("visibility","visible");
    }else{
        $("#loaded").css("visibility","visible");
    }*/
}

function goForward(){
    if(repeatIndxs.includes(nextFrameIndx)){
	    skipBuffer = true;
	} else{
	    if(video.paused){
		    nextFrameIndx+=1;
		    playVideo();
		    //video.play();
	    } else {
	        skipBuffer = true;
	    }
	}
	playVideo();
}

function goBack(){
    nextFrameIndx-=1;
    //newTime = frames[nextFrameIndx]/framerate;
    //video.currentTime = newTime;
    video.pause();
}

$("body").keydown(function(e){
	if(e.keyCode == 37) { // Left
		goBack();
	} else if(e.keyCode == 39 || e.keyCode == 34){ // Right, page down
		goForward();
	    //video.play();
	} else if(e.keyCode == 16) { // Left shift
	    var container = $("#loaded");
	    if(container.css("visibility") === "visible"){
	        container.css("visibility", "hidden");
	    }
        else {
            container.css("visibility", "visible");
        }
	} else if(e.keyCode == 17) { // Left ctrl
	    //var container = $("#playState");
	    //if(container.css("visibility") === "visible"){
	  //      container.css("visibility", "hidden");
	    //}
        //else {
        //    container.css("visibility", "visible");
        //}
	} else if(e.keyCode == 65){ // a
	    framesToRepeat+=1;
	    console.log("framesToRepeat: "+framesToRepeat);
	    
	} else if(e.keyCode == 81){ // z
	    framesToRepeat-=1;
	    console.log("framesToRepeat: "+framesToRepeat);
	}
	timeToRepeat = framesToRepeat/framerate;
	});
</script>
</html>