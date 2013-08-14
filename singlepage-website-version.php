<?php
  
	  //response generation function
	
	  $response = "";
	
	  //function to generate response
	  function generate_response($type, $message){
		
		global $response;
	
		if($type == "success") $response = "<div class='success'>{$message}</div>";
		else $response = "<div class='error'>{$message}</div>";
		
	  }
	
	  //response messages
	  $not_human       = "Human verification incorrect.";
	  $missing_content = "Please supply all information.";
	  $email_invalid   = "Email Address Invalid.";
	  $message_unsent  = "Message was not sent. Try Again.";
	  $message_sent    = "Thanks! Your message has been sent.";
	
	  //user posted variables
	  $name = $_POST['message_name'];
	  $email = $_POST['message_email'];
	  $message = $_POST['message_text'];
	  $human = $_POST['message_human'];
	  $sendCopy = trim($_POST['sendCopy']);
	
	  //php mailer variables
	  $to = get_option('admin_email');
	  $subject = "Someone sent a message from " . get_bloginfo('name');
	  $headers = 'From: '. $email . "\r\n" .
				 'Reply-To: ' . $email . "\r\n";
			
			//Send user a copy check box	 
			if($sendCopy == true) {
				$subject = "A copy of your message from " . get_bloginfo('name');
				$headers = 'From: ' . $email ;
				mail($email, $subject, $message, $headers);
			}
	  
	  
	  if(!$human == 0){
		if($human != 2) generate_response("error", $not_human); //not human!
		else {
		  
		  //validate email
		  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			generate_response("error", $email_invalid);
		  else //email is valid
		  {
			//validate presence of name and message
			if(empty($name) || empty($message)){
			  generate_response("error", $missing_content);
			}
			else //ready to go!
			{
			  $sent = mail($to, $subject, $message, $headers);
			  if($sent) generate_response("success", $message_sent); //message sent!
			  else generate_response("error", $message_unsent); //message wasn't sent
			}
		  }
		}
	  } 
	  else if ($_POST['submitted']) generate_response("error", $missing_content);
	
	?>
	
	
<style> 
/* Include some styles add to your header or css */
#contact-form {
  width: 100%;
}
.left {
    float: left;
    width: 35%;
}
.right {
    float: right;
    width: 65%;
}
.right textarea {
	min-height: 200px;
	width: 100%;
	resize: vertical;
}

/* Respond Message */
.error{
  padding: 5px 9px;
  border: 1px solid red;
  color: red;
  border-radius: 3px;
}

.success{
  padding: 5px 9px;
  border: 1px solid green;
  color: green;
  border-radius: 3px;
}
</style>

      <!-- Now for the form section -->
              <div id="contact-form">
                <?php echo $response; ?>
                <form action="#contact-form" method="post"><!-- Refreshes the page to the #contact-form id on submit -->
                  <div class="left"><!-- Used for positioning -->
                  <p><label for="name">Name: <span>*</span> <br><input type="text" name="message_name" value="<?php echo $_POST['message_name']; ?>"></label></p>
                  <p><label for="message_email">Email: <span>*</span> <br><input type="text" name="message_email" value="<?php echo $_POST['message_email']; ?>"></label></p>
                  </div>
                  <div class="right"><!-- Used for positioning -->
                  <p><label for="message_text">Message: <span>*</span> <br><textarea type="text" name="message_text"><?php echo $_POST['message_text']; ?></textarea></label></p>
                  </div>
                  <p><input type="checkbox" name="sendCopy" id="sendCopy" value="true"<?php if(isset($_POST['sendCopy']) && $_POST['sendCopy'] == true) echo ' checked="checked"'; ?> /><label for="sendCopy">Send a copy of this email to yourself</label></p>
                  <p><label for="message_human">Human Verification: <span>*</span> <br><input type="text" style="width: 60px;" name="message_human"> + 3 = 5</label></p>
                  <input type="hidden" name="submitted" value="1">
                  <p><input type="submit"></p>
                </form>
              </div> <!-- /#contact-form
