<?php

// Check php sockets extention
if (!extension_loaded("sockets")) {
    throw new Exception("failed to load php 'sockets' extentions");
}

// Set the IP address and port number
$address = '127.0.0.1';
$port = 12345;
$connectionCount = 5;
$recivedDataLength = 1024;

// Create a TCP/IP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    exit();
}

// Bind the socket to the address and port
$result = socket_bind($socket, $address, $port);

if ($result === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
    exit();
}

// Start listening for incoming connections
$result = socket_listen($socket, $connectionCount);

if ($result === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
    exit();
}

echo "Server listening on $address:$port\n";

// Accept incoming connections
while (true) {
    $client_socket = socket_accept($socket);

    if ($client_socket === false) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
        break;
    }

    // Read data from the client
    $input = socket_read($client_socket, $recivedDataLength);
    print("request data : $input \n");

    // Process the received data
    $output = "Server received : " . trim($input) . "\n";

    // Send response back to the client
    socket_write($client_socket, $output, strlen($output));

    // Close the client socket
    socket_close($client_socket);
}

// Close the server socket
socket_close($socket);