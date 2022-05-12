<?php
use Intervention\Image\ImageManagerStatic as Image;

class PicController extends BaseController
{
    protected $image;
    protected $imageId;
    protected $width = null;
    protected $height = null;
    protected $resizeImage = false;

    protected $bgColor = '#ddd';

    public function initialize()
    {
        $parameters = $this->dispatcher->getParams();
        $this->imageId  = $parameters[0]??null;
        $this->width    = $parameters[1]??null;
        $this->height   = $parameters[2]??null;
    }

    public function AccAction()
    {
        $this->image = BASE_PATH .'/webassets/images/user.png';

        if ($data = Account::findByUID($this->imageId))
        {
            if ($data->avatar && file_exists($this->config->filePath. $data->avatar))
                $this->image = $this->config->filePath. $data->avatar;
        }

        $this->draw();
    }

    public function LogoAction()
    {
        $this->image = BASE_PATH .'/webassets/images/logo.png';
        $this->draw();
    }

    protected function draw ()
    {
        $img = null;
        if ($this->image)
        {
            if ($this->width!=null || $this->height!=null)
            {
                $image = new Intervention\Image\ImageManager;
                $img = Image::cache(function($image) {
                    $image->make($this->image);

                    //if ($this->width==null) $this->width = $image->width();
                    //if ($this->height==null) $this->height = $image->height();
                    if ($this->resizeImage)
                        return $image->resize($this->width, $this->height, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    else
                        return $image->fit($this->width, $this->height, function ($constraint) {
                            $constraint->upsize();
                        });
                 }, 10, true);
            }
            else
            {
                $img = Image::make($this->image);
            /* $img = Image::make($this->image)
            ->fit($this->width, $this->height, function ($constraint) {
                $constraint->upsize();
            }); */
            }
        }
        else
        {
            if ($this->width==null) $this->width = 300;
            if ($this->height==null) $this->height = 300;

            $img = Image::canvas($this->width, $this->height, $this->bgColor);
            /* $this->image->rectangle(1, 1, $this->width, $this->height, function ($draw) {
                $draw->background('rgba(255, 255, 255, 0.5)');
                $draw->border(2, '#000');
            }); */
        }

        header('Content-Type: image/png');
        echo $img->stream('png');
    }
}