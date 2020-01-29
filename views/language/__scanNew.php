<?php
/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.4
 */

/* @var $this \yii\web\View */
/* @var $newDataProvider \yii\data\ArrayDataProvider */

use yii\grid\GridView;

?>

<?php if ($newDataProvider->totalCount > 0) : ?>

    <?=

    GridView::widget([
        'id' => 'added-source',
        'dataProvider' => $newDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'category',
            [
                'attribute' => 'category',
                'label' => 'Kategoria'
            ],
            //'message',
            [
                'attribute' => 'message',
                'label' => 'Źródło'
            ],
        ],
    ]);

    ?>

<?php endif ?>
