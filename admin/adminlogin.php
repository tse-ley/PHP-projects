<!DOCTYPE html>
<html>
<html lang="en">
    <head>
    <link rel="shortcut icon" href="images" type="images/logo.jpg" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin login page</title>
    <link rel="stylesheet" href="login.css">
   </head>

    <body>
    <h1> Welcome to the login page </h1>

    <h2> Current time and date</h2>
    <?php
     echo " " . date("Y-m-d H:i:s");
    ?>

    <h2>User Information Form:</h2> <br>
    <form action="process.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <input type="submit" value="Submit">
    </form>
    

   </body>
   

</html>