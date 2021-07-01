<?php
ob_start();
session_start();
include 'init.php';
$articleid = isset($_GET['articleid']) && is_numeric($_GET['articleid']) ? intval($_GET['articleid']) : 0;
$stmt = $con->prepare("SELECT 
                                        articles.*, users.Username AS Member
                                    FROM 
                                        articles
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = articles.Member_ID
                                    WHERE 
                                        Approve = 1 
                                    AND
                                    articleID = ?     
                                    ORDER BY articleID DESC");
// Execute Query
$stmt->execute(array($articleid));
// Fetch The Data
$article = $stmt->fetch();

?>

<!-- breadcrumb part -->
<section class="breadcrumb_part parallax_bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 ">
                <div class="breadcrumb_iner">
                    <h2>Article Details</h2>
                    <div class="breadcrumb_iner_link">
                        <a href="index-2.html">Home</a>
                        <span>|</span>
                        <p> Single Article</p>
                        <span>|</span>
                        <p>Article Details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="breadcrumb_animation_4">
        <div data-parallax='{"x": 30, "y": 250, "rotateZ":0}'>
            <img src="layout/img/icon/banner_icon/animated_banner_4.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_5">
        <div data-parallax='{"x": 20, "y": 150, "rotateZ": 180}'>
            <img src="layout/img/icon/banner_icon/animated_banner_5.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_7">
        <div data-parallax='{"x": 100, "y": 250, "rotateZ":0}'>
            <img src="layout/img/icon/banner_icon/animated_banner_15.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_10">
        <div data-parallax='{"x": 15, "y": 150, "rotateZ":0}'>
            <img src="layout/img/icon/banner_icon/animated_banner_10.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_12">
        <div data-parallax='{"x": 20, "y": 150, "rotateZ":180}'>
            <img src="layout/img/icon/banner_icon/animated_banner_20.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_13">
        <div data-parallax='{"x": 10, "y": 250, "rotateZ": 180}'>
            <img src="layout/img/icon/banner_icon/animated_banner_21.png" alt="#">
        </div>
    </div>
</section>
<!-- breadcrumb part end -->

<!-- corses details part -->
<section class="blog_page section_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="single_blog_details">
                    <div class="blog_details_content">
                        <?php
                        if (empty($article['Image'])) {
                            echo "<img class='img-fluid' src='layout/img/icon/arrow_left.svg' alt='article img'>";
                        } else {
                            echo "<img class='img-fluid' src='admin/uploads/image_pic/" . $article['Image'] . "' alt='article img'>";
                        }
                        ?>
                        <h2><?php echo $article['Name'] ?></h2>
                        <p><?php echo $article['Description'] ?></p>

                    </div>
                </div>
                <?php
                if (isset($_SESSION['user'])) {
                    $userid = $_SESSION['userid'];
                    $stmt = $con->prepare("SELECT favorite.*,
                              articles.articleID  AS fav_itm,
                              COUNT(favorite.ID) AS counts
                        FROM 
                            favorite
                        INNER JOIN 
                            articles
                        ON
                            articles.articleID = favorite.article_id     
                        WHERE 
                            user_id = $userid
                         AND article_id = {$article['articleID']}");
                    // Execute Query
                    $stmt->execute();
                    $fav = $stmt->fetch();

                    // two select
                    $stmt2 = $con->prepare("SELECT favorite.*,
                              COUNT(favorite.article_id) AS counts
                        FROM 
                            favorite
                            WHERE 
                            article_id = {$article['articleID']}");
                    // Execute Query
                    $stmt2->execute();
                    $fav2 = $stmt2->fetch();
                    if ($fav['article_id'] == $article['articleID']) {
                        echo '<div class="favorit"><a href="favorit.php?do=remove-favorite&articleid=' . $article['articleID'] . '"><i class="fa-color fas fa-heart fa-lg"></i></a><span class="fav-article"> Likes By ' . $fav2['counts'] . ' Users</span></div>';
                    } else {
                        echo '<div class="favorit"><a href="favorit.php?do=add-favorite&articleid=' . $article['articleID'] . '"><i class="fa-color far fa-heart fa-lg"></i></a><span class="fav-article"> Likes By ' . $fav2['counts'] . ' Users</span></div>';
                    }
                } else {
                    echo '<i class="favorit-icon fa fa-star-o fa-lg"></i> <a href="login.php">Login</a> To Make Favorite article';
                }

                ?>
                <?php
                if (isset($_SESSION['user'])) { ?>
                    <div class="review_form blog_page_single_item">
                        <h3>Leave a Reply</h3>
                        <form action="<?php $_SERVER['PHP_SELF'] . '?articleid=' . $article['articleID'] ?>" method="POST">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form_single_item">
                                        <textarea name="comment" placeholder="Review Content"></textarea>
                                    </div>
                                </div>
                            </div>
                            <input class="cu_btn btn_2" type="submit" value="Submit Review">
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $articleid = $article['articleID'];
                            $userid = $_SESSION['userid'];
                            if (!empty($comment)) {
                                $stmt = $con->prepare("INSERT INTO 
                                                            comments(Comment, Status, Comment_date, article_id, User_id)
                                                        VALUES(:zcomment, 0, NOW(), :zarticleid, :zuserid)");
                                $stmt->execute(array(
                                    'zcomment' => $comment,
                                    'zarticleid'  => $articleid,
                                    'zuserid'  => $userid
                                ));
                                if ($stmt) {
                                    $theMsg =  '<div class="alert alert-success">Comment Added</div>';
                                    redirectHome($theMsg, 'back');
                                }
                            }
                        }
                        ?>
                    </div>
                <?php } else {
                    echo '<a href="login.php">Login</a> or <a href="logout.php">Register</a> To Add Comment';
                } ?>
            </div>
        </div>
        <div class="single_review_part blog_page_single_item">
            <?php
            $stmt = $con->prepare("SELECT 
                                        comments.*, users.Username AS Member,
                                        users.profile AS profile,
                                        COUNT(Comment) AS counts
                                    FROM 
                                        comments
                                    INNER JOIN
                                        users
                                    ON
                                        users.UserID = comments.User_id
                                    WHERE 
                                        article_id = ? 
                                    AND
                                        Status = 1       
                                    ORDER BY C_id DESC");
            $stmt->execute(array($article['articleID']));
            $comments = $stmt->fetchAll();
            foreach ($comments as $comment) { ?>
                <h3>1 Comment</h3>
                <div class="comment_part">
                    <div class="media">
                        <?php
                        if (empty($comment['profile'])) {
                            echo "<img class='img-responsive img-thumbnail img-circle center-block' src='layout/img/student.png' alt='Profile Picture'>";
                        } else {
                            echo "<img class='img-responsive img-thumbnail img-circle center-block' src='admin/uploads/profile_pic/" . $comment['profile'] . "' alt='Profile Picture'>";
                        }
                        ?>
                        <div class="media-body">
                            <div class="admin_tittle">
                                <h5><?php echo strtoupper($comment['Member']); ?> <span><?php echo $comment['Comment_date']; ?> </span></h5>
                            </div>
                            <p><?php echo $comment['Comment'] ?></p>
                        </div>
                    </div>
                </div>
        </div>
    <?php } ?>
    </div>
    </div>
</section>
<!-- corses details part end -->
<?php
include $tpl . 'footer.php';
ob_end_flush();
