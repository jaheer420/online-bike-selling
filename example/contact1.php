
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
</head>
<body>
    <h1>Contact Us</h1>
    <form action="process_contact.php" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        
         <label for="number">phone no:</label>
         <input type="number" name="number" id="number" required><br>
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" required></textarea><br>

        
        
        <input type="submit" value="Submit">
    </form>
</body>

</html>
