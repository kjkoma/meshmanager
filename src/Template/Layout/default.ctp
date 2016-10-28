<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'MESHダッシュボード';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="ja">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="default-style" content="css/normalise.css">
    <meta name="description" content="Mesh ダッシュボード">
    <meta name="robots" content="noindex,nofollow">
    <meta name="author" content="Japan Computer Services, Inc">

    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('bootstrap-theme.min.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('jquery-ui.min.css') ?>
    <?= $this->Html->css('jquery-ui.structure.min.css') ?>
    <?= $this->Html->css('jquery-ui.theme.min.css') ?>
    <?= $this->Html->css('remodal.css') ?>
    <?= $this->Html->css('remodal-default-theme.css') ?>
    <?= $this->Html->css('layout.css') ?>
    <?= $this->Html->css('app.css') ?>

    <?= $this->Html->script('jquery-2.2.3.min.js') ?>
    <?= $this->Html->script('jquery-ui.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('remodal.min.js') ?>
    <?= $this->Html->script('app.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <!-- ** main ** -->
    <div id="wrapper">
      <div class="container-fluid">
      <nav class="navbar navbar-inverse">
          <div class="navbar-header">
              <div class="navbar-brand" style="color: white">センサー インフォメーションボード</div>
          </div>
      </nav>

        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
      </div>
    </div>

    <!-- ** footer ** -->
    <div id="footer">
      <div class="container-fluid text-center">
        <p>Copyrights © 2016 Japan Computer Services, Inc</p>
      </div>
    </div>

    <script>
      if ('<?= $this->fetch('pageFocus') ?>' == '') {
        $('input:visible').first().focus();
      } else {
        $('<?= $this->fetch('pageFocus') ?>').focus();
      }
    </script>

</body>
</html>
