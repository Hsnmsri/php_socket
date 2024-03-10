<?php

// Check php sockets extention
if (!extension_loaded("sockets")) {
    throw new Exception("failed to load php 'sockets' extentions");
}

// Set the IP address and port number
$message = "Hello from client!";
$address = '127.0.0.1';
$port = 12345;
$recivedDataLength = 1024;

// Create a TCP/IP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    exit();
}

// Connect to the server
$result = socket_connect($socket, $address, $port);

if ($result === false) {
    echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
    exit();
}

// Send a message to the server
socket_write($socket, $message, strlen($message));

// Receive response from the server
$response = socket_read($socket, $recivedDataLength);

echo "Response from server: " . $response . "\n";

// Close the socket
socket_close($socket);