#!/usr/bin/php

<?php
/*
	# zmienne predefiniowane -------------------------------------------
	$host = "127.0.0.1";
	$port = 13000;

	# tworzymy gniazdo -------------------------------------------------
	if( ! ( $server = stream_socket_server( "tcp://$host:$port", $errno, $errstr ) ) ){
	  print "stream_socket_server(): $errstr\n";
	  exit( 1 );
	}

	# obslugujemy kolejnych klientow, jak tylko sie podlacza -----------
    $client = stream_socket_accept( $server );
    # wyswietlamy informacje o klientach - - - - - - - - - - - - - -
    $str = stream_socket_get_name( $client, 1 );
    list( $addr, $port ) = explode( ':', $str );

    print "Addres: $addr Port: $port\n";
    fclose( $client );
    fclose( $server );
    //koniec obslugi klienta
*/
    //wyslij do 3 procesu
    // Include the XML-RPC client library
	$port 	= 8000;
	$host 	= '127.0.0.1';
	#-------------------------------------------------------------------
    $params = array(5, 3);
	$req = xmlrpc_encode_request(
		"add_numbers",
		$params
	);
	#-------------------------------------------------------------------
	$ctx = stream_context_create(
		array(
			'http' => array(
				'method' 	=> "POST",
				'header' 	=> array( "Content-Type: text/xml" ),
				'content' 	=> $req
			)
		)
	);
	#-------------------------------------------------------------------
	$xml = file_get_contents( "http://$host:$port/RPC2", false, $ctx );
	#-------------------------------------------------------------------
	$res = xmlrpc_decode( $xml );
	#-------------------------------------------------------------------
	if( $res && xmlrpc_is_fault( $res ) ){
		print "xmlrpc: $res[faultString] ($res[faultCode])";
		exit( 1 );
	} else {
		print_r( $res );
	}
	#===================================================================
	#-------------------------------------------------------------------

	#===================================================================
?>
