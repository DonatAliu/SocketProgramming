<?php

//Reduce errors
error_reporting(~E_WARNING);


//Create a UDP socket
$sock = socket_create(AF_INET, SOCK_DGRAM, 0) or die("Couldn't create socket \n");
$ip="192.168.178.39";
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
    $msg=utf8_decode($buf);
    echo "$remote_ip : $remote_port -- " . $msg;
    $longHostname=gethostbyaddr($remote_ip);
    $hostname=substr($longHostname,0,15);
    $msg=operator($msg,$hostname);
        socket_sendto($sock, $msg , 512 , 0 , $remote_ip , $remote_port);
    
    
    
	//Send back the data to the client

       

        
    }
    
}
function read($filename){
  
    $file="./$filename";
    if(file_exists($file)){
  $document=file_get_contents($file);
  return $document."\n";
}
else{
    return "file doesn't exist \n";
}

  
  
}
function execute($extension="",$filename="points.txt",$hostname="1"){
    $filename=$extension." ".$filename;
    if(hasAccess($hostname)){
    try{
    $command = escapeshellcmd($filename);
    $output = shell_exec($command);
}
catch(Exception $e){
    echo $e;
}
return "executed \n";
}
 else{
     return "You do not have permission to execute files \n";
 }  

}
function write($filename="newfile.txt",$recvtxt,$hostname){
    if (hasAccess($hostname)){
        $myfile = fopen($filename,"w+") or die("Unable to open file!");
        $txt = $recvtxt;
        fwrite($myfile, $txt);
        fclose($myfile);
        return "file has been written successfully \n";
    }
    else{
        echo "You dont have access to write file \n";
    }
}
function hasAccess($hostname){
        $hostname=strtoupper($hostname);
    if($hostname =="DESKTOP-B05K7P4"){
        return true;
    }
    else
    return false;
}
function operator($msg,$hostname){
    $msgArray=explode(' ',$msg);
    if($msgArray[0]=="read"){
        $msg=read($msgArray[1]);
        return $msg;
    }
    elseif($msgArray[0]=="execute"){
       return $msg=execute($msgArray[1],$msgArray[2],$hostname);
    }
 
 
    elseif($msgArray[0]=="write"){
        $input=substr($msg,strlen($msgArray[0].$msgArray[1])+2,strlen($msg));
       $msg=write($msgArray[1],$input,$hostname);
        return $msg;
    }
    else{
        $msg="invalid command \n";
        return $msg;
    }
}

socket_close($sock);