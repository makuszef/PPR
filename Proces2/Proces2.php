#!/usr/bin/php

<?php
	try {
		//throw new Exception("This is an example exception message.");
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
		echo "Blokada";
		while ($chunk = socket_read($client_socket, 1024)) {
			$image_data .= $chunk;
		}
		echo "Wyjscie";
		// Process the image data (here you can save it to a file, perform image processing, etc.)
		// For demonstration, let's just echo the size of the received image
		$image_size = strlen($image_data);
		echo "Received image size: $image_size bytes\n";
		$imageBase64 = base64_encode($image_data);

		socket_close($client_socket);
		// Close the server socket
		socket_close($socket);


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

		// Check for errors
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
		$filePath = "exception_log_" . $currentDate;
		$exceptionMessage = "Exception occurred at: " . $currentDate . PHP_EOL;
		$exceptionMessage .= "Exception message: " . $ex->getMessage() . PHP_EOL;
		$exceptionMessage .= "Stack trace: " . $ex->getTraceAsString() . PHP_EOL;
		file_put_contents($filePath, $exceptionMessage);
		echo "Exception message written to: " . $filePath;
	}
?>
