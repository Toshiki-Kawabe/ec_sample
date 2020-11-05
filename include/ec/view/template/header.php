<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="<?php isset($_SESSION['user_id']) && $_SESSION['user_id']==='admin' ? print 'goods_mng.php' : print 'ec_shop.php' ?>">EC_site</a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
  <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['user_id']) === TRUE) { ?>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['user_id']) === TRUE && $_SESSION['user_id'] !== 'admin') { ?>
            <li class="nav-item">
                <a class="nav-link" href="cart.php">Shopping Cart</a>
            </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<!--
<header>
    <div id="title">
        <p class="title">EC_site</p>
    </div>
    <?php// if (isset($_SESSION['user_id']) === TRUE) { ?>
    <div id="logout">
        <a href="logout.php" class="logout">ログアウト</a>
    </div>
    <?php// } ?>
</header>
-->