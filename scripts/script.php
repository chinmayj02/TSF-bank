<?php
if (isset($_REQUEST['customers']) && $_REQUEST['customers'] == 1) {
    // connection variable ->mysqli_connect(host,username,password,dbname);
    $conn = mysqli_connect("localhost", "root", "", "cjbank") or die(mysqli_error($conn));
    $sql = mysqli_query($conn, "SELECT concat(fname,' ',mname,' ',lname)as name,`unique-id`,balance FROM customers");
    $customers = array();
    while ($row = mysqli_fetch_assoc($sql))
        array_push($customers, $row);
    $myJSON = json_encode($customers);
    echo $myJSON;
}
if (isset($_REQUEST['uniqueID'])) {
    $uniqueID = $_REQUEST['uniqueID'];
    // connection variable ->mysqli_connect(host,username,password,dbname);
    $conn = mysqli_connect("localhost", "root", "", "cjbank") or die(mysqli_error($conn));
    $result = mysqli_fetch_array(mysqli_query($conn, "SELECT `unique-id` FROM customers where `unique-id`='$uniqueID'"));
    if (empty($result)) echo 0;
    else echo 1;
}
if (isset($_REQUEST['p']) && isset($_REQUEST['id']) && isset($_REQUEST['amt'])) {
    $password = $_REQUEST['p'];
    $uniqueID = $_REQUEST['id'];
    $amount = $_REQUEST['amt'];
    // connection variable ->mysqli_connect(host,username,password,dbname);
    $conn = mysqli_connect("localhost", "root", "", "cjbank") or die(mysqli_error($conn));
    $result = mysqli_fetch_array(mysqli_query($conn, "SELECT password,balance FROM customers where `unique-id`='$uniqueID'"));
    if (empty($result)) echo 0;
    else {
        if ($result['password'] == $password) {
            if ($result['balance'] >= $amount) echo 1;
            else echo 2;
        } else echo 0;
    }
}
if (isset($_POST['transfer'])) {
    $senderUniqueID = $_POST['sender'];
    $receiverUniqueID = $_POST['receiver'];
    $amount = $_POST['amount'];
    // connection variable ->mysqli_connect(host,username,password,dbname);
    $conn = mysqli_connect("localhost", "root", "", "cjbank") or die(mysqli_error($conn));
    mysqli_query($conn, "update customers set balance=balance-'$amount' where `unique-id`='$senderUniqueID'");
    mysqli_query($conn, "update customers set balance=balance+'$amount' where `unique-id`='$receiverUniqueID'");
    mysqli_query($conn, "insert into transactions (`sender-unique-id`, `receiver-unique-id`, amount,result) values ('$senderUniqueID', '$receiverUniqueID', '$amount',1)");
    header("location:../transfer.html");
}