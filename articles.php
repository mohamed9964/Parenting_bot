<?php
ob_start();
session_start();
include 'init.php';
?>
<section class="breadcrumb_part parallax_bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 ">
                <div class="breadcrumb_iner">
                    <h2>Articles</h2>
                    <div class="breadcrumb_iner_link">
                        <a href="index-2.html">Home</a>
                        <span>|</span>
                        <p>Articles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="breadcrumb_animation_4">
        <div data-parallax="{&quot;x&quot;: 30, &quot;y&quot;: 250, &quot;rotateZ&quot;:0}">
            <img src="layout/img/icon/banner_icon/animated_banner_4.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_5">
        <div data-parallax="{&quot;x&quot;: 20, &quot;y&quot;: 150, &quot;rotateZ&quot;: 180}">
            <img src="layout/img/icon/banner_icon/animated_banner_5.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_7">
        <div data-parallax="{&quot;x&quot;: 100, &quot;y&quot;: 250, &quot;rotateZ&quot;:0}">
            <img src="layout/img/icon/banner_icon/animated_banner_15.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_10">
        <div data-parallax="{&quot;x&quot;: 15, &quot;y&quot;: 150, &quot;rotateZ&quot;:0}">
            <img src="layout/img/icon/banner_icon/animated_banner_10.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_12">
        <div data-parallax="{&quot;x&quot;: 20, &quot;y&quot;: 150, &quot;rotateZ&quot;:180}">
            <img src="layout/img/icon/banner_icon/animated_banner_20.png" alt="#">
        </div>
    </div>
    <div class="breadcrumb_animation_13">
        <div data-parallax="{&quot;x&quot;: 10, &quot;y&quot;: 250, &quot;rotateZ&quot;: 180}">
            <img src="layout/img/icon/banner_icon/animated_banner_21.png" alt="#">
        </div>
    </div>
</section>
<section class="success_story section_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section_tittle_style_02">
                    <h2 class="title wow fadeInDown" data-wow-delay=".3s"> <span class="title_overlay_effect"> Our Latest Articles</span></h2>
                    <p class="description wow fadeInDown" data-wow-delay=".3s">We have highly qualified content makers to write articles on children's problems to help parents solve their children's problems..</p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
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
                                    ORDER BY articleID DESC");
            $stmt->execute(array());
            $allarticles = $stmt->fetchAll();
            foreach ($allarticles as $article) {
            ?>
                <div class="col-lg-4 col-sm-6 wow fadeInDown" data-wow-delay=".3s">
                    <div class="page_blog_section_wrapper">
                        <a href="#" class="blog_thumbnail">
                            <?php
                            if (empty($article['Image'])) {
                                echo "<img class='img-fluid' src='layout/img/icon/arrow_left.svg' alt='article img'>";
                            } else {
                                echo "<img class='img-fluid' src='admin/uploads/image_pic/" . $article['Image'] . "' alt='article img'>";
                            }
                            ?>
                        </a>
                        <div class="blog_meta_list">
                            <a class="blog_data"><?php echo $article['Add_Date'] ?></a>
                            <a class="blog_author"><span class="fa fa-user"> <?php echo $article['Member'] ?></span></a>
                        </div>
                        <h4><?php echo '<a href="article_single.php?articleid=' . $article["articleID"] . '">';
                            $title = $article['Name'];
                            echo substr($title, 0, 30); ?></a></h4>
                        <p class="custom-article"><?php $content = strip_tags($article['Description']);
                                                    echo substr($content, 0, 95); ?> ....</p>
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
                        <?php echo '<a href="article_single.php?articleid=' . $article["articleID"] . '" class="read_more_btn">';  ?>
                        Read More <img src="layout/img/icon/arrow_left.svg" alt="#"></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- Bolg part end -->
<?php
include $tpl . 'footer.php';

