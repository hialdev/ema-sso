<?php

class BlogController extends BaseAppController
{

    public function indexAction()
    {
        $blog = Blog::find();

        $this->view->blogs = $blog;
        $this->view->pick('blog/index');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $blog = Blog::findFirst("slug = '$slug'");
            $blogs = Blog::find([
                'conditions' => 'slug != :slug:',
                'bind'       => [
                    'slug' => $slug,
                ],
                'order'      => 'created DESC',
                'limit'      => 3,
            ]);

            $this->view->blogs = $blogs;
            $this->view->blog = $blog;
            $this->view->pick('blog/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}