<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/11/2017
 * Time: 12:10
 */
?>
<?php require 'header.php' ?>
<div>
    <div>
        <form action="/users/logout" method="post">
            <button type="submit">Sign Out</button>
        </form>
    </div>
    <div>
        <h1>New User :</h1>
        <form action="/users/add" method="post">
            <label>User Name :</label>
            <input name="user_name">
            <label>Password:</label>
            <input name="password">
            <button type="submit">Add User</button>
        </form>
    </div>
    <div>
        <form action="/users/delete" method="post">
            <table>
                <tr>
                    <th></th>
                    <th>User Name :</th>
                    <th>Password :</th>
                </tr>
                <?php foreach($users as $key => $user) : ?>
                    <tr>
                        <td><input name="keys[]" type="checkbox" value="<?=$key?>"></td>
                        <td><?=$user->user_name?></td>
                        <td><?=$user->password?></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <button type="submit">Delete</button>
        </form>
    </div>
</div>
<?php require 'footer.php' ?>
