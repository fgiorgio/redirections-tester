<?php const PAGE_NAME = 'get-started' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Domain Redirections Tester</title>
    <?php require 'includes/partials/head.php' ?>
</head>

<body>
    <?php include 'includes/partials/navbar.php' ?>
    <div class="container container-body">
        <div class="card mb-3">
            <div class="card-header"><h1 class="h5 m-0">What is Domain Redirections Tester</h1></div>
            <div class="card-body">
                <p class="card-text">It's a simple utility for Web Developers and SEO Managers that helps testing
                    domain redirections and some URL rewriting rules.</p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><h2 class="h5 m-0">What can it do</h2></div>
            <div class="card-body">
                <p class="card-text">This utility can test every combination of the following server configurations
                    and verify that it behaves as expected:</p>
                <ul>
                    <li>Redirection to another domain</li>
                    <li>HTTPS Protocol</li>
                    <li>WWW prefix</li>
                    <li>Index file hiding</li>
                    <li>Trail slash policy</li>
                    <li>Path hiding</li>
                    <li>Queries hiding</li>
                </ul>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><h2 class="h5 m-0">Results of the tests</h2></div>
            <div class="card-body">
                <p class="card-text">The following informations are returned for every test:</p>
                <ul>
                    <li><b>#</b> - Test progressive number starting from 1.</li>
                    <li><b>https</b> - Checked if test URL uses HTTPS protocol</li>
                    <li><b>www</b> - Checked if test URL uses WWW prefix</li>
                    <li><b>queries</b> - Checked if test URL contains query strings</li>
                    <li><b>Test URL</b> - URL of the requested resource. It's a single combination of server
                        configurations to test.</li>
                    <li><b>Steps</b> - HTTP code and URL of every redirection step</li>
                    <li><b>Number of steps</b> - Number of redirection steps made</li>
                    <li><b>Steps time</b> - Time elapsed for all redirection steps</li>
                    <li><b>Final URL</b> - Final URL reached after redirection steps</li>
                    <li><b>Final code</b> - Final HTTP code obtained after redirection steps. Note: a different code
                        from 2xx does not mean that the test failed.</li>
                    <li><b>Result</b> - Result of the test regarding the specified server configurations.</li>
                </ul>
            </div>
        </div>
    </div>
    <?php include 'includes/partials/footer.php' ?>
    <?php require 'includes/partials/scripts.php' ?>
</body>
</html>