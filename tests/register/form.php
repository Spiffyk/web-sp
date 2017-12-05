<!doctype html>
<html>
    <head>
        <title>User creation form</title>
    </head>
    <body>
        <form method="post">
            <input type="hidden" name="action" value="register">

            <label about="name">User:</label> <input type="text" name="name"><br />
            <label about="password">Password:</label> <input type="password" name="password"><br />
            <label about="email">E-mail:</label> <input type="email" name="email"><br />
            <input type="submit">
        </form>
    </body>
</html>
