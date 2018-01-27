<?php
session_start();
include 'database_connection.php';
include 'sendMail.php';

if(isset($_POST['get_started']))
{
	$email=$_POST['email'];
	$pass=$_POST['password'];
	$fname =$_POST['fname'];
	$lname =$_POST['lname'];

	$code=substr(md5(mt_rand()),0,15);
	$insert_query="insert into verify (fname,lname, password,email,code) values('$fname','$lname','$pass','$email','$code')";
	$insert_value = $conn->query($insert_query);

	$db_id=mysqli_insert_id($conn);

	$message = "Your Activation Code is ".$code."";
    $to=$email;
    $subject="Activation Code For Talkerscode.com";
    $from = 'your email';
    $body="Your Activation Code is $code. Please <a href=http://localhost/Projects/web-latest/web%20project/verification.php?id=$db_id&code=$code >Click here!</a> to activate your account.";
    $headers = "From:".$from;
    sendEmail($email,$body,$headers);

	echo "An Activation Code Is Sent To You Check You Emails";
}

if(isset($_GET['code']))
{
	//echo "after vereification";
	$id=$_GET['id'];
	$code=$_GET['code'];
	//echo $id." ".$code;
	//$delete="delete from verified_user where code='$code'";
	//$delete_query_execute = $conn->query($delete);
	$select="select email from verify where code='$code'";
	$select_value = $conn->query($select);
	//echo mysqli_num_rows($select_value);
	if(mysqli_num_rows($select_value)==1)
	{
		while($row=mysqli_fetch_array($select_value))
		{
			$email=$row['email'];
			//echo $email;
			//$password=$row['password'];
		}
		$insert_user="insert into verified_user(user_id,email) values('','$email')";
		$insert_query_execute = $conn->query($insert_user);


		header("Location: http://localhost/Projects/web-latest/web%20project/index.php"); /* Redirect browser */
		exit();
	}
	else
	{
		echo "You already have an account";
	}
}

?>
