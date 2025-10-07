<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome to Asset Management System</title>
    <style>
      body {
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        color: #333333;
      }
      .container {
        background-color: #ffffff;
        max-width: 600px;
        margin: 40px auto;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 30px;
      }
      h2 {
        color: #08979c;
        font-weight: 700;
        margin-bottom: 20px;
      }
      p {
        font-size: 16px;
        line-height: 1.5;
      }
      ul {
        list-style-type: none;
        padding: 0;
        margin: 20px 0;
      }
      ul li {
        font-size: 16px;
        margin-bottom: 10px;
      }
      .btn {
        display: inline-block;
        background-color: #08979c;
        color: white !important;
        font-weight: 600;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        margin: 30px 0 10px 0;
        font-size: 16px;
        box-shadow: 0 3px 8px rgba(8, 151, 156, 0.4);
      }
      .footer-text {
        font-size: 14px;
        color: #777777;
        margin-top: 40px;
        border-top: 1px solid #dddddd;
        padding-top: 20px;
      }
    </style>
</head>
<body>
  <div class="container">
    <h2>Hello {{ $user->name }},</h2>
    <p>Welcome to Asset System! Your account has been successfully created. Below are your login details:</p>
    
    <ul>
      <li><strong>Email:</strong> {{ $user->email }}</li>
      <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    
    <p>You can login now by clicking the button below:</p>
    
    <a href="{{ url('/login') }}" class="btn" target="_blank" rel="noopener noreferrer">Login to Your Account</a>
    
    <p>If you did not request this account, please ignore this email.</p>
    
    <p class="footer-text">Thank you for joining us!<br/>The Asset Team</p>
  </div>
</body>
</html>
