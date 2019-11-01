<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Domain Redirections Tester</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?= (PAGE_NAME==='get-started')?'active':'' ?>">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-circle"></i> Getting Started
                    </a>
                </li>
                <li class="nav-item <?= (PAGE_NAME==='instructions')?'active':'' ?>">
                    <a class="nav-link" href="instructions.php">
                        <i class="fas fa-circle"></i> Instructions
                    </a>
                </li>
                <li class="nav-item <?= (PAGE_NAME==='run' || PAGE_NAME==='results')?'active':'' ?>">
                    <a class="nav-link" href="run.php">
                        <i class="fas fa-circle"></i> Run
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>