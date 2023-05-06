<?php
  	ini_set('display_errors', 'On');
    error_reporting(E_ALL);    
    
    // ----------------  Include config files e.g dbname, host, port, user, password ---------------- 
    include("config.php");
  
    header('Content-Type: application/json; charset=UTF-8');

    // ----------------  Setting the conn var with a mysqli object containing the config files ---------------- 
    $conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname, $cd_port);

    // ----------------  Function that filters POST request data. Alters special chars, trims whitespace, tab, newline and strips backslashes ---------------- 
    function validate_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    // Declaring vars from the AJAX POST request
    $firstName = validate_input($_POST['fname']);
    $lastName = validate_input($_POST['lname']);
    $email = validate_input($_POST['email']);
    $phoneNumber = validate_input($_POST['phone']);
    $message = validate_input($_POST['message']);

    // ---------------- Backend form validation in case user turns of Javascript in the front end ---------------- 
    // Name and message validation
    $errors = [];
    if (empty($firstName) || empty($lastName)) {
      $errors[] = "Name fields cannot be empty";
    }

    // Message validation
    if (empty($message)) {
      $errors[] = "Message field cannot be empty";
    }

    // Phone validation
    if (empty($phoneNumber)) {
      $errors[] = "Phone number field cannot be empty";
    }

    // Email validation
    if (empty($email)) {
      $errors[] = "Email cannot be empty";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email format invalid";
    }

    // If there are any errors, return the error response
    if (!empty($errors)) {
      $output['status']['code'] = "403";
      $output['status']['name'] = "Error";
      $output['status']['description'] = implode("; ", $errors);
      $output['data'] = [];
      mysqli_close($conn);
      echo json_encode($output);
      exit;
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