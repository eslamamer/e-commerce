    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><?php echo lang('Admin') ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="categories.php"><?php echo lang('CAT') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="items.php"><?php echo lang('I') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php"><?php echo lang('M') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="comments.php"><?php echo lang('C') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="statistics.php"><?php echo lang('S') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logs.php"><?php echo lang('L') ?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['username'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../index.php">visit shop</a></li>
                            <li><a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['userID'] ?>">Edit</a></li>
                            <li><a class="dropdown-item" href="members.php?do=Settings">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
