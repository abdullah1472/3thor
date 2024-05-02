<?php require("contc.php"); ?>

<?php

function checkUserNameExist($username)
{

    global $ConnectingDB;
    $sql = "SELECT UserName from users where UserName=:uName";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':uName', $username);
    $stmt->execute();
    $result = $stmt->rowcount();
    if ($result == 1) {
        return true;
    } else {
        return false;
    }
}

function Login($UserName, $Password)
{
    global $ConnectingDB;
    $sql = "SELECT * from users where UserName=:username and Password=:password limit 1";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt->bindValue(':username', $UserName);
    $stmt->bindValue(':password', $Password);
    $stmt->execute();

    $result = $stmt->rowcount();
    if ($result == 1) {
        return $foundAccount  = $stmt->fetch();
    } else {
        return null;
    }
}

function isLogin()
{
    if (isset($_SESSION["UserId"])) {
        return true;
    } else {
        $_SESSION["errorMessage"] = 'يجب تسجيل الدخول ';
        redirect_to("login1.php");
    }
};

?>