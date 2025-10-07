<?php 
Header('Content-Type: application/json');
Header("Access-Control-Allow-Origin: *");   
header("Access-Control-Allow-Methods: POST");




include 'connect.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password) ){

    $queryUserEmail = "Select * from users where email = :email";
    $stmtUser =$pdo->prepare($queryUserEmail);
    $stmtUser ->bindParam(":email", $data->email);
    $stmtUser ->execute();


    if($stmtUser ->rowCount() > 0){
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if(password_verify($data->password ,$user['password'])){
            http_response_code(200);
            echo json_encode(["message" => "Login successful", "user" => $user]);
            exit;
        }else{
            http_response_code(401);
            echo json_encode(["message" => "Invalid password"]);
            exit;
        }

    }else{
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
        exit;
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Email and password are required"]);
    exit;
}




?>