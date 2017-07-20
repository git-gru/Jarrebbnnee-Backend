<?php
include 'db.php';
error_reporting(0);
if(isset($_REQUEST['email']))
{ 
		$email = $_REQUEST['email'];			
	 
	 $qry_select = "select * from users where `u_email` ='".$email."'";
	 if($qry_select)
	 {
		 $res_select = mysql_query($qry_select);
		 if(mysql_num_rows($res_select) > 0)
		 {
			 $row = mysql_fetch_array($res_select);
			 //print_r($row);
			 $msg['status'] = "1";
                         $msg['userid'] = $row['u_id'];
                         $msg['message'] = "mail send success..";
				
				$pass =  rand(10000000,100000000);
				$rapass = md5($pass);
				
				$qry1 = "UPDATE `users` SET `u_password`= '$rapass' WHERE `u_id` = '".$row['id']."'";
				$res1 = mysql_query($qry1);
				
				/*start eamail varification */
				$username = $row['u_user_name'];
				$to  = $email;
					$subject = 'Password Reset ';
					$message = '
					<html>
					<head>
					  <title>Password reset</title>
					</head>
					<body>
					<table>
						<tr>
						  <th>Username : </th>
						  <td>'.$email .'</td>						 
						</tr>
						<tr>
							 <th>Password : </th>
						  	<td>'.$pass.'</td>
						</tr>
					  </table>
					</body>
					</html>
					';
					
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Admin Salespot <info@joomlavogueteamdemo.com>' . "\r\n";
					mail($to, $subject, $message, $headers);
				
				
		 }		 
		 else
		 {
			 $msg['userid']="0";
		         $msg['message'] = mysql_error(); 		  
		 }
		 
	 }
	 else
	 {
	$msg['userid']="0";
         $msg['message'] = mysql_error(); 
	 }
	
}
else{
    $msg['message'] = "wrong or missing parameters!";
}
$ar['status'] = $msg;
print json_encode($ar);
?>