<?php

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 *
 * @since 1.0
 */
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use lajax\translatemanager\helpers\Language;
use lajax\translatemanager\models\Language as Lang;
use dosamigos\ckeditor\CKEditor;

/* @var $this \yii\web\View */
/* @var $language_id string */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel lajax\translatemanager\models\searches\LanguageSourceSearch */
/* @var $searchEmptyCommand string */

$this->title = Yii::t('language', 'Translation into {language_id}', ['language_id' => $language_id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::hiddenInput('language_id', $language_id, ['id' => 'language_id', 'data-url' => Yii::$app->urlManager->createUrl('/translatemanager/language/save')]); ?>
<div id="translates" class="<?= $language_id ?>">
    <?php
    Pjax::begin([
        'id' => 'translates',
    ]);
    $form = ActiveForm::begin([
        'method' => 'get',
        'id' => 'search-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]);
    echo $form->field($searchModel, 'source')->dropDownList(['' => Yii::t('language', 'Original')] + Lang::getLanguageNames(true))->label(Yii::t('language', 'Source language'));
    ActiveForm::end();
    $allCategoriesArray = array_merge(Language::getCategories(), Yii::$app->getModule('translatemanager')->languageSourcesCategoriesWithLabels);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'filter' => $allCategoriesArray,
                'attribute' => 'category',
                'filterInputOptions' => ['class' => 'form-control', 'id' => 'category'],
                'value' => function($model)use($allCategoriesArray){
                  return $allCategoriesArray[$model->category];
                }
            ],
            //[
            //    'format' => 'html',
            //    'attribute' => 'message',
            //    'filterInputOptions' => ['class' => 'form-control', 'id' => 'message'],
            //    //'visible'=>false,
            //    'label' => Yii::t('language', 'Source'),
            //    'content' => function ($data) {
            //        return Html::textarea('LanguageSource[' . $data->id . ']', $data->source, ['class' => 'form-control source', 'readonly' => 'readonly']);
            //    },
            //],
            //[
            //    'format' => 'html',
            //    'attribute' => 'message',
            //    'filterInputOptions' => ['class' => 'form-control', 'id' => 'message'],
            //    //'visible'=>false,
            //    'label' => Yii::t('language', 'Source'),
            //    'content' => function ($data) {
            //        return Html::tag('LanguageSource[' . $data->id . ']', $data->source, ['class' => '', 'readonly' => 'readonly']);
            //    },
            //],
            //'message:html',
            [
                'format' => 'html',
                'attribute' => 'message',
                'content' => function ($data) {
                    return  $data->source;
                },
            ],
            [
                'format' => 'raw',
                'attribute' => 'translation',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'id' => 'translation',
                    'placeholder' => $searchEmptyCommand ? Yii::t('language', 'Enter "{command}" to search for empty translations.', ['command' => $searchEmptyCommand]) : '',
                ],
                'label' => Yii::t('language', 'Translation'),
                'contentOptions' => [
                    //'style' => 'min-width:350px',
                    'class' => 'translation-column'
                ],
                'content' => function ($data) {

                    $ckEditorInsteadTextarea = Yii::$app->getModule('translatemanager')->ckEditorInsteadTextarea;
                    $textToCheck = $data->source;

                    if ($ckEditorInsteadTextarea===true||($ckEditorInsteadTextarea==='only-if-detect-html-tags'&&$textToCheck != strip_tags($textToCheck))) {
                        $ckEditorConfigArrayClient = Yii::$app->getModule('translatemanager')->ckEditorConfigArray;
                        $ckEditorConfigArrayDefault = [
                            'name' => "LanguageTranslate[{$data->id}]",
                            'value' => $data->translation,
                            'options' => [
                                'class' => 'form-control translation',//this must be "translation"
                                'data-id' => $data->id,
                                'tabindex' => $data->id
                            ],
                            'clientOptions' => [
                                'height' => 100,
                                'width' => "100%",
                                'class' => 'form-control translation',
                                'data-id' => $data->id,
                                'tabindex' => $data->id
                            ],
                        ];
                        $ckEditorConfigArray = array_replace_recursive($ckEditorConfigArrayDefault, $ckEditorConfigArrayClient);
                        return CKEditor::widget($ckEditorConfigArray);
                    } else {
                        return Html::textarea('LanguageTranslate[' . $data->id . ']', $data->translation, ['class' => 'form-control translation', 'data-id' => $data->id, 'tabindex' => $data->id]);
                    }
                },
            ],
            [
                'format' => 'raw',
                'label' => Yii::t('language', 'Action'),
                'content' => function ($data) {
                    return Html::button(Yii::t('language', 'Save'), ['type' => 'button', 'data-id' => $data->id, 'class' => 'btn btn-lg btn-success']);
                },
            ],
        ],
    ]);
    Pjax::end();
    ?>

</div>