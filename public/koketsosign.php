<?php

header("Content-Type: applicaton/json");
Header("Access-Control-Allow-Origin: *");
Header("Access-Control-Allow-Methods: POST");

include 'connect.php';

//get the data from the request
$data = json_decode(file_get_contents("php://input"));


// Helper function to check missing fields
function getMissingFields($data, $fields)
{
    $missing = [];
    foreach ($fields as $field) {
        if (empty($data->$field)) {
            $missing[] = $field;
        }
    }
    return $missing;
}

if(!empty($data->email)  && !empty($data->password)  && !empty($data->first_name)  && !empty($data->last_name)){
 
        $queryEmail ="Select id from users where email = :email";
        $stmtEmail = $pdo->prepare($queryEmail);
        $stmtEmail->bindParam(":email" ,$data->email) ;
        $stmtEmail->execute();
        
        //check if email already exists
        if($stmtEmail->rowCount() > 0){
            http_response_code(400);
            echo json_encode(["message" => "email already exists"]);
            exit;
        }

        $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);

        $queryAdduser ="INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
        $stmtAdduser = $pdo->prepare($queryAdduser);
        $stmtAdduser->bindParam(":first_name" ,$data->first_name) ;
        $stmtAdduser->bindParam(":last_name" ,$data->last_name) ;
        $stmtAdduser->bindParam(":email" ,$data->email) ;     
        $stmtAdduser->bindParam(":password" ,$hashed_password) ;
        $stmtAdduser->execute();

        if($stmtAdduser->execute()){
            http_response_code(201);
            echo json_encode(["message" => "user created successfully"]);
            exit;

        }else{
            http_response_code(503);
            echo json_encode(["message" => "Unable to register user."]);
        }

   } else {
        http_response_code(400);
        $requiredFields = ["email", "password", "first_name", "last_name"];
        $missingFields = getMissingFields($data, $requiredFields);
        echo json_encode(["message" => "Incomplete data." , "missing_fields" => $missingFields ,  "error" => "400 Bad Request"]);
   }

 



?>