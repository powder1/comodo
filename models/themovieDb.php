<?php

namespace app\models;

use Yii;
use yii\base\Model;

class themovieDb extends \yii\db\ActiveRecord
{
    public function createTable()
    {
        Yii::$app->db->createCommand(
            "CREATE TABLE IF NOT EXISTS tbl_themovie_db (
            id int,
            rate int,
            title varchar(255),
            release_date date,
            original_title varchar(255),
            overview text,
            poster_path varchar(255),
            sort varchar(255)
            );"
        )->execute();
    }
    
    public function insertToMovies($data, $sort)
    {
        foreach ($data['results'] as $page)
        {
            $row = (new \yii\db\Query())
            ->select(['id'])
            ->where(['id' => $page['id']])        
            ->from('tbl_themovie_db')->one();
            
            if (!$row)
            {
                $image = "https://image.tmdb.org/t/p/w300".$page['poster_path'];
                $image_data = file_get_contents($image);
                if ($image_data != NULL) 
                {
                    Yii::$app->db->createCommand()
                    ->insert('tbl_themovie_db', [
                    'id' => $page['id'],
                    'title' => $page['title'],
                    'release_date' => $page['release_date'],
                    'original_title' => $page['original_title'],
                    'overview' => $page['overview'],  
                    'poster_path' => $page['poster_path'],    
                    'sort' => $sort
                    ])->execute();
                
                    $downloaded_file = Yii::$app->basePath.'\www\uploads'.$page['poster_path'];
                    file_put_contents($downloaded_file, $image_data);
                }
            }
        }
    }
}
