<?php
use Intervention\Image\ImageManagerStatic as Image;

class imageController extends Phalcon\Mvc\Controller
{
    protected $image = BASE_PATH .'/webassets/images/placeholder.png';
    protected $imageId;
    protected $width = null;
    protected $height = null;
    protected $cached = true;
    protected $resizeImage = true;

    protected $bgColor = '#ddd';

    protected function redirect($uri = 'notfound')
    {
        $this->response->redirect($uri);
        return $this->response->sendHeaders();
    }

    public function viewAction($id, $width = null, $height = null)
    {
        $this->width = $width;
        $this->height = $height;

        if ($file = File::findByID($id))
        {
            if ($file->isImageFile())
            {
                if (file_exists($this->config->filePath. $file->filePath))
                    $this->image = $this->config->filePath. $file->filePath;
            }
        }
        $this->draw();
    }

    public function broken ()
    {
        $this->width = 200;
        $this->draw();
    }

    protected function draw ()
    {
        $img = null;
        if ($this->image)
        {
            if ($this->width == 0) $this->width = null;
            if ($this->height == 0) $this->height = null;

            if ($this->resizeImage && ($this->width!=null || $this->height!=null))
            {
                $image = new Intervention\Image\ImageManager;
                $img = Image::cache(function($image) {
                    $image->make($this->image);

                    $fitToSize = $this->width!=null && $this->height!=null;

                    if ($fitToSize)
                    {
                        return $image->orientate()->fit($this->width, $this->height, function ($constraint) {
                            //$constraint->upsize();
                        });
                    }
                    else
                    {
                        return $image->orientate()->resize($this->width, $this->height, function ($constraint) {
                            $constraint->aspectRatio();
                            //$constraint->upsize();
                        });
                    }

                 }, 10, true);
            }
            else
            {
                $img = Image::make($this->image);
                $img->orientate();
            }
        }
        else
        {
            if ($this->width==null) $this->width = 300;
            if ($this->height==null) $this->height = 300;

            $img = Image::canvas($this->width, $this->height, $this->bgColor);
        }

        header('Content-Type: image/png');

        if ($this->cached)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=86400, public');
            header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
        }

        echo $img->stream();
        die;
    }
}