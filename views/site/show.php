<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\themovieDb;

$this->title = 'Main';
?>
<div class="site-contact">

<?php

$dataProvider = new ActiveDataProvider([
    'query' => themovieDb::find()->where(['id' => $id]),
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
       ['class' => 'yii\grid\SerialColumn'],
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'id',
       ],
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'title',
       ],
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'release_date',
       ], 
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'original_title',
       ], 
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'overview',
       ],
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'poster_path',
           'format' => 'raw',
           'value' => function ($model) { 
                return Html::img('\uploads'.$model->poster_path);
           },
       ], 
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'sort',
       ],
       [
           'class' => 'yii\grid\DataColumn',
           'label' => 'Edit',
           'format' => 'raw',
           'value' => function ($model) { 
                return Html::a('edit', ['edit', 'id' => $model->id]);
           },
       ],
       [
           'class' => 'yii\grid\DataColumn',
           'label' => 'Delete',
           'format' => 'raw',
           'value' => function ($model) { 
                return Html::a('delete', ['delete', 'id' => $model->id]);
           },
       ],
       [
           'class' => 'yii\grid\DataColumn',
           'label' => 'Rate',
           'format' => 'raw',
           'value' => function ($model) { 
                return Html::a('rate '.$model->rate, ['rate', 'id' => $model->id]);
           },
       ],            
    ]]);

?>

</div>
