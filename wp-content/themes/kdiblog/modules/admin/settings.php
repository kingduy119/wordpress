<?php

if( ! class_exists( 'KDI_Admin' ) ) {
    class KDI_Admin {
        public function __construct() {
            
            add_action( 'login_enqueue_scripts', array( $this, 'css_login_page' ) );
        }

        public function css_login_page() { ?>
            <style type="text/css">

                #login .privacy-policy-page-link,
                h1 { 
                    display: none; 
                }

                #login::after {
                    content: '';
                    display: block;
                    position: absolute;
                    
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    z-index: -10;
                    width: 100%;
                    height: 100%;
                    background-image: url("<?php echo assets('images/bg-login.jpg'); ?>");
                    background-repeat: no-repeat;
                    background-size: 100%;
                }

                #login #loginform {
                    background-color: transparent;
                    color: white;
                    border: none;
                    padding-left: 0;
                    padding-right: 0;
                }

                #login #backtoblog,
                #login #nav {
                    border-left: 4px solid #72aee6;
                    border-radius: 3px;
                    padding: 12px;
                    margin-left: 0;
                    margin-bottom: 20px;
                    background-color: #fff;
                    box-shadow: 0 1px 1px 0 rgb(0 0 0 / 10%);
                    word-wrap: break-word;
                }
            </style>
        <?php }
    }

    new KDI_Admin();
}



