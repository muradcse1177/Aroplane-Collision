<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
canvas {
    border:1px solid #d3d3d3;
    background-color: #000000;
}
</style>
</head>

<body onload="startGame()">
</body>
<script>

var myGamePiece;
var myBackground;
var myscore;
myscore = {};
var myObstacles = [];

function startGame() {
    myGamePiece = new component(100, 70, "plane.gif", 10, 120, "image");
    myBackground = new component(656, 270, "", 0, 0, "background");
	myScore = new component("30px", "Consolas", "white", 280, 40, "text");
	mySound = new sound("sound.mp3");
	mySound.play();
    myGameArea.start();
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 480;
        this.canvas.height = 270;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.frameNo = 0;
        this.interval = setInterval(updateGameArea, 20);
        },
    clear : function() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    },
    stop : function() {
        clearInterval(this.interval);
    }
}

function component(width, height, color, x, y, type) {
    this.type = type;
    if (type == "image" || type == "background") {
        this.image = new Image();
        this.image.src = color;
    }
    this.width = width;
    this.height = height;
    this.speedX = 0;
    this.speedY = 0;    
    this.x = x;
    this.y = y;    
    this.update = function() {
        ctx = myGameArea.context;
		if (this.type == "text") {
            ctx.font = this.width + " " + this.height;
            ctx.fillStyle = color;
            ctx.fillText(this.text, this.x, this.y);
        } 
        if (type == "image" || type == "background") {
            ctx.drawImage(this.image,this.x, this.y,this.width, this.height);
        if (type == "background") {
            ctx.drawImage(this.image,  this.x + this.width, this.y,this.width, this.height);
        }
        } else {
            ctx.fillStyle = color;
            ctx.fillRect(this.x, this.y, this.width, this.height);
        }
    }
    this.newPos = function() {
        this.x += this.speedX;
        this.y += this.speedY;
        if (this.type == "background") {
            if (this.x == -(this.width)) {
                this.x = 0;
            }
        }
    }
	
	this.crashWith = function(otherobj) {
        var myleft = this.x;
        var myright = this.x + (this.width)-2;
        var mytop = this.y+30;
        var mybottom = this.y + (this.height)-20;
        var otherleft = otherobj.x;
        var otherright = otherobj.x + (otherobj.width);
        var othertop = otherobj.y;
        var otherbottom = otherobj.y + (otherobj.height);
        var crash = true;
        if ((mybottom < othertop) || (mytop > otherbottom) || (myright < otherleft) || (myleft > otherright)) {
            crash = false;
        }
         return crash;
     }
    
}

function sound(src) {
    this.sound = document.createElement("audio");
    this.sound.src = src;
    this.sound.setAttribute("preload", "auto");
    this.sound.setAttribute("controls", "none");
    this.sound.style.display = "none";
    document.body.appendChild(this.sound);
    this.play = function(){
        this.sound.play();
    }
    this.stop = function(){
        this.sound.pause();
    }    
}


function updateGameArea() {
	var x, height, gap, minHeight, maxHeight, minGap, maxGap;
	for (i = 0; i < myObstacles.length; i += 1) {
		if (myGamePiece.crashWith(myObstacles[i])) {
			mySound.stop();
			myGameArea.stop();
			return;
		} 
    }
    myGameArea.clear();
	 myGameArea.frameNo += 1;
    if (myGameArea.frameNo == 1 || everyinterval(170)) {
        x = myGameArea.canvas.width;
        minHeight = 20;
        maxHeight = 200;
        height = Math.floor(Math.random()*(maxHeight-minHeight+1)+minHeight);
        minGap = 50;
        maxGap = 200;
        gap = Math.floor(Math.random()*(maxGap-minGap+1)+minGap);
        myObstacles.push(new component(20, height, "red", x, 0));
        myObstacles.push(new component(20, x - height - gap, "red", x, height + gap));
    }
    for (i = 0; i < myObstacles.length; i += 1) {
        myObstacles[i].x += -1;
        myObstacles[i].update();
    }	
    myBackground.speedX = -1;
    myBackground.newPos();   	
    myBackground.update();
	myScore.text="SCORE: " + myGameArea.frameNo;
    myScore.update();
    myGamePiece.newPos();    
    myGamePiece.update();
}
function everyinterval(n) {
    if ((myGameArea.frameNo / n) % 1 == 0) {return true;}
    return false;
}
function move(dir) {
    myGamePiece.image.src = "plane.gif";
    if (dir == "up"){
		myGamePiece.speedY = -2;
	}
    if (dir == "down"){
		myGamePiece.speedY = 2; 
	}
    if (dir == "left"){
		myGamePiece.speedX = -2; 
	}
    if (dir == "right"){
		myGamePiece.speedX = 2; 
	}
}
function checkKey(e) {
	myGamePiece.image.src = "plane.gif";
    e = e || window.event;

    if (e.keyCode == '38') {
        // up arrow
		myGamePiece.speedY = -2;
    }
    else if (e.keyCode == '40') {
        // down arrow
		myGamePiece.speedY = 2;
    }
    else if (e.keyCode == '37') {
       // left arrow
	   myGamePiece.speedX = -2;
    }
    else if (e.keyCode == '39') {
       // right arrow
	   myGamePiece.speedX = 2; 
    }

}
function clearmove() {
    myGamePiece.image.src = "plane.gif";
    myGamePiece.speedX = 0; 
    myGamePiece.speedY = 0; 
}
startGame();
</script><br>
<div style="text-align:center;width:480px;">
  <button style="text-align:center;font-size:20px; width:80px;" onmousedown="move('up')" onmouseup="clearmove()" onkeypress="checkKey('up')" ontouchstart="move('up')">UP</button><br><br>
  <button style="text-align:center;font-size:20px; width:80px;" onmousedown="move('left')" onmouseup="clearmove()" onkeypress="checkKey('left')" ontouchstart="move('left')">LEFT</button>
  <button style="text-align:center;font-size:20px; width:80px;" onmousedown="move('right')" onmouseup="clearmove()" onkeypress="checkKey('right')" ontouchstart="move('right')">RIGHT</button><br><br>
  <button style="text-align:center;font-size:20px; width:80px;" onmousedown="move('down')" onmouseup="clearmove()" onkeypress="checkKey('down')" ontouchstart="move('down')">DOWN</button>
</div>
</body>
</html>
