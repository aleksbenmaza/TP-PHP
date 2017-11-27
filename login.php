<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/11/2017
 * Time: 12:09
 */
?>
<?php require 'header.php' ?>
<div>
    <form method="post" action="/users/login">
        <input name="user_name">
        <input name="password">
        <button type="submit">Sign In</button>
    </form>
</div>
<?php require 'footer.php' ?>