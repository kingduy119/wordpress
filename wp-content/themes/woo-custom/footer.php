<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="box-footer info-contact">
                    <h3>Thông tin liên hệ</h3>
                    <div class="content-contact">
                        <p>Website chuyên cung cấp thiết bị điện tử hàng đầu Việt Nam</p>
                        <p>
                            <strong>Địa chỉ:</strong> 457/44 Tôn Đức Thắng, Liên Chiểu, Đà Nẵng
                        </p>
                        <p>
                            <strong>Email: </strong> thietkeweb43.com@gmail.com
                        </p>
                        <p>
                            <strong>Điện thoại: </strong> 0358949xxx
                        </p>
                        <p>
                            <strong>Website: </strong> https://huykira.net
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="box-footer info-contact">
                    <h3>Thông tin khác</h3>
                    <div class="content-list">
                            <?php wp_nav_menu(array(
								"theme_location" => "footer-menu",
								"menu_id" => "footer-menu",
								"menu_class" => "footer-menu",
								"container" => false,
							)); ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="box-footer info-contact">
                    <h3>Form liên hệ</h3>
                    <div class="content-contact">
                        <form action="/" method="GET" role="form">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="text" name="" id="" class="form-control" placeholder="Họ và Tên">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                    <input type="email" name="" id="" class="form-control" placeholder="Địa chỉ mail">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                    <input type="text" name="" id="" class="form-control" placeholder="Số điện thoại">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="text" name="" id="" class="form-control" placeholder="Tiêu đề">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn-contact">Liên hệ ngay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <p>Copyright © 2020 HKSHOP All Rights Reserved - Design by THIETKEWEB43.COM</p>
    </div>
</footer>

        </div>

        <script src="<?php bloginfo("stylesheet_directory") ?>/libs/jquery-3.4.1.min.js"></script>
        <script src="<?php bloginfo("stylesheet_directory") ?>/libs/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php bloginfo("stylesheet_directory") ?>/js/main.js"></script>
        <!-- <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v6.0"></script> -->
        
        <div id="fb-root"></div>
        <script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6"></script>
        <?php wp_footer(); ?>
    </body>
</html>