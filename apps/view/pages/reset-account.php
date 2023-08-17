<?php
$db = new Mydb('pk_user');
$user = $db->filterData(['email'=>$_GET['email'], 'remember_token'=>$_GET['token']]);
if (count($user)>0) {
    $user = $db->getData(['email'=>$_GET['email'], 'remember_token'=>$_GET['token']]);
}
else{
    $home = home;
    echo "<a href='/{$home}'>Home</a><br>";
    die("Link expired");
}
if (isset($_POST['cnf_password']) && isset($_POST['new_password']) && !empty($_POST['new_password']) && !empty($_POST['cnf_password'])) {

    // $map = matchData($_POST['cnf_password'],$_POST['new_password'],1);
    if ($_POST['cnf_password']==$_POST['new_password']) {
        $db->updateData(['password'=>md5($_POST['new_password']),'remember_token'=>'']);
        $GLOBALS['msg_signin'][] = "Password changed successfully!";
    }
    else{
        $GLOBALS['msg_signin'][] = "Password did not match or invalid password";
    }
}
?>
<?php import("apps/view/inc/nav.php"); ?>
<section style="min-height: 100vh; background-image: url(/<?php echo media_root; ?>/nav-bg.jpeg); 
background-position: center;">
    <div class="container">
        <div class="row" style="padding-top: 220px;">
            <div class="col-md-4"></div>
            <div class="col-md-4">
            </head>          
            <main class="form-signin">
            <form action="" method="post">
                <!-- <img class="mb-4" src="/<?php //echo media_dir;?>/logo/logo.png" alt="" width="auto" height="57"> -->
                <h1 class="h1 mb-3 fw-bold text-center">Change Password</h1>

                <div class="form-floating">
                <input type="text" class="form-control mb-1" id="floatingInput" name="cnf_password" placeholder="password">
                <label for="floatingInput">New Password</label>
                </div>
                <div class="form-floating">
                <input type="password" class="form-control mb-1" id="floatingPassword" name="new_password" placeholder="Password">
                <label for="floatingPassword">Confirm New Password</label>
                </div>
                <button class="w-100 btn btn-lg btn-outline-secondary mb-1" type="submit">Reset</button>
                <?php msg('msg_signin'); ?>
                <input type="hidden" name="login_my_account" value="true">
            </form>
            </main>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</section>