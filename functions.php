<?php
session_start();


try{
	$db = new PDO("mysql:host=mysql.qhafeezdomain.dreamhosters.com;dbname=qh_twitter;port=3306","qhafeezdomaindre","3XLjSbBZ");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
} catch(Exception $e) {
	echo $e->getMessage();
	exit;
}


// if($_GET['function'] ==="logout"){
// 	session_unset();
// }



//checks to see if email is already in database
//returns whole row including password
function checkUser($email){

	global $db;

	try{
		$result = $db->prepare("SELECT * FROM users WHERE email  = ?");
		$result->bindValue(1, $email, PDO::PARAM_STR);
		$result->execute();
		
		$count = $result->fetchAll(PDO::FETCH_ASSOC);
		return $count;

	} catch(Exception $e){
		echo "Error!:" . $e->getMessage() . "</br>";
		false;
	}
	
}



//inserts new user's email and password into database
function signUserUp($email, $password){

	global $db;

	$password = password_hash($password, PASSWORD_DEFAULT);

	try{

		$result = $db->prepare("INSERT INTO users(email, password) VALUES (?, ?)");
		$result->bindValue(1, $email, PDO::PARAM_STR);
		$result->bindValue(2, $password, PDO::PARAM_STR);
		$result->execute();
		
		return true;

	} catch(Exception $e){

		echo "Error!:" . $e->getMessage() . "</br>";
		return false;

	}
}


//logs the user in and creates the session id
function userLogin($email, $password){
	global $db;

	try{
		$checkUser = ("SELECT * FROM users WHERE email = ?");
		
		$result = $db->prepare($checkUser);
		$result->bindParam(1, $email, PDO::PARAM_STR);
		
		$result->execute();
		
		if($result->rowCount()>0){
			
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
						
			if(password_verify($password, $row['password'])){
				 $_SESSION['id'] = $row['id'];
				return true;
				
			} else {
				
				return false;
			}
		} else{
			
			return false;
		}
		
	} catch(Exception $e){
		echo $e->getMessage();
		return false;
	}
}




function whereClause(){

	global $db;

	

}


/*function that displays tweets.  $type is the type of tweet (public,
 tweets of users the current user is following, their own tweets, etc).  The $type
variable determines the where clause*/
function displayTweets($type){

	global $db;

	if($type === "public") {
		
		$whereClause ="";
	
	} else if ($type === "isFollowing"){

			$session = $_SESSION['id'];
			$whereClause="";

		try{
			$query = "SELECT * FROM isFollowing WHERE follower = $session";
			$result= $db->prepare($query);
			$result->execute();

			while ($row = $result->fetch(PDO::FETCH_ASSOC)){

				if($whereClause===""){
					$whereClause = " WHERE ";
				} else {
					$whereClause.= " OR";
				} $whereClause.= " user_id=". $row["isFollowing"];

			}

		} catch(Exception $e){
			echo $e->getMessage();
		}



	} else if ($type === "yourTweets"){
		$session = $_SESSION['id'];

		$whereClause = "WHERE user_id = $session";

	} else if($type === "search"){

		$searchTerm = $_GET['q'];

		echo "<p>Showing results for '$searchTerm'</p>";

		$whereClause = "WHERE tweet LIKE '%$searchTerm%'";

	} else if(is_numeric($type)){

			try{
				$query = ("SELECT * FROM users WHERE id = ? LIMIT 1");
				$result = $db->prepare($query);
				$result->bindValue(1, $type, PDO::PARAM_INT);
				$result->execute();
				$row = $result->fetch(PDO::FETCH_ASSOC);

				echo "<h2>Tweets by " . $row['email'] . "</h2>";

			} catch(Exception $e){

			}



		$whereClause = "WHERE user_id = $type";

	}

	try{

		$query = ("SELECT * FROM tweets ". $whereClause . " ORDER BY datetime DESC LIMIT 10");
		$result = $db->prepare($query);
		// $result->bindValue(1, $whereClause, PDO::PARAM_STR);
		$result->execute();
		$tweets = $result->fetchAll(PDO::FETCH_ASSOC);
		// var_dump($tweets);
		
		if(count($tweets) === 0){

			echo "There are no tweets to display";

		} else {

			foreach($tweets as $tweet){

				$id = $tweet["user_id"];				

				$userQuery = "SELECT * FROM users WHERE id = ? LIMIT 1";
				$userResult = $db->prepare($userQuery);
				$userResult->bindValue(1, $id, PDO::PARAM_STR);
				$userResult->execute();
				$user = $userResult->fetch(PDO::FETCH_ASSOC);

				echo "<div class='tweet'>";

				echo "<p><a href='?page=publicProfiles&userid=" . $user["id"]. "'>".$user['email']. "</a> <span class='time'>". time_since(time() - strtotime($tweet['datetime'])) .  " ago</span></p>";

				echo "<p>" . $tweet["tweet"] . "</p>";

				if($type != "yourTweets"){

					echo "<p><a href='#' class='toggleFollow' data-userId=$id>";
				
				



						try{

							$follower = $_SESSION['id'];

						$query = "SELECT * FROM isFollowing WHERE follower = ? AND isFollowing = ? LIMIT 1";
						$result=$db->prepare($query);
						$result->bindParam(1, $follower, PDO::PARAM_STR);
						$result->bindParam(2, $id, PDO::PARAM_STR);
						$result->execute();
							if($result->fetch(PDO::FETCH_ASSOC)){
								echo "Unfollow";
							} else{
								echo"Follow";
							}
						
						} catch(Exception $e){

							echo $e->getMessage();

						}



				echo "</a></p>";

			}

			echo "</div>";

			}

		}


	} catch(Exception $e){
		echo $e->getMessage();
		return false;
	}



}







