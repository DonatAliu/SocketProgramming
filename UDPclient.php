<?php



//Reduce errors
error_reporting(~E_WARNING);

//server destination ip
$server = "192.168.178.39";
//server port
$port = 9999;
//handling errors if they accrue during the creation of the socket
if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);

    die("Couldn't create socket: [$errorcode] $errormsg \n");
}

echo "Socket created \n";

//Communication loop
while(true)
{
	//Take some input to send
	echo 'Enter the command you want to send:'; //
	$input = fgets(STDIN);
	$input=strtolower($input);
	//encoding input
	$msg=utf8_encode($input);
	
	//Send the command to the server
	if( ! socket_sendto($sock, $msg , strlen($msg) , 0 , $server , $port))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		
		die("Could not send data: [$errorcode] $errormsg \n");
	}
		
	//Now receive reply from server and print it
	if(socket_recv ( $sock , $reply , 2045 , 0) === FALSE)
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		
		die("Could not receive data: [$errorcode] $errormsg \n");
	}
	
	echo "Reply : ".utf8_decode($reply);
}