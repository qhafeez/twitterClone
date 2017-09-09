<footer class="footer">
	<div class="container">

		<p>&copy; My Twitter App 2017 </p>
	
	</div>
</footer>

<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


    <!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="loginModalTitle">Login</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
					
						<div id="loginAlert" class="alert alert-danger" ></div>
					
		      		<form >
		      			<input type="hidden" name="loginActive" id="loginActive" value="1"/>
					  <div class="form-group">
					    <label for="email">Email address</label>
					    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email address">
					    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
					  </div>
					  <div class="form-group">
					    <label for="password">Password</label>
					    <input type="password" class="form-control" id="password" placeholder="Password">
					  </div>
					 
					  
					</form>


   		      </div>
   		       <div class="modal-footer">
        	<a href="#" id="toggeLogin">Sign Up?</a>
        <button id="loginSignUpButton" type="button" class="btn btn-primary">Login</button>
      </div>

		    
		    </div>
		  </div>
		</div>
		

		<script>

			$("#toggeLogin").click(function(){
				if($("#loginActive").val()==="1"){

					$("#loginActive").val("0");
					$("#loginModalTitle").html("Sign Up");
					$("#loginSignUpButton").html("Sign Up!");
					$("#toggeLogin").html("Login?");


				} else{

					$("#loginActive").val("1");
					$("#loginModalTitle").html("Log In");
					$("#loginSignUpButton").html("Log In");
					$("#toggeLogin").html("Sign Up?");

				}
			});

			$("#loginSignUpButton").click(function(){

				$.ajax({
					type:"POST",
					url: "actions.php?action=loginSignUp",
					data: "email=" + $("#email").val() + "&password=" + $("#password").val() +
							"&loginActive=" + $("#loginActive").val(),
					success: function(result){
						if (result === "1"){
							window.location.assign("http://qhafeezdomain.dreamhosters.com/projects/twitter/")
						} else {

							$("#loginAlert").html(result).show();

						}
					}	
				})


			});

			$("#loginSignUpButton").click(function(){

				$.ajax({
					type:"POST",
					url: "actions.php?action=loginSignUp",
					data: "email=" + $("#email").val() + "&password=" + $("#password").val() +
							"&loginActive=" + $("#loginActive").val(),
					success: function(result){
						if (result === "1"){
							window.location.assign("http://qhafeezdomain.dreamhosters.com/projects/twitter/")
						} else {

							$("#loginAlert").html(result).show();

						}
					}	
				})


			});

			$("#logout").click(function(e){
				e.preventDefault();
				$.ajax({

					type:"POST",
					url:"actions.php?action=logout",
					success: function(result){
						if (result==="1"){
							window.location.assign("http://qhafeezdomain.dreamhosters.com/projects/twitter/");
						}
					}

				})


			});

			$(".toggleFollow").click(function(){
				
				console.log($(this));	
				var id = $(this).attr("data-userid");


				$.ajax({
					context:this,
					type:"POST",
					url: "actions.php?action=toggleFollow",
					data: "userId=" + id,
					success: function(result){
						
						if(result === "1"){
							$("a[data-userid='"+ id + "']").html("Follow");
						} else if(result === "2") {
							$("a[data-userid='"+ id + "']").html("Unfollow");
						} else if (result ==="3"){
							$('body').append("<div id='overlay'>"+result+"</div>");
						}
					},

					
				})


			});

			$("#postTweetButton").click(function(){

				var $tweet = $("#tweetContent");


				$.ajax({
					type:"POST",
					url: "actions.php?action=postTweet",
					data: "tweetContent=" + $tweet.val(),
					success: function(result){
						
						if(result === "1"){
						
							$("#tweetSuccess").show();
							$("#tweetFail").hide();
							$tweet.val("");
						
						} else if(result != ""){

							$("#tweetFail").html(result).show();
							$("#tweetSuccess").hide();

						}

					},

				})

			});




		</script>
  </body>
</html>