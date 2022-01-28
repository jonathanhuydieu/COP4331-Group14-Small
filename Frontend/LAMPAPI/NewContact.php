<?php
	$inData = getRequestInfo();
	$ret = "";
	$login = $inData["login"];
	$cname = $inData["cname"];

    $etype = $inData["etype"];
    $eaddress = $inData["eaddress"];

    $ptype = $inData["ptype"];
    $paddress = $inData["paddress"];

    $ltype = $inData["ltype"];
    $laddress = $inData["laddress"];

	$conn = new mysqli("localhost", "Admin", "Admin", "yellabook");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("INSERT into contacts (login,name) VALUES(?,?)");
		$stmt->bind_param("ss", $login, $cname);
		$stmt->execute();
        $stmt->close();
      
		
		// Emails
		//length
        if (strcmp($eaddress,"") != 0)
        {
            $stmt = $conn->prepare("INSERT into emails (login,name,type,address) VALUES(?,?,?,?)");
            $stmt->bind_param("ssss", $login, $cname, $etype, $eaddress);
			$ret = $eaddress;
	    	$stmt->execute();
	    	$stmt->close();
        }
		
    	// Phones
        if (strcmp($paddress,"") != 0)
        {
            $stmt = $conn->prepare("INSERT into phones (login,name,type,number) VALUES(?,?,?,?)");
            $stmt->bind_param("sssi", $login, $cname, $ptype, $paddress);
	        $stmt->execute();
	        $stmt->close();
        }
		
        // Locations
        if (strcmp($laddress,"") != 0)
        {
            $stmt = $conn->prepare("INSERT into locations (login,name,type,address) VALUES(?,?,?,?)");
            $stmt->bind_param("ssss", $login, $cname, $ltype, $laddress);
	    	$stmt->execute();
	    	$stmt->close();
        }
        
		$conn->close();
		returnWithError($ret);
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>