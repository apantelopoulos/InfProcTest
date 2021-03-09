<?php
date_default_timezone_set("Europe/Athens");
$current_date = date("Y-m-d");

$conn = mysqli_connect('sql305.epizy.com','epiz_26836307','qMeDQd2ioQChp','epiz_26836307_list');

session_start();

if(isset($_POST['submit'])) {
    $selected_date=$_POST['mydate'];
    $_SESSION['temp_date'] = $selected_date;
}
else if(isset($_POST['add_submit'])){
    $selected_date = $_SESSION['temp_date'];
}
else if(isset($_POST['final_submit'])){
    $selected_date = $_SESSION['temp_date'];
}
else{
    $_SESSION['temp_date'] = $current_date;
    $selected_date = $current_date;
}


if(isset($_POST['add_submit'])){
    $answer = $_POST['add'];
    $sql = "INSERT INTO `list` (`date`,`task`,`active`) VALUES ('$selected_date','$answer','X')";
    mysqli_query($conn, $sql);
}


date_default_timezone_set("Europe/Athens");

$startdate = strtotime("-3 days", strtotime("$current_date"));
$startdate = date("Y-m-d", $startdate);

$enddate = strtotime("+7 days", strtotime("$current_date"));
$enddate = date("Y-m-d", $enddate);

$sql = "SELECT * FROM `list` WHERE `date` = '$selected_date' AND `active` = 'X'";
$result2 = mysqli_query($conn, $sql);
$tasks = mysqli_fetch_all($result2, MYSQLI_ASSOC);

if(isset($_POST['final_submit'])){
    // print_r($tasks);
    $next_day = strtotime("+1 days", strtotime("$selected_date"));
    $next_day = date("Y-m-d", $next_day);
    for($j=0;$j<count($tasks);$j++) {
        if (isset($_POST["q$j"])) {
            $answer = $_POST["q$j"];
            if ($answer == "next") {
                $temp = $tasks[$j]['task'];
                //echo $temp;
                $sql = "UPDATE `list` SET `date` = '$next_day' WHERE `list`.`task` = '$temp'";
                mysqli_query($conn, $sql);
            }
            if ($answer == "end") {
                $temp = $tasks[$j]['task'];
                $sql = "UPDATE `list` SET `active` = 'N' WHERE `list`.`task` = '$temp'";
                mysqli_query($conn, $sql);
            }
        }
    }
}

if(isset($_POST['calendar_submit'])){
    header('Location: calendar.php');
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="main.css">
    <style>
        button {
            text-decoration: none;
            display: inline-block;
            padding: 6px 16px;
        }
        .column20 {
            float: left;
            width: 20%;
            margin: auto;
        }
        .column60 {
            float: left;
            width: 60%;
            margin: auto;
        }
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .date_container {
            width: 800px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;

        }

        .date{
            font-family: Helvetica, Arial, sans-serif;
            font-weight: bold;
            font-size: x-large;
            line-height: 1em;
            color: cornflowerblue;
        }

        #tbl {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 50%;
            align-self: center;
            margin-left: auto;
            margin-right: auto;
        }

        #tbl td, #tbl th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #tbl tr:nth-child(even){background-color: #f2f2f2;}

        #tbl tr:hover {background-color: #dddddd;}

        #tbl th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: cornflowerblue;
            color: white;
        }
        .cntr{
            margin-right: auto;
            margin-left: auto;
            display: block;
        }

        .fsSubmitButton
        {
            padding: 10px 20px 11px !important;
            font-size: 21px !important;
            background-color: #F36C8C;
            font-weight: bold;
            text-shadow: 1px 1px #F36C8C;
            color: #ffffff;
            border-radius: 100px;
            -moz-border-radius: 100px;
            -webkit-border-radius: 100px;
            border: 1px solid #F36C8C;
            cursor: pointer;
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.5) inset;
            -moz-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.5) inset;
            -webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.5) inset;
        }

    </style>

</head>

<body>

<br><br><br>
<div class="date_container row">
    <tr>
        <form action="index.php" method="POST" id="form1">
            <div class="column20"> <BUTTON type="submit" value="submit" name="submit" id="prevButton" <?php if($selected_date==$startdate){echo "disabled";}?>>Previous Day</BUTTON></div>
            <input type="date" id="mydate" name="mydate" format="DD/MM/YYYY" placeholder="DD/MM/YYYY" readonly hidden value="<?php echo $selected_date?>" min="<?php echo $startdate ?>" max="<?php echo $enddate ?>">
            <div class="column60 date" > <?php
                $display_date = strtotime("$selected_date");
                echo date("l", $display_date).", ".date("d M Y", $display_date);
                ?></div>
            <div class="column20" style="text-align: center"><BUTTON type="submit" class="button" value="submit" name="submit" id="nextButton" <?php if($selected_date==$enddate){echo "disabled";}?>>Next Day</BUTTON></div>
        </form>
    </tr>
</div>


<script>
    let nextbutton = document.getElementById('nextButton')
    nextbutton.addEventListener('click', function() {
        dateup()
    })

    function dateup() {
        let input = document.getElementById('mydate')
        input.stepUp()
    }

    let prevbutton = document.getElementById('prevButton')
    prevbutton.addEventListener('click', function() {
        datedown()
    })

    function datedown() {
        let input = document.getElementById('mydate')
        input.stepDown()
    }

</script>
<br><br><br>
<div class="date_container row">
    <form action="index.php" method="post" id="form2" name="form2">
        <input type="text" name="add" autocomplete="off" style="width: 600px">
        <input type="submit" name="add_submit" value="Submit" hidden>

    </form>
</div>

<br><br><br>



<form method="post">
    <table id="tbl">
        <tr>
            <th style="width: 80%">TODO List </th>
            <th style="width: 10%">Next Day</th>
            <th style="width: 10%">Done</th>
        </tr>

        <?php
        $sql = "SELECT * FROM `list` WHERE `date` = '$selected_date' AND `active` = 'X'";
        $result2 = mysqli_query($conn, $sql);
        $tmp = mysqli_fetch_all($result2,MYSQLI_ASSOC);
        if(count($tmp)==0){
            print "</table><table id='tbl'><tr><td>There are no more tasks for today</td></tr>";
        }
        $result2 = mysqli_query($conn, $sql);
        $i = 0;
        while($data2 = mysqli_fetch_assoc($result2)) {
            print "<tr><td>$data2[task]</td>
            <td><input type=\"radio\" name=\"q$i\" value=\"next\"></input></td>
            <td><input type=\"radio\" name=\"q$i\" value=\"end\"></input></td></tr>";
            $i++;
        }
        ?>
    </table>
    <table id="tbl" style="text-align: right">
        <tr><td style="text-align: right">
                <input type="submit" class="fsSubmitButton" name="final_submit" value="Submit">
            </td></tr>

        <tr><td style="text-align: right">
                <input type="submit" class="fsSubmitButton" name="calendar_submit" value="Calendar View">
            </td>
        </tr>
    </table>
</form>

<br><br><br>

<table id="tbl">
    <tr>
        <th style="width: 100%">Completed Tasks </th>
    </tr>

    <?php
    $sql = "SELECT * FROM `list` WHERE `date` = '$selected_date' AND `active` = 'N'";
    $result3 = mysqli_query($conn, $sql);
    while($data3 = mysqli_fetch_assoc($result3)){
        print "<tr><td>$data3[task]</td></tr>";
    }
    ?>
</table>

<br><br><br>
</body>
</html>
