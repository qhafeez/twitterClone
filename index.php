<?php 

	include("functions.php");

	include("views/header.php");

	if($_GET['page'] === "timeline"){

		include("views/timeline.php");

	} else if($_GET['page'] === "yourTweets") {

		include("views/yourTweets.php");

	} else if($_GET['page'] === "search"){

		include("views/search.php");

	} else if($_GET['page'] === "publicProfiles"){

		include("views/publicProfiles.php");

	} else{

		include("views/home.php");

	}

	

	include("views/footer.php");

?>