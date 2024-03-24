<?php
/*class All_Functions{

}*/
function getTitle()
{

    global $pageTitle;

    if (isset($pageTitle)) {

        echo $pageTitle;
    } else {

        echo 'Default';
    }
}
////
function getAge($date)
{

    $dob = new DateTime($date);
    $now = new DateTime();
    $diff = $now->diff($dob);
    $age = $diff->y;
    return $age;
}
////////////////////////////
function check_input($input)
{
    $input = trim($input);
    $input = htmlspecialchars($input);
    $input = stripcslashes($input);
    return $input;
}
//////
function countItems($item, $table)
{

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}
//////////////////////
function countAvaillabl($item, $table,$column ,$state)
{

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table where $column = $state");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}
//////////
function sumAvaillabl($item, $table, $column, $state)
{

    global $con;

    $stmt2 = $con->prepare("SELECT SUM($item) FROM $table where $column = $state");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}
//////////
function sumBills($item, $table)
{

    global $con;

    $stmt2 = $con->prepare("SELECT SUM($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}
//////////
function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC")
{

    global $con;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

    $getAll->execute();

    $all = $getAll->fetchAll();

    return $all;
}
///////////////////////
function checkItem($select, $from, $value)
{

    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $statement->execute(array($value));

    $count = $statement->rowCount();

    return $count;
}
///////////////////////
$genders = array('gender' => ['Male' => 'Male', 'Female' => 'Female']);
///////////////////////////////////////
$Roles = array('Role' =>
['admin' => 'admin', 'subadmin' => 'subadmin', 'subadminadd' => 'subadminadd', 'subadminedit' => 'subadminedit', 'subadmindelete' => 'subadmindelete', 'subadminshow' => 'subadminshow']);
$Floors=array('Floor'=>[1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10]);
$Bookss=array('Book'=>[0=>'Not Booked',1=>'Booked']);
$Guests_states = array('Arrive' => [0 => 'Not Arrived', 1 => 'Arrived']);
$Pays = array('Pay' => [0 => 'Not Payed', 1 => 'Payed']);
$Guests_code_type = array('code_type' => ['Kuraimi' => 'Kuraimi', 'Annajm' => 'Annajm','Alakwa'=> 'Alakwa']);
$Stars = array('Star' => [1 => 1, 2 => 2, 3 => 3,4=>4,5=>5]);
?>