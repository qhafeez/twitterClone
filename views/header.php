<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="styles.css" rel="stylesheet">
  </head>
  <body>

  	<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="http://qhafeezdomain.dreamhosters.com/projects/twitter/">Twitter</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        
        <?php if (isset($_SESSION['id'])) { ?>

          <li class="nav-item">
            <a class="nav-link" href="?page=timeline">Your Timeline</a>
          </li>


          <li class="nav-item">
            <a class="nav-link " href="?page=yourTweets">Your Tweets</a>
          </li>

      <?  } ?>
      
      <li class="nav-item">
        <a class="nav-link " href="?page=publicProfiles">Public Profiles</a>
      </li>
    </ul>
    <div class="form-inline my-2 my-lg-0">

      <?php if ($_SESSION["id"]) { ?>
      
         <a id="logout" class="btn btn-outline-success my-2 my-sm-0" href="actions.php?logout">Logout</a>     

      <?php } else { ?>
      <button class="btn btn-outline-success my-2 my-sm-0" data-target="#myModal" data-toggle="modal" >Login/Signup</button>

      <?php } ?>
    </div>
  </div>
</nav>