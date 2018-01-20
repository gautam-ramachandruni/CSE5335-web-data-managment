//Gautam Ramachandruni
var court, ball, paddle;
var offsetPaddle;
var offsetBallMin, offsetBallMax;;
var ballSpeed;
var dt;
var max_score; // stores max score
var strikes_count; // keep a count of strikes
var strike_span; // reference to <span> tag for strikes
var score_span; // reference to <span> tag for score
var speed_options; // array of the speed radio buttons
var setInterval_ID; // used when game is reset and ball needs to stop moving

function initialize() {
	court = document.getElementById("court"); // get reference to court
	ball = document.getElementById("ball"); // get reference to ball
	paddle = document.getElementById("paddle"); // get reference to paddle
	strike_span = document.getElementById("strikes");
	score_span = document.getElementById("score");
	offsetPaddle = court.getBoundingClientRect().top + (paddle.height/2);
	offsetBallMin = court.getBoundingClientRect().top - paddle.height; // minimum y position the ball can take in the court (i.e. touching the top side of the court)
	offsetBallMax = court.getBoundingClientRect().height - (ball.style.height) - paddle.height; // maximum y position the ball can take in the court (i.e. touching the bottom side of the court)
	dt = 5;
	max_score = -1;
	strikes_count = 0;
	speed_options = document.getElementsByName("speed");
	ballSpeed = 0;
	for(var i = 0; i < speed_options.length; i++) {
		if(speed_options[i].checked)
			ballSpeed = speed_options[i].value + 1;
	}
}

function startGame(event) {
	var left = 0; // ball always starts from left side of court
	// select random position from the top of the court to position the ball
	// it can range from court.getBoundingClientRect().top to court.getBoundingClientRect().height
	var top = (Math.random() * (offsetBallMax - offsetBallMin + 1) + offsetBallMin);
	ball.style.left = left + 'px'; //set the ball's left attribute
	ball.style.top = top + 'px'; // set the ball's top attribute
	var	angle = ((Math.random() * 90) - 45) * 3.14/180; // get an angle at random in the range (-45 to +45)
	var	vx = Math.cos(angle) * ballSpeed; // calculate speed coordinate x
	var	vy = Math.sin(angle) * ballSpeed; // calculate speed coordinate y
	var signRandomizer = Math.random(); // get a random value between 0 and 1
	//var top = ball.style.top;
	//var left = ball.style.left;
	setInterval_ID = setInterval(function() {
		//signRandomizer helps the ball move in a top right direction if < 0.5 or bottom right direction >= 0.5
		if(signRandomizer < 0.5) {
				top -=vy;
				ball.style.top = top + 'px';
		}
		else {
			top += vy;
			ball.style.top = top + 'px';
		}
		left += (vx * dt); // calculate new left from current left 
		ball.style.left = left + 'px'; // set it to the ball's left
		if (ball.getBoundingClientRect().top < court.getBoundingClientRect().top) {
			//ball hits top side of court, change speed coordinate
			vy = -vy;
		}
		else if (ball.getBoundingClientRect().left < court.getBoundingClientRect().left) {
			//ball hits left side of court, change speed coordinate
			vx=-vx;
		}
		else if (ball.getBoundingClientRect().bottom > (court.getBoundingClientRect().bottom)) {
			//ball hits bottom side of court, change speed coordinates
			vy=-vy;
		}
		else if (ball.getBoundingClientRect().right >= paddle.getBoundingClientRect().left) {
			
			if(ball.getBoundingClientRect().top >= paddle.getBoundingClientRect().top && ball.getBoundingClientRect().bottom <= paddle.getBoundingClientRect().bottom) {
				//hits paddle on the right side of the court, change speed coordinates
				vx=-vx;
				strikes_count += 1;
				strike_span.textContent = strikes_count;
				
			}
			else if(ball.getBoundingClientRect().right > paddle.getBoundingClientRect().left) {
				// paddle misses, balls hits right side of court. so we reset the game
				resetGame();
			}
		}	
	}, 60);
}

function setSpeed(speed) {
	ballSpeed = speed + 1;
}

function resetGame() {
	clearInterval(setInterval_ID); // to stop the ball from moving
	if(strikes_count>max_score) {
		score_span.textContent = strikes_count;
		max_score = strikes_count;
	}
	strikes_count = 0;
	strike_span.textContent = strikes_count;			
	for(var i = 0; i < speed_options.length; i++) {
		if(speed_options[i].value == "0")
			speed_options[i].checked = true;
		else
			speed_options[i].checked = false;
	}
	setSpeed(0); // reset speed to slow as default
	var left = 0; // ball always starts from left side of court
	// select random position from the top of the court to position the ball
	// it can range from court.getBoundingClientRect().top to court.getBoundingClientRect().height
	var top = (Math.random() * (offsetBallMax - offsetBallMin + 1) + offsetBallMin);
	ball.style.left = left + 'px'; //set the ball's left attribute
	ball.style.top = top + 'px'; // set the ball's top attribute	
}

function movePaddle(event) {

	if(event.pageY <= (court.getBoundingClientRect().top + (paddle.offsetHeight/2))) {
		//hits top side of the court
		//paddle.style.bottom = (court.getBoundingClientRect().top - paddle.offsetHeight) + 'px';
		paddle.style.top = court.getBoundingClientRect().top;
	}	
	else if(event.pageY >= (court.getBoundingClientRect().bottom - (paddle.offsetHeight/2))) {
		//hits bottom side of the court
		paddle.style.top = (court.offsetHeight - paddle.offsetHeight) + 'px';
	}	
	else if(event.pageY > (court.getBoundingClientRect().top + (paddle.height/2)) && event.pageY < (court.getBoundingClientRect().bottom - (paddle.height/2))) {
		//moves within the boundaries of the court
		paddle.style.top = (event.pageY - offsetPaddle) + 'px';
	}
}