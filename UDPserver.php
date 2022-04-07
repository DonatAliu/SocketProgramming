<?php

//Reduce errors
error_reporting(~E_WARNING);


//Create a UDP socket
$sock = socket_create(AF_INET, SOCK_DGRAM, 0) or die("Couldn't create socket \n");
$ip="127.0.0.1";
echo "Socket created \n";

// Bind the source address
socket_bind($sock, $ip , 9999) or die("Could not bind socket : \n");


echo "Socket bind OK \n";

//Do some communication, this loop can handle multiple clients

while(true)
{
	echo "Waiting for data ... \n";
	
	
    //Receive some data
	$r = socket_recvfrom($sock, $buf, 512, 0, $remote_ip, $remote_port);
	if($r){
    echo "$remote_ip : $remote_port -- " . $buf;
	
	//Send back the data to the client

        socket_sendto($sock, $buf , 512 , 0 , $remote_ip , $remote_port);
        
    }
    
}
function read(){
    echo "read";
}
function write(){
    if (hasAccess($hotsname)){
        $myfile = fopen($filename,"w+") or die("Unable to open file!");
        $txt = $recvtxt;
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    else{
        echo "You dont have access to write file";
    }
}
function hasAccess($hostname){

    if($hostname =="DESKTOP-B05K7P4"){
        return true;
    }
    else
    return false;
}

socket_close($sock);