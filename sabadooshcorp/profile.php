<?php
    require_once 'header.php';

    if(!$loggedIn) die('</div></body></html>');

    echo "<h3>Your Profile</h3>";

    $resultFromQuery = queryMysql("SELECT * FROM profiles WHERE user='$user'");
    if(!$resultFromQuery) die("Error, could not fetch results</div></body></html>");

    //If new text post
    if(isset($_POST['text']) && !empty($_POST['text']))
    {
        $textOfProfile = sanatizeString($_POST['text']);
        $textOfProfile = preg_replace('/\s\s+/', ' ', $textOfProfile);
        
        if($resultFromQuery->num_rows){
            queryMysql("UPDATE profiles SET text='$textOfProfile' WHERE user='$user'");
        }
        else{
            queryMysql("INSERT INTO profiles VALUES('$user', '$textOfProfile')");
        }
    }

    //Profile text to display
    else
    {
        if($resultFromQuery->num_rows){
            $rowOfTable = $resultFromQuery->fetch_array(MYSQLI_ASSOC);
            $textOfProfile = stripcslashes($row['text']);
        }
        else{
            $textOfProfile = "";
        }
    }
    
    //Profile image
    if (isset($_FILES['image']['name']))
    {
        $saveTo ="./profile_images/".$user.".jpg";
        move_uploaded_file($_FILES['image']['tmp_name'], $saveTo);
        $typeOk = TRUE;

        switch($_FILES['image']['type'])
        {
            case "image/gif": $src = imagecreatefromgif($saveTo); break;
            case "image/jpeg": //Both regular and progressive jpegs
            case "image/pjpeg": $src = imagecreatefromjpeg($saveTo); break;
            case "image/png": $src = imagecreatefrompng($saveTo); break;
            default: $typeOk = FALSE; break;
        }

        if ($typeOk)
        {
            list($w, $h) = getimagesize($saveTo);
            $max = 200;
            $tw = $w;
            $th = $h;

            if($w > $h && $max < $w)
            {
                $th = $max / $w * $h;
                $tw = $max;
            }
            elseif($h > $w && $max < $h)
            {
                $tw = $max / $h * $w;
                $th = $max;
            }
            elseif($max < $w)
            {
                $tw = $th = $max;
            }

            

            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(array(-1, -1, -1), array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
            imagejpeg($tmp, $saveTo);
            imagedestroy($tmp);
            imagedestroy($src);
        }

        else
        {
            //delete image from directory
            unlink($saveTo);
            die($_FILES['image']['type']."<br>File exstention not approved
                 <a data-transition='slide' href='profile.php?view=$user'> Click here</a>
                 to try again");
        }

        
    }

    showProfile($user);

    echo <<<_END

        <form data-ajax='false' method="POST" action="profile.php" enctype="multipart/form-data">
        <h3>Enter or edit your details and/or upload an image</h3>
        <textarea name="text"></textarea><br>
        Image: <input type="file" name="image" size="14">
        <input type="submit" value="Save Profie">
        </form>


    _END;


?>