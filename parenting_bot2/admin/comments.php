<?php
/*
    ================================================
    == Comments Page
    ================================================
    */
ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'Comments';
if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        $stmt = $con->prepare("SELECT 
                                    comments.*,articles.Name AS article_Name,
                                    users.Username AS Member
                                FROM 
                                    comments
                                INNER JOIN
                                    articles
                                ON
                                    articles.articleID = comments.article_id
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = comments.User_id
                                ORDER BY C_id DESC");
        $stmt->execute();
        $comments = $stmt->fetchAll();
        if (!empty($comments)) {
?>
            <h1 class="text-center">Manage Comment</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>article Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($comments as $comment) {
                            echo "<tr>";
                            echo "<td>" . $comment['C_ID'] . "</td>";
                            echo "<td>" . $comment['Comment'] . "</td>";
                            echo "<td>" . $comment['article_Name'] . "</td>";
                            echo "<td><a href='members.php?do=Edit&userid=" . $comment['User_id'] . "'>" . $comment['Member'] . "</td>";
                            echo "<td>" . $comment['Comment_date'] . "</td>";
                            echo "<td style='width:150px'>
                            <a href='comments.php?do=Delete&comid=" . $comment['C_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i></a>";
                            if ($comment['Status'] == 0) {
                                echo "<a href='comments.php?do=Approve&comid=" . $comment['C_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i></a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                    <a href="dashboard.php" class="btn btn-primary btn-sm" style="background-color:#e74c3c;border:none"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                
            </div>
        <?php } else {
            echo '<div class="container">';
                echo '<div class="nice-message">There\'s No Comments To Manage</div>';
                echo '<a href="dashboard.php" class="btn btn-primary btn-sm" style="margin-left:50px;background-color:#e74c3c;border:none"><i class="fa fa-arrow-left"></i> Back</a>';
            echo '</div>';
        }
        ?>
        <?php } elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Comment</h1>';
        echo "<div class= 'container'>";
        // Check If Get Request Com ID Is numeric & Get The Integer Value Of It
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        // Select All Data Depend On This ID
        $check = checkarticle('C_ID', 'comments', $comid);
        // If There's Such ID Show The Form
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM comments WHERE C_ID = :comid");
            $stmt->bindParam(':comid', $comid);
            $stmt->execute();
            // Echo Success Message
            echo '<div class="container">';
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
            redirectHome($theMsg, 'back');
            echo '</div>';
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg);
            echo "</div>";
        }
        echo '</div>';
    }elseif ($do == 'Approve') {
        // Approve Member Page
        echo '<h1 class="text-center">Approve Member</h1>';
        echo "<div class= 'container'>";
        // Check If Get Request Com ID Is numeric & Get The Integer Value Of It
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        // Select All Data Depend On This ID
        $check = checkarticle('C_ID', 'comments', $comid);
        // If There's Such ID Show The Form
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE C_ID = ?");
            $stmt->execute(array($comid));
            // Echo Success Message
            echo '<div class="container">';
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved</div>';
            redirectHome($theMsg, 'back');
            echo '</div>';
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>';
            redirectHome($theMsg);
            echo "</div>";
        }
        echo '</div>';
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush(); // Release The Output
