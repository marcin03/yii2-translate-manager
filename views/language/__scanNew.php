<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.4
 */

/* @var $this \yii\web\View */
/* @var $newDataProvider \yii\data\ArrayDataProvider */

use lajax\translatemanager\helpers\Language;
use yii\grid\GridView;

?>

<?php if ($newDataProvider->totalCount > 0) : ?>

    <?php
    $allCategoriesArray = array_merge(Language::getCategories(), Yii::$app->getModule('translatemanager')->languageSourcesCategoriesWithLabels);
    ?>

    <?=

    GridView::widget([
        'id' => 'added-source',
        'dataProvider' => $newDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'category',
            [
                'attribute' => 'category',
                'label' => 'Kategoria',
                'content' => function($model)use($allCategoriesArray){
                    return $allCategoriesArray[$model['category']];
                }
            ],
            //'message',
            [
                'format' => 'raw',
                'attribute' => 'message',
                'label' => 'Źródło'
            ],
        ],
    ]);

    ?>

<?php endif ?>
