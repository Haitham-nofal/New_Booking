<?php

namespace App\Http\Services;


class MediaService{

public function createMedia($model , $file , $collection="main_image")
{
    return $model->addMedia($file)->toMediaCollection($collection);
}

public function editMedia($model,$file,$collection="main_image")
{
    if($model->getMedia($collection)->isNotEmpty())
        {
            $model->clearMediaCollection($collection);
        }
    return $model->addMedia($file)->toMediaCollection($collection);

}

public function deleteMedia($model,$collection="main_image")
{
     if($model->getMedia($collection)->isNotEmpty())
        {
            $model->clearMediaCollection($collection);
        }
        return true;
}
}
