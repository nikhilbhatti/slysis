<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple HTML Form</title>
</head>
<body>

    <h1> create new accunt</h1>
    
    <form action="#" method="POST">
        
        <label for="name"> full name:</label><br>
        <input 
            type="text" 
            id="name" 
            name="full_name" 
            placeholder="enter your name" 
            required
        ><br><br>

        <label for="email">email ID:</label><br>
        <input 
            type="email" 
            id="email" 
            name="email_id" 
            placeholder="example@domain.com" 
            required
        ><br><br>

        <label for="password">password:</label><br>
        <input 
            type="password" 
            id="password" 
            name="user_password" 
            placeholder=" Keep it confidential" 
            required
        ><br><br>
        
        <button type="submit">
             register
        </button>
        
        </form>

</body>
</html>