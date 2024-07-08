<!--header-->
            <header class="header about-header fixed" id="main-header">
                <div class="container-fluid">
                    <div class="row">
                        <!--logo-->
                        <div class="col-md-3">
                            <a href="https://<?php echo WEBSITE_MAIN_DOMAIN_NAME;?>" class="logo">
                                <img src="/images/logo.jpg" alt="Deal Expert" />
                            </a>
                            <!-- <a href="javascript:;" class="next"> <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="menu-arrow"></span>
                            </button></a> -->
							<a href="tel:1300359779" class="about-phone"> <i class="fa fa-phone"></i></a>
                        </div>
                        <?php if ($this->request->params['action'] != 'personal_information'):?>
                        <div class="col-md-6">
                        </div>
                        <?php endif;?>
                        <!--phone number-->
                        <div class="col-md-3 col-lg-3 col-sm-4 hidden-xs pull-right">
                            <div class="blue-text right-nubmers">
                                <span>Speak to us now</span>
                                <h2 class="fontBold"><a href="tel:1300087011"><i class="fa fa-phone"></i>  1300 087 011</a>  </h2>
                                <span>Speak to us in under<br>60 seconds</span>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </header>