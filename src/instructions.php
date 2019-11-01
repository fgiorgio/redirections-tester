<?php const PAGE_NAME = 'instructions' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Domain Redirections Tester - Instructions</title>
    <?php require 'includes/partials/head.php' ?>
</head>

<body>
    <?php include 'includes/partials/navbar.php' ?>
    <div class="container container-body">
        <div class="card mb-3">
            <div class="card-header"><h1 class="h5 m-0">Instructions</h1></div>
            <div class="card-body">
                <p class="card-text">Before running the tests there are some steps you need to complete.</p>
                <p>To give you the correct test results for index file hiding rule and trail slash policy there's need
                    that some files are really reachable on your domain. Complete the steps below:</p>
                <ol>
                    <li>Download <a href="drt_test_content.zip">this</a> archive</li>
                    <li>Unzip the archive and upload it in the root of the domain to test</li>
                    <li>Add other <code>index.*</code> files inside <code>real_dir</code> folder if you want to test
                        other extensions</li>
                    <li>You should have the current directory tree:
                        <figure class="highlight m-0">
                            <pre class="m-0">
                                <code class="language-text" data-lang="text">
root/
└── real_dir/
    ├── index.html
    ├── index.php
    ├── index.*
    └── real_file.txt</code>
                            </pre>
                        </figure>
                    </li>
                    <li>Go to <a href="run.php">Run</a> page and set up tests</li>
                </ol>
            </div>
        </div>
    </div>
    <?php include 'includes/partials/footer.php' ?>
    <?php require 'includes/partials/scripts.php' ?>
</body>
</html>