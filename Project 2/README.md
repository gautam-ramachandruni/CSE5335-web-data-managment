Along with XAMPP web server, the Web Service REST API of the music application Last.fm is used. Firstly, an API access key needs to be retrieved from Last.fm. The access key will allow the user to send web service requests to Last.fm (maximum 1 request per second).

The project is to develop a web application to get information about music artists, their albums, etc. This application is developed using plain JavaScript and Ajax. The Ajax requests return JSON, not XML. Note that everything is done asynchronously and the web page is never redrawn/refreshed completely. This means that the buttons or any other input element in the HTML forms have JavaScript actions, and are not regular HTTP requests.

The following Last.fm methods were used:

•	Get the metadata for an artist, including biography

•	Get the top albums for an artist

•	Get all the artists similar to this artist

It can be assumed that the person who uses this application will type the correct complete name of the artist. So it isn't necessary to check for errors. For example, if someone types "Beatles" instead of "The Beatles", it will be an error, but there is no need to check for such errors.
