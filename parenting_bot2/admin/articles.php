<?php
/*
    ================================================
    == articles Page
    ================================================
    */
ob_start(); // Output Buffering Start
session_start();
$pageTitle = 'articles';
if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        $stmt = $con->prepare("SELECT 
                                    articles.*,categories.Name AS category_name,
                                    users.Username AS member_name
                                FROM 
                                    articles
                                INNER JOIN
                                    categories
                                ON
                                    categories.ID = articles.Cat_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = articles.Member_ID
                                ORDER BY articleID DESC");
        // Execute The Statement
        $stmt->execute();
        // Assign To Variable
        $articles = $stmt->fetchAll();
        if (!empty($articles)) {
?>

            <h1 class="text-center">Manage article</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-articles text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>article Image</td>
                            <td>Title</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($articles as $article) {
                            echo "<tr>";
                            echo "<td>" . $article['articleID'] . "</td>";
                            echo "<td>";
                            if (empty($article['Image'])) {
                                echo "<img src='uploads/image_pic/images.jpg' alt='article img'>";
                            } else {
                                echo "<img src='uploads/image_pic/" . $article['Image'] . "' alt='article img'>";
                            }
                            echo "</td>";
                            echo "<td>" . $article['Name'] . "</td>";
                            echo "<td>" . $article['Add_Date'] . "</td>";
                            echo "<td>" . $article['category_name'] . "</td>";
                            echo "<td>" . $article['member_name'] . "</td>";
                            echo "<td>
                                <a href='articles.php?do=Edit&articleid=" . $article['articleID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                <a href='articles.php?do=Delete&articleid=" . $article['articleID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                            if ($article['Approve'] == 0) {
                                echo "<a href='articles.php?do=Approve&articleid=" . $article['articleID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i></a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <a href="articles.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New article</a>
                <a href="dashboard.php" class="btn btn-primary btn-sm" style="margin-left:50px;background-color:#e74c3c;border:none"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        <?php } else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No articles To Manage</div>';
            echo '<a href="articles.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New article</a>';
            echo '</div>';
        }
        ?>
    <?php
    } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New article</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="post" enctype="multipart/form-data">
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" required placeholder="Name of The article">
                    </div>
                </div>
                <!-- End Name Field -->
                
                <!-- Start Tags Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="tags" class="form-control" required placeholder="Separate Tags With Comma (,)">
                    </div>
                </div>
                <!-- End Tags Field -->

                <!-- Start Members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
                            $allMembers = getAllFrom("*", "users", "", "", "UserID");
                            foreach ($allMembers as $user) {
                                echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Members Field -->
                
                <!-- Start Categories Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                            $allCats = getAllFrom("*", "categories", "where Parent = 0", "", "ID");
                            foreach ($allCats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $childCats = getAllFrom("*", "categories", "where Parent = {$cat['ID']}", "", "ID");
                                foreach ($childCats as $child) {
                                    echo "<option value='" . $child['ID'] . "'>----  " . $child['Name'] . " Sub Category from " . $cat['Name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Categories Field -->
               
                <!-- Start Article Edition-->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Write Article</label>
                    <div class="col-sm-10 col-md-6">
                        <textarea name = 'editor'></textarea>
                    </div>
                </div>
                <!-- End Article Edition-->

                <!-- Start Profile Picture Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">article Image</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>
                    <!-- End Profile Picture Field -->


                
                <!-- Start submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add article" class="btn btn-primary btn-sm">
                        <a href="dashboard.php" class="btn btn-primary btn-sm" style="margin-left:50px;background-color:#e74c3c;border:none"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <!-- End submit Field -->
                
            </form>
        </div>
        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<h1 class="text-center">Insert article</h1>';
            echo "<div class= 'container'>";
            // Upload Variable
            $imageName      = $_FILES['image']['name'];
            $imageSize      = $_FILES['image']['size'];
            $imageTmp       = $_FILES['image']['tmp_name'];
            $imageType      = $_FILES['image']['type'];
            // list of file to upload 
            $imgAllowExt = array("jpeg", "jpg", "png", "gif");
            // get variable extension
            $img_parts = explode('.', $_FILES['image']['name']);
            $imgExt = strtolower(end($img_parts));
            // Get Variables From The Form
            $name       = $_POST['name'];
            $desc       = $_POST['editor'];
            $member     = $_POST['member'];
            $cat        = $_POST['category'];
            $tags       = $_POST['tags'];
            // Validate The Form
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'Name Cant be <strong>Empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'Content Cant be <strong>Empty</strong>';
            }
            if ($member == 0) {
                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }
            if ($cat == 0) {
                $formErrors[] = 'You Must Choose The <strong>Category</strong>';
            }
            if (!empty($imageName) && !in_array($imgExt, $imgAllowExt)) {
                $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
            }
            if (empty($imageName)) {
                $formErrors[] = 'Profile Picture Is <strong>Required</strong>';
            }
            if ($imageSize > 4194304) {
                $formErrors[] = 'Profile Picture Cant Be Larger Than <strong>4MB</strong>';
            }
            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // check If There 's No Error Proceed The Update Operation
            if (empty($formErrors)) {
                $image = rand(0, 10000) . '_' . $imageName;
                move_uploaded_file($imageTmp, "uploads\image_pic\\" . $image);
                // Insert Userinfo In Database
                $stmt = $con->prepare("INSERT INTO
                                articles(Name, Description, Add_Date, Member_ID, Cat_ID, tags, Image)
                                VALUES(:zname, :zdesc, now(),:zmember, :zcat, :ztags, :zimage)");
                $stmt->execute(array(
                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zmember'   => $member,
                    'zcat'      => $cat,
                    'ztags'     => $tags,
                    'zimage'     => $image
                ));
                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                redirectHome($theMsg, "back");
            }
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">Sorry You cant Browse This Page Directly</div>';
            redirectHome($theMsg);
            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Edit') {
        // Check If Get Request article Is numeric & Get The Integer Value Of It
        $articleid = isset($_GET['articleid']) && is_numeric($_GET['articleid']) ? intval($_GET['articleid']) : 0;
        // Select All Data Depend On This ID
        $stmt = $con->prepare("SELECT * FROM articles WHERE articleID = ?");
        // Execute Query
        $stmt->execute(array($articleid));
        // Fetch The Data
        $article = $stmt->fetch();
        // The Row Count
        $count = $stmt->rowCount();
        // If There's Such ID Show The Form
        if ($count > 0) { ?>
            <h1 class="text-center">Edit article</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    <input type="hidden" name="articleid" value="<?php echo $articleid ?>">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" required placeholder="Name of The article" value="<?php echo $article['Name'] ?>">
                        </div>
                    </div>
                    <!-- End Name Field -->
                    
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control" required placeholder="Separate Tags With Comma (,)" value="<?php echo $article['tags'] ?>">
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    
                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="member">
                                <?php
                                $allUsers = getAllFrom("*", "users", "", "", "UserID");
                                foreach ($allUsers as $user) {
                                    echo "<option value='" . $user['UserID'] . "'";
                                    if ($article['Member_ID'] == $user['UserID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $user['Username'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="category">
                                <?php
                                $allCats = getAllFrom("*", "categories", "", "", "ID");
                                foreach ($allCats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'";
                                    if ($article['Cat_ID'] == $cat['ID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $cat['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->

                    <!-- Start Article Edition-->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Write Article</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea name = 'editor'><?php echo $article['Description'] ?></textarea>
                        </div>
                    </div>

                    <!-- End Article Edition-->
                    <!-- Start submit Field -->
                    <div class=" form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Edit article" class="btn btn-primary btn-sm">
                            <a href="dashboard.php" class="btn btn-primary btn-sm" style="margin-left:50px;background-color:#e74c3c;border:none"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <!-- End submit Field -->
                </form>
                <?php
                $stmt = $con->prepare("SELECT
                                            comments.*, users.Username AS Member
                                       FROM
                                            comments
                                       INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.User_id
                                        WHERE article_id = ?");
                $stmt->execute(array($articleid));
                $comments = $stmt->fetchAll();
                if (!empty($comments)) {
                ?>
                    <h1 class="text-center">Manage [ <?php echo $article['Name'] ?> ] Comment</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($comments as $comment) {
                                echo "<tr>";
                                echo "<td>" . $comment['Comment'] . "</td>";
                                echo "<td>" . $comment['Member'] . "</td>";
                                echo "<td>" . $comment['Comment_date'] . "</td>";
                                echo "<td>
                        <a href='comments.php?do=Edit&comid=" . $comment['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='comments.php?do=Delete&comid=" . $comment['C_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                if ($comment['Status'] == 0) {
                                    echo "<a href='comments.php?do=Approve&comid=" . $comment['C_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                <?php } ?>
            </div>
<?php
            // If There's Such ID Show Error Message
        } else {
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>Theres No Such ID</div>";
            redirectHome($theMsg);
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        echo '<h1 class="text-center">Update article</h1>';
        echo "<div class= 'container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From The Form
            $articleid     = $_POST['articleid'];
            $name       = $_POST['name'];
            $desc       = $_POST['editor'];
            $member     = $_POST['member'];
            $cat        = $_POST['category'];
            $tags       = $_POST['tags'];
            // Validate The Form
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'Name Cant be <strong>Empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'Content Cant be <strong>Empty</strong>';
            }
            if ($member == 0) {
                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }
            if ($cat == 0) {
                $formErrors[] = 'You Must Choose The <strong>Category</strong>';
            }
            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // check If There 's No Error Proceed The Update Operation
            if (empty($formErrors)) {
                // Update The Database With This apc_cache_info
                $stmt = $con->prepare("UPDATE articles SET Name = ?, Description = ?, Member_ID = ?, Cat_ID = ?, tags = ?  WHERE articleID = ?");
                $stmt->execute(array($name, $desc, $member, $cat, $tags, $articleid));

                // Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg, 'back');
            }
        } else {
            $theMsg = "<div class='alert alert-danger'>Sorry You Cant Brows This is Page Directly</div>";
            redirectHome($theMsg, 'back');
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Member</h1>';
        echo "<div class= 'container'>";
        // Check If Get Request UserID Is numeric & Get The Integer Value Of It
        $articleid = isset($_GET['articleid']) && is_numeric($_GET['articleid']) ? intval($_GET['articleid']) : 0;
        // Select All Data Depend On This ID
        $check = checkarticle('articleID', 'articles', $articleid);
        // If There's Such ID Show The Form
        if ($check > 0) {
            $stmt = $con->prepare("DELETE FROM articles WHERE articleID = :articleid");
            $stmt->bindParam(':articleid', $articleid);
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
    } elseif ($do == 'Approve') {
        // Approve Member Page
        echo '<h1 class="text-center">Approve article</h1>';
        echo "<div class= 'container'>";
        // Check If Get Request UserID Is numeric & Get The Integer Value Of It
        $articleid = isset($_GET['articleid']) && is_numeric($_GET['articleid']) ? intval($_GET['articleid']) : 0;
        // Select All Data Depend On This ID
        $check = checkarticle('articleID', 'articles', $articleid);
        // If There's Such ID Show The Form
        if ($check > 0) {
            $stmt = $con->prepare("UPDATE articles SET Approve = 1 WHERE articleID = ?");
            $stmt->execute(array($articleid));
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