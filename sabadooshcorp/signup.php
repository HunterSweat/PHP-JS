<?php
    require_once 'header.php';

?>   

            <!-- Username and password validation -->
            <script>
                function checkUser(user){
                    if(user.value == ''){
                        $('#used').html('&nbsp;')
                        return
                    }
                    $.post('checkuser.php',
                            {user : user.value},
                            function(data){
                                $('#used').html('<ul>'+data+'</ul>')
                            })
                    }
            </script>
            <script>
                function checkPassword(password){
                    if(password.value == ''){
                        $('#passed').html('&nbsp;')
                        return
                    }
                    $.post('checkuser.php',
                            {password : password.value}, 
                            function(data){
                                $('#passed').html('<ul>'+data+'</ul>')
                            })
                    }
            </script>
<?php

        $error = $user = $pass = "";
        if(isset($_SESSION['user'])) destroySession();

        if(isset($_POST['user']) && isset($_POST['pass'])){
            $usertemp = sanatizeString($_POST['user']);
            $user = strtolower($usertemp);
            $pass = sanatizeString($_POST['pass']);
            
            if($user == "" || $pass == ""){
                die('<h4>Not all fields were entered<br><br>Please try again <a href="signup.php">Click here</a></h4></div></body></html>');
            }

            else if(strlen($user) < 5 ){
                echo "<li class='taken'>&nbsp;&#x2718;"." Username must be longer than 5 charecters<br></li>";
            }
            else if(!preg_match("/[a-z]/", $user) ||
                !preg_match("/[0-9]/", $user)){
                    echo "<li class='taken'>&nbsp;&#x2718;"." Username must contain atleast one Lowercase charecter and a number</li><br>";
                } 
            else {
               $result = queryMysql("SELECT * FROM members WHERE user='$user'");

               if ($result->num_rows){
                   die('<h4>That Username already exists<br><br>Please try again <a href="signup.php">Click here</a></h4></div></body></html>');
               }
            }

            if(strlen($pass) < 5 ){
                echo "<li class='taken'>&nbsp;&#x2718;"." Password must be longer than 5 charecters<br></li>";
            }
            else if(!preg_match("/[a-z]/", $pass) ||
                !preg_match("/[0-9]/", $pass) ||
                !preg_match("/[A-Z]/", $pass)){
                    echo "<li class='taken'>&nbsp;&#x2718;"." Password must contain atleast one (Lowercase, Uppercase, Number) charecter</li><br>";
                }

            else{
                   $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
                   queryMysql("INSERT INTO members VALUES('$user', '$hashedPassword')");
                   die('<h4>Account created</h4> Please log in.</div></body></html>');
               }
            
        }



    echo <<<_END

                
                <form method="post" action="signup.php">$error
                    <div data-role="fieldcontain">
                        <label></label>
                        Please enter your details to sign up
                    </div>

                    <div data-role="fieldcontain">
                        <label>Username</label>
                        <input type="text" maxlength="16" name='user' value="$usertemp" onkeyup="checkUser(this)">
                        <label></label><div id='used'>&nbsp;</div>
                    </div>

                    <div data-role="fieldcontain">
                        <label>Password</label>
                        <input type="text" maxlength="16" name='pass' value="$pass" onkeyup="checkPassword(this)">
                        <label></label><div id='passed'>&nbsp;</div>
                    </div>

                  

                    <div data-role="fieldcontain">
                        <label></label>
                            <input data-transition="slide" type="submit" value="Sign Up">
                            <label></label>
                    </div>
                </div>
                
            </body>
        </html>
        


    _END;


?>