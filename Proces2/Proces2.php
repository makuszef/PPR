#!/usr/bin/php

<?php
	function saveToFile($fileName) {
		$tempFilePath = "historia.txt";
		$currentDate = date('Y-m-d H:i:s');
		$Message = "Filename: " . $fileName . PHP_EOL;
		$Message .= "Date: " . $currentDate . PHP_EOL;
		file_put_contents($tempFilePath, $Message, FILE_APPEND);
	}
	function getMessUdp() {
		//GET UDP data
		$UDPserverIP = "127.0.0.1"; // Replace with your server's IP address
		$UDPserverPort = 14001;      // Replace with your desired port number
		$UDPsocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if (!$UDPsocket) {
			echo "Error: Unable to create socket\n";
			exit;
		}
		if (!socket_bind($UDPsocket, $UDPserverIP, $UDPserverPort)) {
			echo "Error: Unable to bind socket to $UDPserverIP:$UDPserverPort\n";
			exit;
		}
		echo "Server listening on $UDPserverIP:$UDPserverPort\n";
		$clientIP = "";
		$clientPort = 0;
		$data = "";
		socket_recvfrom($UDPsocket, $data, 1024, 0, $clientIP, $clientPort);
		echo "Received from $clientIP:$clientPort: $data\n";
		saveToFile($data);
		socket_close($UDPsocket);
	}
	function getMessTCP() {
		# zmienne predefiniowane -------------------------------------------
		$host = "127.0.0.1";
		$port = 13001;
		// Create a TCP/IP socket
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket === false) {
			echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
			exit;
		}

		// Bind the socket to the address and port
		$result = socket_bind($socket, $host, $port);
		if ($result === false) {
			echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
			exit;
		}

		// Start listening for connections
		$result = socket_listen($socket, 5);
		if ($result === false) {
			echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
			exit;
		}

		echo "Server started on $host:$port\n";

		$client_socket = socket_accept($socket);
		if ($client_socket === false) {
			echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
		}

		// Read image data from the client
		$image_data = '';
		while ($chunk = socket_read($client_socket, 1024)) {
			$image_data .= $chunk;
		}
		// Process the image data (here you can save it to a file, perform image processing, etc.)
		// For demonstration, let's just echo the size of the received image
		$image_size = strlen($image_data);
		echo "Received image size: $image_size bytes\n";

		$imageBase64 = base64_encode($image_data);

		socket_close($client_socket);
		// Close the server socket
		socket_close($socket);
		return $imageBase64;
	}
	try {

		//throw new Exception("This is an example exception message.");
		$imageBase64 = getMessTCP();
		getMessUdp();

		//wyslij do 3 procesu
		// Include the XML-RPC client library
		$port 	= 8000;
		$host 	= '127.0.0.1';
		// XML-RPC server information
		$server_url = "http://$host:$port/RPC2"; // URL of the XML-RPC server
		$method_name = 'handleImage'; // Name of the method you want to call

		// Parameters for the method (if any)
		$parameters = array($imageBase64);

		// Prepare the XML-RPC request body
		$request_body = xmlrpc_encode_request($method_name, $parameters);

		// Set up the HTTP headers
		$headers = array(
			'Content-Type: text/xml',
			'Content-Length: ' . strlen($request_body),
		);

		// Set up the cURL session
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $server_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the request and get the response
		$response = curl_exec($ch);
		if($response === false) {
			echo 'Error: ' . curl_error($ch);
		} else {
			// Decode the XML-RPC response
			$decoded_response = xmlrpc_decode($response);
			// Check for errors in decoding
			if ($decoded_response === null && xmlrpc_error()) {
				echo 'Error decoding response: ' . xmlrpc_error_string();
			} else {
				echo 'Type of returned value: ' . gettype($decoded_response);
				// Display the response
				var_dump($decoded_response);
			}
		}
		// Close the cURL session
		curl_close($ch);
	}
	catch (Exception $ex) {
		$currentDate = date('Y-m-d H:i:s');
		$filePath = "exception_log" . ".txt";
		$exceptionMessage = "Exception occurred at: " . $currentDate . PHP_EOL;
		$exceptionMessage .= "Exception message: " . $ex->getMessage() . PHP_EOL;
		$exceptionMessage .= "Stack trace: " . $ex->getTraceAsString() . PHP_EOL;
		file_put_contents($filePath, $exceptionMessage, FILE_APPEND);
		echo "Exception message written to: " . $filePath;
	}
?>