function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'min'),
        array(1 , 'sec')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print;
}


function displaySearch(){

	echo '
			<form class="form-inline">
  <div class="form-group">	
  <input type="hidden" name="page" value="search">
  <input type="text" class="form-control" name="q" id="search" placeholder="Search">
</div>
  
  <button type="submit" class="btn btn-primary">Search Tweets</button>
</form>

	';

}

function displayTweetBox(){

	if($_SESSION['id'] > 0){

		echo '<div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully.</div>
				<div id="tweetFail" class="alert alert-danger"></div>
			<div class="tweetBox">
  <div class="form-group">	
  <textarea class="form-control " id="tweetContent" placeholder="Post Tweet"></textarea>
</div>
  
  <button  id="postTweetButton" class="btn btn-primary">Post Tweet</button>
</div>

	';

	}


}

//check following script starts here

function checkFollowing($postUserId){

	if(isset($_SESSION['id'])){
		$follower = $_SESSION['id'];


		global $db;

		try{
			$query = "SELECT * FROM isFollowing WHERE follower = ? AND isFollowing = ? LIMIT 1";
			$result=$db->prepare($query);
			$result->bindParam(1, $follower, PDO::PARAM_STR);
			$result->bindParam(2, $postUserId, PDO::PARAM_STR);
			$result->execute();
			$count = $result->fetch(PDO::FETCH_ASSOC);
			
			
			if($count) {
					$aaa = $count['id'];

						$deleteQuery = "DELETE FROM isFollowing WHERE id = $aaa LIMIT 1";
						$results = $db->prepare($deleteQuery);
						$results->execute();
						echo "1";
				}  else {
						$insertQuery = "INSERT INTO isFollowing(follower, isFollowing) VALUES($follower, $postUserId)";
						$results = $db->prepare($insertQuery);
						$results->execute();
						echo "2";
					}


		} catch(Exception $e){

			echo $e->getMessage();

		}
	} else {
		echo "3";
	}


}
//check following script ends here

function postTweet($tweet, $user_id){

	global $db;
	
	try{
		$query = ("INSERT INTO tweets(tweet, user_id, datetime) VALUES(?, ?, NOW())");
		$result=$db->prepare($query);
		$result->bindParam(1, $tweet, PDO::PARAM_STR);
		$result->bindParam(2, $user_id, PDO::PARAM_STR);
		$result->execute();
		echo 1;

	} catch(Exception $e){

		echo $e->getMessage();
	
	}

}

function displayUsers(){

	global $db;

	try{

		$query = ("SELECT * FROM users LIMIT 10");
		$result = $db->prepare($query);
		$result->execute();
		while($row = $result->fetch(PDO::FETCH_ASSOC)){

			

			echo "<p><a href='?page=publicProfiles&userid=".$row["id"]."'>".$row["email"]."</a></p>";

		}
	
	} catch(Exception $e){



	}



}





?>