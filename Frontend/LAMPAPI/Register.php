<?php
	$inData = getRequestInfo();

    $login = $inData["login"];
    $password = $inData["password"];
	$name = $inData["name"];

	$conn = new mysqli("localhost", "Admin", "Admin", "yellabook");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("INSERT into users (name, login, password) VALUES(?,?,?)");
		$stmt->bind_param("sss", $name, $login, $password);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithInfo( $name, $login);
		returnWithError("");


	}

	function returnWithInfo( $name, $login )
	{
		$retValue = '{"name":"' . $name . '","login":"' . $login . '","error":""}';
		sendResultInfoAsJson( $retValue );
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