<!-- gabriel urtado -->

<?php

//function to print doctype, <html>, <head>, <title>, <body> of HTML file
function display_html_head($title= ''){
    print <<<HTMLBLOCK
        <!doctype html>
        <html>
        <head><link rel ="stylesheet" href="style.css">
        <title>$title</title></head>
        <body>
        
        HTMLBLOCK;
}

//function to print end tags for </body> and </html>
function display_html_foot(){
    print <<<HTMLBLOCK
        </body></html>
        HTMLBLOCK;
}


function display_form($errors = []){
    // echo "<form method = 'POST' action = '/fx/data-insert.php'>"
    //Pid
    echo "<form method='POST' action='$_SERVER[PHP_SELF]'>
    <label>PID</label><input type='number' name='pid' value='" . ($_SESSION['PID'] ?? '') . "'/>";
    if (isset($errors[0])) {
        echo "<span class='err'>$errors[0]</span>";
     }
    
     
    //Name
    echo "<br><label>Name</label><input type='text' name='name' value='" . ($_SESSION['Name'] ?? '') . "'/>";
    if (isset($errors[1])) {
        echo "<span class='err'>$errors[1]</span>";
     }

    //Team Name
    echo "<br><label>Team Name</label>";
    echo "<Select name= 'teams'>
    <option value='U10' " . (($_SESSION['Team Name'] ?? '') == 'U10' ? 'Selected' : '') . ">U10</option>
    <option value='U11' " . (($_SESSION['Team Name'] ?? '') == 'U11' ? 'Selected' : '') . ">U11</option>
    <option value='U12' " . (($_SESSION['Team Name'] ?? '') == 'U12' ? 'Selected' : '') . ">U12</option>
    </Select>";

    //Gender
     echo "<br><label>Gender</label>";
     if (isset($errors[2])) {
        echo "<span class='err'>$errors[2]</span>";
        }
     echo "<br><input type='radio' name='gender' value='M' " . (($_SESSION['Gender'] ?? '') == 'M' ? 'Checked' : '') . "/><label>Male</label><br />
     <input type='radio' name='gender' value='F' " . (($_SESSION['Gender'] ?? '') == 'F' ? 'Checked' : '') . "/><label>Female</label><br />
     <input type='radio' name='gender' value='X' " . (($_SESSION['Gender'] ?? '') == 'X' ? 'Checked' : '') . "/><label>Other</label><br />";

     //Sport
    echo "<br><label>Favorite Sports</label>";
    echo "<br><input type='checkbox' name='sports[]' value='sc' " . (in_array('sc', ($_SESSION['Favorite Sports'] ?? [])) ? 'Checked' : '') . "/> <label>Soccer</label><br />
    <input type='checkbox' name='sports[]' value='tn' " . (in_array('Tn', ($_SESSION['Favorite Sports'] ?? [])) ? 'Checked' : '') . "/> <label>Tennis</label><br />
    <input type='checkbox' name='sports[]' value='sw' " . (in_array('sw', ($_SESSION['Favorite Sports'] ?? [])) ? 'Checked' : '') . "/> <label>Swimming</label><br />
    <input type='checkbox' name='sports[]' value='bk' " . (in_array('bk', ($_SESSION['Favorite Sports'] ?? [])) ? 'Checked' : '') . "/> <label>Basketball</label><br />";
     
    echo "<input type='submit' name='submit' value='Submit'/> </form>";
   

}

function confirm_form(){
 foreach($_SESSION as $key => $value){
    $name = $_POST['name'];
    $pid= $_POST['pid'];
    $gender = $_POST['gender'];
    
    
    echo $pid;
    echo "<br>";
    echo $name;
    echo"<br>";
    echo $gender;
 }

 echo "<form method='POST' action='$_SERVER[PHP_SELF]'>
<input type='submit' name='submit' value='Confirm'/>
<input type='submit' name='submit' value='Edit'/>
</form>";
}

function process_form(){
    First create a connec􀆟on to the database using PDO.
    try {
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    echo "<p class='err'>Error: " . $e->getMessage() . "</p>";
    }

    try {
        $sql = "CREATE DATABASE IF NOT EXISTS players_db;
        USE players_db;
        CREATE TABLE IF NOT EXISTS Player (
        PID INT PRIMARY KEY,
        PName VARCHAR(20),
        TeamName CHAR(3),
        Gender CHAR(1)
    );
    CREATE TABLE IF NOT EXISTS Player_FavSports (PID INT,
    FavSport CHAR(2),
    PRIMARY KEY(PID, FavSport),
    FOREIGN KEY(PID) REFERENCES Player(PID)
    );";
    $conn->exec($sql);
    } catch (PDOException $e) {
        echo "<p class='err'>Error: " . $e->getMessage() . "</p>";
    }

    try {
        $insert = "INSERT INTO players_db.Player VALUES (?,?,?,?);";
        $stmt = $conn->prepare($insert);
        $stmt->execute(array($_SESSION['PID'], $_SESSION['Name'], $_SESSION['Team Name'], $_SESSION['Gender']));
        foreach ($_SESSION['Favorite Sports'] as $sport) {
        $insert = "INSERT INTO players_db.Player_FavSports VALUES (?,?);";
        $stmt = $conn->prepare($insert);
        $stmt->execute(array($_SESSION['PID'], $sport));
        }
        echo "<p style='color:green'>Data Inserted Successfully.</p>";
        } catch (PDOException $e) {
        echo "<p class='err'>Error: " . $e->getMessage() . "</p>";
        }


}

function validate_form(){

    //name
    $errors = array();
    $name = $_POST['name'];
    if( strlen($name) == 0 ){
        $errors[1] = 'Your name is required';
    }else{
        $_SESSION['Name'] = $name;
    }


    //PID
    $pid = $_POST['pid'];
    $min = 0;
    $max = 10000000;
    if(filter_var($pid,FILTER_VALIDATE_INT, array('options' => array("min_range" => $min, "max_range" => $max))) == TRUE){
        $_SESSION['PID'] = $pid;
    }else{
        $errors[0] = "PID must be a positive intiger";
    }


    //Gender
    // if(isset($_POST['submit'])){
       
    //     $gender = $_POST['gender'];

    //     if(empty($gender)){
    //         $errors[2] = "Select a Gender";
    //     }
    // }else{
    //     $_SESSION['gender'] = $_POST['gender'];
    // }




    return $errors;
    
}



?>