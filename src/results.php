<?php
const PAGE_NAME = 'results';
require_once 'includes/RedirectionsTester.php';

if(!isset($_POST['host'])){
    header('Location: index.php');
    exit;
}

try{
    $tester = new RedirectionsTester(
        $_POST['host'],
        [
            'https'   => $_POST['https'],
            'www'     => $_POST['www'],
            'index_rewrite'   => $_POST['index-rewrite'],
            'index_rewrite_ext'   => $_POST['index-rewrite-ext'],
            'trail_slash'     => $_POST['trail-slash'],
            'path_remove'     => $_POST['path-remove'],
            'queries_remove'  => $_POST['queries-remove'],
        ],
        $_POST['redirection-host']
    );
    $tester->run();
    $options = RedirectionsTester::getAllowedConfigs();
}catch(Exception $e){
    echo '<b>Fatal Error:</b> '.$e->getMessage().
        ' in <b>'.$e->getTrace()[0]['file'].'</b> on line <b>'.$e->getTrace()[0]['line'].'</b>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Domain Redirections Tester - Results</title>
    <?php require 'includes/partials/head.php' ?>
</head>

<body>
    <?php include 'includes/partials/navbar.php' ?>
    <div class="container-fluid container-body">
        <h1 class="h3 mb-4 text-center">Tests Results</h1>
        <?php
        if(count($tester->errors)>0){
            echo '<ul class="errors">';
            foreach($tester->errors as $error){
                echo '<li class="alert-danger">'.$error.'</li>';
            }
            echo '</ul>';
            echo '<a href="run.php" class="btn btn-outline-danger btn-sm mb-4">Torna indietro</a>';
        }
        ?>
        <div class="accordion" id="accordion-configurations">
            <div class="card">
                <div class="card-header" id="heading-configurations">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapse-configurations" aria-expanded="true"
                                aria-controls="heading-configurations">
                            Show Configurations
                        </button>
                    </h2>
                </div>
                <div id="collapse-configurations" class="collapse" aria-labelledby="heading-configurations"
                     data-parent="#accordion-configurations">
                    <div class="card-body">
                        <ul class="list-configurations">
                            <li>Domain: <b><?= $_POST['host'] ?></b></li>
                            <li>Redirection Domain: <b><?= $_POST['redirection-host'] ?></b></li>
                            <li>HTTPS: <b><?php
                                foreach($options['config_https'] as $opt){
                                    if($opt['label']===$_POST['https']){
                                        echo $opt['title'];
                                    }
                                }?></b>
                            </li>
                            <li>WWW: <b><?php
                                    foreach($options['config_www'] as $opt){
                                        if($opt['label']===$_POST['www']){
                                            echo $opt['title'];
                                        }
                                    }?></b>
                            </li>
                            <li>Hide Index.*: <b><?php
                                    foreach($options['config_index_rewrite'] as $opt){
                                        if($opt['label']===$_POST['index-rewrite']){
                                            echo $opt['title'];
                                        }
                                    }?> (<?= $_POST['index-rewrite-ext'] ?>)</b>
                            </li>
                            <li>Trail Slash: <b><?php
                                    foreach($options['config_trail_slash'] as $opt){
                                        if($opt['label']===$_POST['trail-slash']){
                                            echo $opt['title'];
                                        }
                                    }?></b>
                            </li>
                            <li>Remove Path: <b><?php
                                    foreach($options['config_path_remove'] as $opt){
                                        if($opt['label']===$_POST['path-remove']){
                                            echo $opt['title'];
                                        }
                                    }?></b>
                            </li>
                            <li>Remove Queries: <b><?php
                                    foreach($options['config_queries_remove'] as $opt){
                                        if($opt['label']===$_POST['queries-remove']){
                                            echo $opt['title'];
                                        }
                                    }?></b>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <table id="results_table" class="table table-striped table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">https</th>
                    <th class="text-center">www</th>
                    <th class="text-center">queries</th>
                    <th>Test URL</th>
                    <th>Steps</th>
                    <th class="text-center"># of Steps</th>
                    <th>Steps Time [ms]</th>
                    <th>Final URL</th>
                    <th class="text-center">Final Code</th>
                    <th class="text-center">Result</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($tester->results as $key=>$result){ ?>
                <tr>
                    <td class="text-center"><?= $key+1 ?></td>
                    <td class="text-center">
                        <?= (strpos($result['test_url'],'https://')!==false)?'<i class="fas fa-check"></i>':'' ?>
                    </td>
                    <td class="text-center">
                        <?= (strpos($result['test_url'],'://www.')!==false)?'<i class="fas fa-check"></i>':'' ?>
                    </td>
                    <td class="text-center">
                        <?= (strpos($result['test_url'],'?key=value')!==false)?'<i class="fas fa-check"></i>':'' ?>
                    </td>
                    <td><?= $result['test_url'] ?></td>
                    <td><?php
                        foreach($result['intermediate_data'] as $data){
                            echo $data['http_code'].(($data['redirect_url'])?'-->'.$data['redirect_url']:'').'<br>';
                        }
                        ?>
                    </td>
                    <td class="text-center"><?= $result['n_redirects'] ?></td>
                    <td><?= $result['redirects_time'] ?></td>
                    <td><?= $result['final_url'] ?></td>
                    <td class="text-center <?=
                        (substr($result['final_code'],0,1)==='2')?'text-success':'text-danger' ?>">
                        <?= $result['final_code'] ?>
                    </td>
                    <td class="text-center">
                        <?= ($result['test_result'])
                            ?'<span class="badge badge-success">PASS</span>'
                            :'<span class="badge badge-danger">FAIL</span>' ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php include 'includes/partials/footer.php' ?>
    <?php require 'includes/partials/scripts.php' ?>
</body>
</html>
