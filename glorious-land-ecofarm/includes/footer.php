<footer class="footer">

    <div class="container">

        <div class="footer-grid">

            <div>

                <h4>
                    Glorious Land Ecofarm
                </h4>

                <p>
                    Native Tree Database System
                </p>

                <span>
                    A digital system for organizing
                    and documenting native tree information.
                </span>

            </div>

            <div>

                <h4>
                    Quick Links
                </h4>

                <a href="index.php">
                    Home
                </a>

                <a href="trees.php">
                    Tree Database
                </a>

                <a href="map.php">
                    Tree Map
                </a>

            </div>

            <div>

                <h4>
                    System
                </h4>

                <a href="about.php">
                    About
                </a>

                <?php if (isset($_SESSION['admin_id'])): ?>

                    <a href="admin.php">
                        Admin Dashboard
                    </a>

                    <a href="logout.php">
                        Logout
                    </a>

                <?php else: ?>

                    <a href="login.php">
                        Admin Login
                    </a>

                <?php endif; ?>

            </div>

        </div>

        <div class="footer-bottom">

            © <?= date('Y') ?> Glorious Land Ecofarm.
            All rights reserved.

        </div>

    </div>

</footer>