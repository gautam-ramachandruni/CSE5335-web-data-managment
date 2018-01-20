# A JavaScript Game
The JavaScript file pong.js, used in the file pong.html, implements the following actions:

•	initialize: initialize the game

•	startGame: starts the game (when you click the mouse)

•	setSpeed: sets the speed to 0 (slow), 1 (medium), 2 (fast)

•	resetGame: resets the game

•	movePaddle: moves the paddle up and down, by folowing the mouse

The pong court is 800x500px, the pong ball is 20x20px, and the paddle is 102x14px. When the Start button is clicked or a left-click occurs on the court, the ball must start from a random place at the left border of the court at a random angle between -π/4 and π/4. The paddle can move up and down on the right border by just moving the mouse (without clicking the mouse). The ball bounces on the left, top, and bottom borders of the court. Every time the ball is struck by the paddle, one strike is added. 

If the ball crosses the right border (the dotted line), the game is suspended and the strikes so far becomes the score. Either the Start button or the court must be clicked to restart with a zero number of strikes. So the goal of this game is to move the paddle to protect the right border by hitting the ball.
