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
    'query' => themovieDb::find(),
    'pagination' => [
        'pageSize' => 4,
    ],
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
           'format' => 'raw',
           'value' => function ($model) { 
                return Html::a($model->title, ['show', 'id' => $model->id]);
           },
       ],
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'release_date',
       ], 
       [
           'class' => 'yii\grid\DataColumn',
           'attribute' => 'sort',
       ], 
    ]]);

?>

</div>
