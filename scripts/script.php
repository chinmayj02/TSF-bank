<?php
if (isset($_REQUEST['customers']) && $_REQUEST['customers'] == 1) {
    // connection variable ->mysqli_connect(host,username,password,dbname);
    $conn = mysqli_connect("localhost", "root", "", "cjbank") or die(mysqli_error($conn));
    $sql=mysqli_query($conn, "SELECT concat(fname,' ',mname,' ',lname)as name,`unique-id`,balance FROM customers");
    $customers=array();
    while($row = mysqli_fetch_assoc($sql))
        array_push($customers,$row);
    $myJSON = json_encode($customers);
    echo $myJSON;
}