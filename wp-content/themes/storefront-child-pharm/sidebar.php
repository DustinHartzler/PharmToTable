<!-- Start Sidebar -->
<div class="sidebar col-md-4">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
    <?php endif;?>
    <aside>

        <div class="sidebar-item social-sidebar">
            <div class="title">
                <h4>follow us</h4>
            </div>
            <div class="sidebar-info">
                <ul>
                    <li class="facebook">
                        <a href="#">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li class="instagram">
                        <a href="#">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </aside>
</div>
<!-- End Start Sidebar -->