<?php
const PAGE_NAME = 'run';
require_once 'includes/RedirectionsTester.php';
$options = RedirectionsTester::getAllowedConfigs();
function displayOptions(Array $options, String $config){
    foreach($options[$config] as $option){
        echo '<option value="'.$option['label'].'">'.$option['title'].'</option>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Domain Redirections Tester - Configure and Run</title>
    <?php require 'includes/partials/head.php' ?>
</head>

<body>
    <?php include 'includes/partials/navbar.php' ?>
    <div class="container container-body">
        <h1 class="h3 mb-4 text-center">Configure and Run Tests</h1>
        <form action="results.php" method="POST">
            <div class="card mb-3">
                <div class="card-header"><label for="host" class="m-0">Domain Name</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <input type="text" class="form-control" id="host" name="host"
                                       placeholder="Ex. domain-name.com" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <p class="m-0">The domain name to test. Please do not enter any protocol,
                                path or query string.</p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="my-4 text-center">The following rules must reflect the web server configurations you are going
                to test.</p>
            <div class="card mb-3">
                <div class="card-header"><label for="redirection-host" class="m-0">Redirection Domain Name</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <input type="text" class="form-control" id="redirection-host" name="redirection-host"
                                       placeholder="Ex. domain-name.com" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <p class="m-0">The (optional) domain name to which requests are redirected to. Please do
                                not enter any protocol, path or query string.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><label for="https" class="m-0">HTTPS Protocol</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <select class="form-control" id="https" name="https">
                                    <?php displayOptions($options,'config_https') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <ul class="pl-3">
                                <li><u>Forced</u> - HTTPS protocol is always forced</li>
                                <li><u>Enabled</u> - HTTPS protocol is enabled but not forced</li>
                                <li><u>Disabled</u> - HTTPS protocol is disabled</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><label for="www" class="m-0">WWW Prefix</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <select class="form-control" id="www" name="www">
                                    <?php displayOptions($options,'config_www') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <ul class="pl-3">
                                <li><u>Forced Without</u> - The server removes the www prefix if present</li>
                                <li><u>Forced With</u> - The server adds the www prefix if not present</li>
                                <li><u>Not Forced</u> - The server doesn't apply any rule to www prefix</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><label for="index-rewrite" class="m-0">Hide index.*</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <select class="form-control" id="index-rewrite" name="index-rewrite">
                                    <?php displayOptions($options,'config_index_rewrite') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <ul class="pl-3">
                                <li><u>Always</u> - The server removes index.* file name if present</li>
                                <li><u>Never</u> - The server doesn't remove index.* file name if present</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <label for="index-rewrite-ext">Extensions</label>
                                <input type="text" class="form-control"
                                       id="index-rewrite-ext" name="index-rewrite-ext" placeholder="Ex. html, php"
                                       autocomplete="off" value="html, php">
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <p class="m-0">The index.* extensions which this rule is applied to. Separate extensions
                                with a white space or whatever non alphanumerical character.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><label for="trail-slash" class="m-0">Trail Slash</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <select class="form-control" id="trail-slash" name="trail-slash">
                                    <?php displayOptions($options,'config_trail_slash') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <ul class="pl-3">
                                <li><u>Forced for real directories</u> - The server adds a trail slash only if the URL
                                    points to a real directory, otherwise it does nothing</li>
                                <li><u>Forced if not real file</u> - The server adds a trail slash everytime URL points
                                    to something that's not a real file</li>
                                <li><u>Never Forced</u> - The server doesn't apply any rule to trail slash</li>
                                <li><u>Always Forced</u> - The server adds always a trail slash to URL (every URL is
                                    considered as a directory)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><label for="path-remove" class="m-0">Remove Path</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <select class="form-control" id="path-remove" name="path-remove">
                                    <?php displayOptions($options,'config_path_remove') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <ul class="pl-3">
                                <li><u>Never</u> - The server never removes the path in the URL</li>
                                <li><u>Always</u> - The server always removes the path in the URL</li>
                                <li><u>Only on domain redirection</u> - The server removes the path in the URL only
                                    on domain redirection</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><label for="queries-remove" class="m-0">Remove Queries</label></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <select class="form-control" id="queries-remove" name="queries-remove">
                                    <?php displayOptions($options,'config_queries_remove') ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7 ml-auto align-self-center mt-2 mt-md-0">
                            <ul class="pl-3">
                                <li><u>Never</u> - The server never removes queries in the URL</li>
                                <li><u>Always</u> - The server always removes queries in the URL</li>
                                <li><u>Only on domain redirection</u> - The server removes queries in the URL only
                                    on domain redirection</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center py-5">
                    <span class="lead">You're almost done</span><br>
                    <button type="submit" class="btn btn-primary mt-2">Run Tests</button>
                </div>
            </div>
        </form>
    </div>
    <?php include 'includes/partials/footer.php' ?>
    <?php require 'includes/partials/scripts.php' ?>
</body>
</html>
