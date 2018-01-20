# A Message Board Using PHP and MySQL
The goal of this project is to learn server-side web programming using PHP and a relational database system (MySQL). More specifically, to create a message board where registered users can post messages.

There are two PHP scripts, login.php and board.php. The login.php script generates a form that has two text windows for username and password and a "Login" button. The board.php has a "Logout" button, a textarea to write a message, a "New Post" button, and a list of messages. The board script prints all the messages in the database as a flat list ordered by date/time (newest first, oldest last). For each posted message, it prints:

•	The message ID

•	The username and the fullname of the person who posted the message

•	The date and time when this message was posted

•	If this is a reply to a message, the ID of this message

•	The message text

•	A button "Reply" to reply to this message

From the login script, if the user enters a wrong username/password and pushes "Login", it goes to the login script again. If the user enters a correct username/password and pushes "Login", it goes to the board script. From the board script, if the user pushes "Logout", it logs out and goes to the login script. From the board script, if the user fills out the textarea and pushes the "New Post" button, it will insert the new message in the database (with null replyto attribute) and will go to the board script again. If the user fills out the textarea and pushes the "Reply" button, it will insert the message in the database - but this time the replyto value must be set, and will go to the board script again.

When logging in, the ‘Submit’ button must be clicked twice for a successful login. Messages such as the following are displayed for convenience:
	
1. “Invalid username or password, please try again!” - when username or password doesn’t match with registered users in the ‘users’ table

2. “No users found in database” - if no users are manually registered in the board.php and the ‘users’ table is empty

When displaying messages as a list, if a message has no ‘replyto’ id associated with it, then it is displayed as an empty space between the commas. In order to reply to a message, user must first type their message in the text area and then click on the ‘Reply’ button associated with the message they wish to reply to.
