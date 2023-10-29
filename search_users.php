<?php
require_once "connection.php";

$searchKeyword = isset($_GET['search']) ? $_GET['search'] : "";
$sql = "SELECT * FROM user WHERE username LIKE '%$searchKeyword%' OR firstname LIKE '%$searchKeyword%' OR lastname LIKE '%$searchKeyword%'";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($users as $user) {
    echo '<tr>';
    echo '<td>' . $user['id'] . '</td>';
    echo '<td>' . $user['username'] . '</td>';
    echo '<td>' . $user['firstname'] . '</td>';
    echo '<td>' . $user['lastname'] . '</td>';
    echo '<td>' . ($user['userlevel'] == 'a' ? 'Admin' : 'Member') . '</td>';
    echo '<td><a href="edit_user.php?id=' . $user['id'] . '">Edit</a></td>';
    echo '<td><a href="delete_user.php?id=' . $user['id'] . '">Delete</a></td>';
    echo '</tr>';
}
?>
