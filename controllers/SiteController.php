<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\themoviedbApi;
use app\models\themovieDb;

class SiteController extends Controller
{  
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAuthentication()
    {
        $model = new themoviedbApi();
        
        if ($model->load(Yii::$app->request->post()))
        {
            if ($apikey = $model->startApi())
            {
                $session = Yii::$app->session;

                $session->set('apikey', $apikey);
            }
        }
        
        return $this->render('authentication', ['model' => $model]);
    }
    
    public function actionMain()
    {
        $session = Yii::$app->session;
        
        $db = new themovieDb();
            
        $db->createTable();
            
        $model = new themoviedbApi();

        $apikey = $session->get('apikey');
            
        $model->load([]);
            
        if ($model->startApi($apikey))
        {
            $resultPopular = $model->getPopularMovies();
            $resultNowPlaying = $model->getNowPlayingMovies();

            $db->insertToMovies($resultPopular, "popular");
            $db->insertToMovies($resultNowPlaying, "nowplaying");
        }
        
        return $this->render('main', ['model' => $db]);
    }
    
    public function actionShow($id)
    {
        return $this->render('show', ['id' => $id]);
    }
    
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionEdit($id)
    {
        (new \yii\db\Query)
        ->createCommand()
        ->update('tbl_themovie_db', [
                    'rate' => $_POST['themovieDb']['rate'],
                    'title' => $_POST['themovieDb']['title'],
                    'release_date' => $_POST['themovieDb']['release_date'],
                    'original_title' => $_POST['themovieDb']['original_title'],
                    'overview' => $_POST['themovieDb']['overview'],
                    'poster_path' => $_POST['themovieDb']['poster_path'],
                    'sort' => $_POST['themovieDb']['sort'],
                ], ['id' => $id])
        ->execute();
        
        $db = themovieDb::find()->where(['id' => $id])->one();
        
        return $this->render('edit', ['id' => $id, 'model' => $db]);
    }
    
    public function actionDelete($id)
    {
        (new \yii\db\Query)
        ->createCommand()
        ->delete('tbl_themovie_db', ['id' => $id])
        ->execute();
        
        return $this->redirect(['site/main']);
    }
    
    public function actionRate($id)
    {
        $row = (new \yii\db\Query())
        ->select(['rate'])
        ->where(['id' => $id])        
        ->from('tbl_themovie_db')->one();
        
        (new \yii\db\Query)
        ->createCommand()
        ->update('tbl_themovie_db', ['rate' => $row['rate'] + 1], ['id' => $id])        
        ->execute();
                
        return $this->redirect(['site/show?id='.$id]);
    }
}
