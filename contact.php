<?php
include 'init.php';
?>
<!-- breadcrumb part -->
<section class="breadcrumb_part parallax_bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 ">
                <div class="breadcrumb_iner">
                    <h2>Contact</h2>
                    <div class="breadcrumb_iner_link">
                        <a href="index-2.html">Home</a>
                        <span>|</span>
                        <p> Contact</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- breadcrumb part end -->

<!-- contact page -->
<section class="contact_page section_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="contact_form form_style">
                    <h2 class="kid_title mb-4"> <span class="title_overlay_effect">We`re here to Help You</span></h2>
                    <form action="#">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form_single_item">
                                    <input type="text" name="#" placeholder="Your Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form_single_item">
                                    <input type="email" name="#" placeholder="Your Phone">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form_single_item">
                                    <input type="text" name="#" placeholder="Your Email">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form_single_item">
                                    <input type="email" name="#" placeholder="Website URL">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form_single_item">
                                    <textarea name="#" placeholder="Review Content"></textarea>
                                </div>
                            </div>
                        </div>
                        <a class="pc-button elementor-button button-link cu_btn" href="#">
                            <div class="button-content-wrapper">
                                <span class="elementor-button-text">Send Message</span>
                                <svg class="pc-dashes inner-dashed-border animated-dashes">
                                    <rect x="5px" y="5px" rx="25px" ry="25px" width="0" height="0"></rect>
                                </svg>
                            </div>
                        </a>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 pl-lg-5">
                <div class="blog_sidebar">
                    <div class="contact_sidebar">
                        <h2 class="kid_title mb-4"> <span class="title_overlay_effect">Office Info</span></h2>
                        <div class="single_contact_sidebar">
                            <i class="icon_pin"></i>
                            <div class="contact_sidevar_content">
                                <h5>Location</h5>
                                <p>9 Road, Mirpur Dohs, <br>
                                    New York</p>
                            </div>
                        </div>
                        <div class="single_contact_sidebar">
                            <i class="icon_phone"></i>
                            <div class="contact_sidevar_content">
                                <h5>Phone</h5>
                                <p>+000 1111 222</p>
                            </div>
                        </div>
                        <div class="single_contact_sidebar">
                            <i class="icon_mail"></i>
                            <div class="contact_sidevar_content">
                                <h5>Email</h5>
                                <p>parenting_bot@kidzo.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="social_icon">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/"><i class="ti-instagram"></i></a>
                        <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- contact page end -->
<?php
include $tpl . 'footer.php';
