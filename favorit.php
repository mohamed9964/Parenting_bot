<?php
ob_start();
session_start();
$pageTitle = 'Profile';
include 'init.php';
if (isset($_SESSION['user'])) {
    $do = isset($_GET['do']) ? $_GET['do'] : 'error';
    if ($do == 'add-favorite') {
        $articleid = isset($_GET['articleid']) && is_numeric($_GET['articleid']) ? intval($_GET['articleid']) : 0;
        $userid = $_SESSION['userid'];
    $stmt = $con->prepare("INSERT INTO 
                                favorite(article_id, user_id)
                            VALUES(:zarticle, :zuser)");
    // Execute Query
    $stmt->execute(array(
        'zarticle' => $articleid,
        'zuser' => $userid
    ));
    header('Location:' . $_SERVER['HTTP_REFERER']);
} elseif ($do == 'remove-favorite') {
        $articleid = isset($_GET['articleid']) && is_numeric($_GET['articleid']) ? intval($_GET['articleid']) : 0;
        $userid = $_SESSION['userid'];
        // Select All Data Depend On This ID
        $check = checkarticle('article_id', 'favorite', $articleid);
        // If There's Such ID Show The Form
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM favorite WHERE user_id = $userid");
            $stmt->execute();
            header('Location:' . $_SERVER['HTTP_REFERER']);
}
} elseif ($do == 'error') {
        header('Location:' . $_SERVER['HTTP_REFERER']);
    }
    } else {
    header('Location: login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
