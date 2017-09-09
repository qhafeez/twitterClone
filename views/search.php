<div class="container mainContainer">

<div class="row">
	<div class="col-md-8">
		<h2>Search Results</h2>

		<pre>

		<?php 

		var_dump($_SESSION);

		displayTweets("search")?>
	</pre>
	

	</div>
	<div class="col-md-4">
		<?php displaySearch(); ?>

		<hr>

		<?php displayTweetBox(); ?>
	</div>
</div>

	
</div>