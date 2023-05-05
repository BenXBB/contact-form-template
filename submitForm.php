<?php
  	ini_set('display_errors', 'On');
    error_reporting(E_ALL);    
    
    // ----------------  Include config files e.g dbname, host, port, user, password ---------------- 
    include("config.php");
  
    header('Content-Type: application/json; charset=UTF-8');

    // ----------------  Setting the conn var with a mysqli object containing the config files ---------------- 
    $conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname, $cd_port);

    // Declaring vars from the AJAX POST request
    $firstName = $_POST['fname'];
    $lastName = $_POST['lname'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone'];
    $message = $_POST['message'];

    // ---------------- Backend form validation in case user turns of Javascript in the front end ---------------- 
    // Name and message validation
    if ($firstName === '' || $lastName === '') {
      $output['status']['code'] = "403";
      $output['status']['name'] = "Name fields cannot be empty";
      mysqli_close($conn);
      echo json_encode($output);
      exit;
    }

    // Message validation
    if ($message === '') {
      $output['status']['code'] = "403";
      $output['status']['name'] = "Message field cannot be empty";
      mysqli_close($conn);
      echo json_encode($output);
      exit;
    }

    // Phone validation
    if ($phoneNumber === '') {
      $output['status']['code'] = "403";
      $output['status']['name'] = "Phone number field cannot be empty";
      mysqli_close($conn);
      echo json_encode($output);
      exit;
    }

    // Email validation
    if ($email === '') {
      $output['status']['code'] = "403";
      $output['status']['name'] = "Email cannot be empty";
      mysqli_close($conn);
      echo json_encode($output);
      exit;
    } else {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $output['status']['code'] = "403";
      $output['status']['name'] = "Email format invalid";
      mysqli_close($conn);
      echo json_encode($output);
      exit;
      }
    }

    // ----------------  Setting connection/database error response first before any process ---------------- 
    if (mysqli_connect_errno()) {
      
      $output['status']['code'] = "300";
      $output['status']['name'] = "failure";
      $output['status']['description'] = "database unavailable";
      $output['data'] = [];
  
      mysqli_close($conn);
  
      echo json_encode($output);
  
      exit;
    }
    
    // ----------------  Setting prepared query ---------------- 
    $query = $conn->prepare('INSERT INTO contactform (firstName, lastName, email, phone, message) VALUES(?,?,?,?,?)');

    // ----------------  Binding the prepared query s=string i=int etc ---------------- 
    $query->bind_param("sssss", $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone'], $_POST['message']);
    
    // ----------------  Finally executing the query ---------------- 
    $query->execute();
    
    // ----------------  Setting error response if query fails ---------------- 
    if (false === $query) {
  
      $output['status']['code'] = "400";
      $output['status']['name'] = "executed";
      $output['status']['description'] = "query failed";	
      $output['data'] = [];
  
      mysqli_close($conn);
  
      echo json_encode($output); 
  
      exit;
  
    }
    
    // ----------------  Success response ---------------- 
    $output['status']['code'] = "200";
    $output['status']['name'] = "ok";
    $output['status']['description'] = "success";
    $output['data'] = [];
    
    mysqli_close($conn);
  
    echo json_encode($output); 
?>