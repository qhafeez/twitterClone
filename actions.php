<?php
		
	include("functions.php");


	if($_GET['action'] ==="logout"){
	session_unset();
	echo "1";
}



	///login/signup script starts here
	if ($_GET['action'] === "loginSignUp"){
		
		$error = "";

		if(!$_POST["email"]){

			$error = "an email address is required";

		} else if (!$_POST["password"]){
		
			$error = "A password is required";
		
		} else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){

			$error = "Please enter a valid email address";

		}

		if ($error != ""){
			echo $error;
			exit;
		}


		//if user is attempting to sign up
		if($_POST["loginActive"]==="0"){


			//check if email already in database
			if(count(checkUser($_POST['email']))>0){
			
				$error = "That email address is already taken.";
			
			} else{
					//sign user up if email not found in database
				if(signUserUp($_POST["email"], $_POST["password"])){

					global $db;
					$_SESSION['id'] = $db->lastInsertId();
					echo "1";

				} else {
					$error = "Couldn't create user, please try again later";
				}
			}

		} else {///if loginActive === "1"

			if(userLogin($_POST["email"], $_POST["password"])){
				
				echo "1";
			} else{
				$error = "Could not find that username/password combination";
			}

		}

		if ($error != ""){
			echo $error;
			exit;
		}

	}
		//end of login/signup script



		//follow/unfollow script starts here

	if ($_GET['action'] === "toggleFollow"){

		checkFollowing($_POST['userId']);

		

	}

	//end of follow/unfollow script


	//post tweet script starts here
	if($_GET['action'] === "postTweet"){

		$tweetContent=$_POST['tweetContent'];
		$user_id = $_SESSION['id'];

		if (!$tweetContent){

			echo "Your tweet is empty";

		} else if (strlen($tweetContent) > 140){

			echo "Your tweet is too long";

		} else {

			postTweet($tweetContent, $user_id);

		}

	}

?>