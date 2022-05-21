<?php
use Phalcon\Paginator\Adapter\NativeArray as Paginate;

class BlogController extends BaseAppController
{
    protected $metaPage = [
        'title' => "Knowladge - Elang Merah Api",
        'desc'  => "Dapatkan berbagai pengetahuan untuk mengatasi masalah / mengetahui cara melakukan sesuatu. - PT Elang Merah Api",
    ];

    public function indexAction()
    {
        $q = $this->request->getQuery('q', 'string');
        $page = $this->request->getQuery('page', 'int');
        if ($q === null) $q = "";
        $data = Blog::find([
            'conditions' => "title LIKE :q:",
            'bind' => [
                'q' => '%'.$q.'%',
            ],
            'order'      => 'created DESC',
        ]);

        $datas = [];
        foreach ($data as $d) {
            $datas[] = $d->normalizeArray();
        }
        
        $blog = new Paginate(
            [
                "data"       => $datas,
                "limit"      => 10,
                "page"       => $page === '' || $page === 0 ? 2 : $page,
            ]
        );
        $blog = $blog->paginate();

        $this->view->qq = $q;
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

    public function addAction()
    {
        $this->view->pick('blog/add');
    }

    public function createAction()
    {
        try {
            $blog = new Blog();
            $blog->title = $this->request->getPost("title");
            $blog->content = $this->request->getPost("content");
            $blog->excerpt = $this->request->getPost("excerpt");
            $blog->slug = Utils::slugify($this->request->getPost("title"));
            $blog->image = "ngasal";
            $blog->save();
            $up = $this->uploadFile($blog->id);
            
            if ($up) {
                $this->flashSession->success('Hooray.. data berhasil disimpan.');
                return $this->response->redirect("/knowladge/$blog->slug");
            }else{
                $this->flashSession->error('Ooops.. Maaf data gagal disimpan.');
                return $this->response->redirect("/knowladge/$blog->slug");
            }

        } catch (\Exception $e) {
            echo $e->getMessage() . '<br>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    public function editAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $blog = Blog::findFirst("slug = '$slug'");  

            $this->view->blog = $blog;
            $this->view->pick('blog/edit');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function updateAction()
    {
        if ($slug = $this->dispatcher->getParam('slug')){
            try {
                $blog = Blog::findFirst("slug = '$slug'");
                $blog->title = $this->request->getPost("title");
                $blog->content = $this->request->getPost("content");
                $blog->excerpt = $this->request->getPost("excerpt");
                $blog->slug = Utils::slugify($this->request->getPost("title"));
                $blog->image = "ngasal";
                $blog->save();
                $up = $this->uploadFile($blog->id);
                
                if ($up) {
                    $this->flashSession->success('Hooray.. data berhasil disimpan.');
                    return $this->response->redirect("/knowladge/$blog->slug");
                }else{
                    $this->flashSession->error('Ooops.. Maaf data gagal disimpan.');
                    return $this->response->redirect("/knowladge/$blog->slug");
                }
    
            } catch (\Exception $e) {
                echo $e->getMessage() . '<br>';
                echo '<pre>' . $e->getTraceAsString() . '</pre>';
            }
        }
    }

    public function deleteAction($slug)
    {
        $blog = Blog::findFirst("slug = '$slug'");
        $this->deleteFile($blog->image);
        
        if($blog->delete())
            $this->flashSession->success("berhasil menghapus $blog->title");
            
        return $this->response->redirect('/knowladge');
        
    }

    public function uploadFile ($id)
    {
        $this->view->disable();
        $upFiles = $this->request->getUploadedFiles();

        if (empty($blog = Blog::findFirst("id = $id")))
        {
            $this->flashSession->error("Knowladge tidak ditemukan.");
            return $this->response->redirect('/knowladge');
        }

        foreach ($upFiles as $upFile)
        {
            $fileKey = $upFile->getKey();

            if ($fileKey === 'file')
            {
                $fileInfo = pathinfo($upFile->getName());
                $fileType = $upFile->getRealType();
                $fileSize = $upFile->getSize();
                $filePath = "knowladge/".sprintf("%s/%s/%s_%s_%ss", $id, date("Y/m/d"), $id, $id, Utils::slugify($upFile->getName()));

                if (!$this->saveUploadedFile($upFile, $filePath))
                {
                    $this->flashSession->error("File gagal disimpan.");
                    return $this->response->redirect("/knowladge/$blog->slug");
                }

                $_filePath = $this->config->filePath . $filePath;

                $blog->image = $filePath;
            }
        }
        
        if ($blog->save()) {
            $this->flashSession->success("File berhasil disimpan.");
            return $this->response->redirect("/knowladge/$blog->slug");
        }

        $this->deleteFile ($filePath);

        $this->flashSession->error("Gagal mengupload dan menambahkan file.");
        return $this->response->redirect("/knowladge/$blog->slug");
    }

}