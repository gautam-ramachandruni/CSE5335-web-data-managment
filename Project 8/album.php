<!-- Gautam Ramachandruni -->
<?php

// display all errors on the browser
error_reporting(E_ALL);
ini_set('display_errors','On');

require_once 'demo-lib.php';
demo_init(); // this just enables nicer output

// if there are many files in your Dropbox it can take some time, so disable the max. execution time
set_time_limit(0);

require_once 'DropboxClient.php';

/** you have to create an app at @see https://www.dropbox.com/developers/apps and enter details below: */
/** @noinspection SpellCheckingInspection */
$dropbox = new DropboxClient( array(
	'app_key' => "hxes27ltnx2gedb",      // Put your Dropbox API key here
	'app_secret' => "ru7ckaqk3c5g8i2",   // Put your Dropbox API secret here
	'app_full_access' => false,
) );

/**
 * Dropbox will redirect the user here
 * @var string $return_url
 */
$return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";

// first, try to load existing access token
$bearer_token = demo_token_load( "bearer" );

if($bearer_token) {
	$dropbox->SetBearerToken( $bearer_token );
	//echo "loaded bearer token: " . json_encode( $bearer_token, JSON_PRETTY_PRINT ) . "\n";
} elseif(!empty($_GET['auth_redirect'])) // are we coming from dropbox's auth page? 
{
	// get & store bearer token
	$bearer_token = $dropbox->GetBearerToken( null, $return_url );
	demo_store_token( $bearer_token, "bearer" );
} elseif(!$dropbox->IsAuthorized()) {
	// redirect user to Dropbox auth page
	$auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
	die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
}

if (empty( $_FILES["userfile"])) {
?>
<form enctype="multipart/form-data" method="POST" action="">
<b>Upload File: <br></b>
<input name="userfile" type="file" /> <br/>
<input type="submit" value="Upload File" />
<?php
} else {
	$uploaded_file = $_FILES["userfile"]["name"];
	echo "\n\n<b>Uploading $uploaded_file:</b>\r\n";
	$meta = $dropbox->UploadFile( $_FILES["userfile"]["tmp_name"], $uploaded_file );
}

$files = $dropbox->GetFiles("", false);

echo "\n\n<t><b>Files in DropBox:</b>\n";

foreach($files as $file) {
	echo ($file->name."\n");
?>
	<a href="album.php?download=<?php echo $file->content_hash; ?>">Download</a>
	<input type='submit' name='deleteBtn' formaction="album.php?delete=<?php echo $file->content_hash;?>" value="Delete File"/>
	</form>
<?php
}

/////// DOWNLOAD ///////

if(isset($_GET['download'])) {
	$fileToDownload = $_GET['download'];
	foreach($files as $fdwn) {
		if ($fdwn->content_hash === $fileToDownload) {
			$test_file = "test_download_" . basename($fdwn->name);
			//$dropbox->DownloadFile($fdwn->name, $test_file);
			$image_link = ($dropbox->GetLink( $fdwn->path, false ));
			echo "<div id=\"image_section\">";
			echo "\n<b>Downloaded Image: </b>\n\n";
			echo "<img src=\"$image_link\" alt=\"Image goes here\" width=\"50%\" height=\"50%\"/></br>";
			unset($_GET['download']);
			$fdwn = null;
		}
	}
}

/////// DELETE ///////

if(isset($_GET['delete'])) {
	$fileToDelete = $_GET['delete'];
	foreach($files as $fdel) {
		if ($fdel->content_hash === $fileToDelete) {
			$dropbox->Delete($fdel);
			unset($_GET['delete']);
			$fdel=null;
		}
	}
}

?>

<form action="album.php" method="POST">
<button type="submit" name="refresh">Refresh</button>
</form>

<?php

?>