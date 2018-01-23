<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<meta name="keywords" content="木子_培训网" />
	<meta name="description" content="木子_培训网" />
	<link rel="shortcut icon" href="./publics/resource/images/favicon.ico" />


<?php AppAsset::addCss($this,'@web/publics/resource/css/bootstrap.min.css'); ?>
<?php AppAsset::addCss($this,'@web/publics/resource/css/common.css'); ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<!--------------------banner--------------------->
<?php AppAsset::addScript($this,'@web/publics/resource/js/lib/jquery-1.11.1.min.js'); ?>
<?php AppAsset::addScript($this,'@web/publics/resource/js/lib/bootstrap.min.js'); ?>
<?php AppAsset::addScript($this,'@web/publics/resource/js/app/util.js'); ?>
<?php AppAsset::addScript($this,'@web/publics/resource/js/app/common.min.js'); ?>
<?php AppAsset::addScript($this,'@web/publics/resource/js/require.js'); ?>
<!--------------------banner-over--------------------->
</head>

<body>
<?php $this->beginBody() ?>
<?=$content?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>