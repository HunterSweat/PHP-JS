<?php

    require_once 'header.php';

    if (!$loggedIn) die("</div></body></html>");

    if (isset($_GET['view'])) $viewedAccount = sanatizeString($_GET['view']);
    else $viewedAccount = $user;

    if (!empty($viewedAccount))
    {
        if ($viewedAccount == $user) $name1 = $name2 = "Your";
        else
        {
            $name1 = "<a href='members.php?view=$viewedAccount'>$viewedAccount</a>";
            $name2 = "$viewedAccount's";
        }
    }

    echo "<h3>$name1 Codebase</h3><div class='my-buttons'>";
    showProfile($viewedAccount);

    if (isset($_POST['code']) && !empty($_POST['code']) && isset($_POST['title']) && !empty($_POST['title']))
    {
        $titleOfPost = sanatizeString($_POST['title']);
        $codeOfPostEncode = base64_encode(sanatizeCode($_POST['code']));
        $typeOfPost = substr(sanatizeString($_POST['type']), 0, 1);
        $timeOfPost = time();
        $lengthOfPost = strlen($codeOfPostEncode);
        

        queryMysql("INSERT INTO codebase VALUES(NULL, '$user', '$titleOfPost', '$timeOfPost', '$codeOfPostEncode')");
    }

    if (isset($_GET['delete']))
    {
        $delete = sanatizeString($_GET['delete']);
        queryMysql("DELETE FROM codebase WHERE id=$delete and auth='$user'");
    }

    date_default_timezone_set('America/Chicago');
    $queryDatabase = "SELECT * FROM codebase WHERE auth='$viewedAccount' ORDER BY time DESC";
    $resultFromQuery = queryMysql($queryDatabase);
    $numberOfRowsInTable = $resultFromQuery->num_rows;


   
    for ($j=0; $j<$numberOfRowsInTable; ++$j)
    {
        $rowOfTable = $resultFromQuery->fetch_array(MYSQLI_ASSOC);

        if($rowOfTable['auth'] == $user)
        {
            echo "[<a href='codebase.php?view=$viewedAccount"."&delete=".$rowOfTable['id']."'>Delete</a>]<br>";
        }

        
        echo date('M j \'y g:ia:', $rowOfTable['time']);
        echo "<button class=title>".$rowOfTable['title']."</button>";
        $codeOfPostDecode = base64_decode($rowOfTable['code']);
        echo "<pre style='display:none' class='prettyprint'>$codeOfPostDecode</pre>";

        
    }
    echo "</div>";

   if ($name1 == "Your")
    {
        echo <<<_END
            <br>
            <form method='post' action='codebase.php?view=$viewedAccount'>
                <h3>Post Your Example</h3><br>
                <ul data-role="listview" data-insert="true">

                    <li class="ui-body ui-body-a">
                        <fieldset class="ui-grid-a">
                            <div class='ui-block-a'>   
                                <label class='center' for="type"><h3>Chose best fit</h3></label>
                                <select>
                                    <option>Php</option>
                                    <option>C++</option>
                                    <option>C#</option>
                                </select>
                            </div>
                            <div class='ui-block-b'>
                                <label class='center' for="title"><h3>Title</h3></label>
                                <input type="text" name="title" value="" data-clear-btn="true">
                            </div>
                        </fieldset>
                    </li>
                    <li class="ui-field-contain">
                        <lable for='code'><h3>Insert Code</h3></lable>
                        <textarea cols='40' rows='8' name='code'></textarea>
                    </li>
                    
                  
                        <fieldset data-role="controlgroup" data-type="horizontal">
                            <button data-role="button" data-transition='slide'>Preview</button>
                            <input data-transition='slide' type='submit' value='Submit'>
                        </fieldset>
                    
                </ul>
                </form><br>


        _END;
    }


?>
        
            <script>
                $('#title').click(function(){$('#ttext').slideToggle('slow')})
                $('#code').click(function(){$('#ctext').slideToggle('slow')})
                $('.my-buttons').click(e=>$(e.target).next('pre').slideToggle('slow'))
            </script>
          
            <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>


        </div><br>
    </body>
</html>