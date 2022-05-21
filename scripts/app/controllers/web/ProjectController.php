<?php

class ProjectController extends BaseAppController
{
    protected $metaPage = [
        'title' => "Projects - Elang Merah Api",
        'desc'  => "Halaman project - PT Elang Merah Api",
    ];

    public function indexAction()
    {
        $ps = ProjectStatus::find();
        //Get Clients Account
        $pc = Account::findByRoleSlug("client");

        $psa = [];
        foreach ($ps as $psi) {
            $psa[] = $psi->id;
        };
        $pca = [];
        foreach ($pc as $pci) {
            $pca[] = $pci->id;
        };

        //get Query
        $qq = $this->request->getQuery('q', 'string');
        $qp = $this->request->getQuery('priority', 'int');
        $qs = $this->request->getQuery('status', 'int');
        $qc = $this->request->getQuery('client', 'int');

        if($qq === null) $qq === "";
        if ($qc === 0 || $qc === null) {
            $c = $pca;
        }else{
            $c = [$qc];
        }
        if ($qs === 0 || $qs === null) {
            $s = $psa;
        }else{
            $s = [$qs];
        }

        //Data Projects
        $project = Project::find([
            'conditions' => 'account_id in ({c:array}) AND '.
                            'status in ({s:array}) AND '.
                            'name LIKE :q:',
            'bind' =>
            [
                'q' => '%'.$qq.'%',
                'c' => $c,
                's' => $s,
            ],
            'order' => 'created DESC',
        ]);

        $query = ['q'=>$qq,'c'=>(string) $qc,'s'=>(string) $qs];
        $opt = ['c'=>$pc,'s'=>$ps];
        //var_dump($opt['p'][0]->id);dd();
        $c = count($ticket);

        $this->view->opt = $opt;
        $this->view->query = $query;

        $this->view->projects = $project;
        $this->view->pick('project/index');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $project = Project::findFirst("slug = '$slug'");
            $ps = ProjectStatus::find();
            $ts = TicketStatus::find([
                'conditions' => 'name != :s:',
                'bind' => ['s' => 'closed']
            ]);
            $tp = TicketPriority::find();

            $tsa = [];
            foreach ($ts as $tsi) {
                $tsa[] = $tsi->id;
            };
            $tpa = [];
            foreach ($tp as $tpi) {
                $tpa[] = $tpi->id;
            };

            $qq = $this->request->getQuery('q', 'string');
            $qp = $this->request->getQuery('priority', 'int');
            $qs = $this->request->getQuery('status', 'int');

            if($qq === null) $qq === "";
            if ($qp === 0 || $qp === null) {
                $p = $tpa;
            }else{
                $p = [$qp];
            }
            if ($qs === 0 || $qs === null) {
                $s = $tsa;
            }else{
                $s = [$qs];
            }

            $ticket = Ticket::find([
                'conditions' => 'project_id = :id: AND '.
                                'status in ({s:array}) AND '.
                                'priority in ({p:array}) AND '.
                                'no LIKE :q:',
                'bind' =>
                [
                    'id' => $project->id,
                    'q' => '%'.$qq.'%',
                    'p' => $p,
                    's' => $s,
                ]
            ]);

            $query = ['q'=>$qq,'p'=>(string) $qp,'s'=>(string) $qs];
            $opt = ['p'=>$tp,'s'=>$ts];
            //var_dump($opt['p'][0]->id);dd();
            $c = count($ticket);

            $this->view->opt = $opt;
            $this->view->query = $query;
            $this->view->count = $c;
            $this->view->status = $ps;
            $this->view->tickets = $ticket;
            $this->view->project = $project;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('project/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function addAction()
    {
        $ps = ProjectStatus::find();
        $clients = Account::findByRoleSlug("client");
        
        $this->view->ps = $ps;
        $this->view->clients = $clients;
        $this->view->pick('project/add');
    }

    public function createAction()
    {
        try {
            $project = new Project();
            $project->account_id = $this->request->getPost("client_id");
            $project->name = $this->request->getPost("name");
            $project->status = $this->request->getPost("status");
            $project->excerpt = $this->request->getPost("excerpt");
            $project->slug = Utils::slugify($this->request->getPost("name").' '.$this->request->getPost("client_id"));
            $project->image = "ngasal";
            $project->save();
            $up = $this->uploadFile($project->id);
            
            if ($up) {
                $this->flashSession->success('Hooray.. data berhasil disimpan.');
                return $this->response->redirect("/project/$project->slug");
            }else{
                $this->flashSession->error('Ooops.. Maaf data gagal disimpan.');
                return $this->response->redirect("/project/$project->slug");
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
            $project = Project::findFirst("slug = '$slug'");  
            $ps = ProjectStatus::find();
            $clients = Account::findByRoleSlug("client");
            
            $this->view->ps = $ps;
            $this->view->clients = $clients;
            $this->view->project = $project;
            $this->view->pick('project/edit');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function updateAction()
    {
        if ($slug = $this->dispatcher->getParam('slug')){
            try {
                $project = Project::findFirst("slug = '$slug'");
                $project->account_id = $this->request->getPost("client_id");
                $project->name = $this->request->getPost("name");
                $project->status = $this->request->getPost("status");
                $project->excerpt = $this->request->getPost("excerpt");
                $project->slug = Utils::slugify($this->request->getPost("name").' '.$this->request->getPost("client_id"));
                
                $this->deleteFile($project->image);
                
                if ($this->request->hasFiles()) {
                    $up = $this->uploadFile($project->id);
                }else{
                    if(!$project->save())
                        $this->flashSession->error("Ooops.. Gagal di update");
                    
                    $this->flashSession->success("berhasil diupdate menggunakan gambar lama");
                    return $this->response->redirect("/project/$project->slug");
                }
                
                if ($up) {
                    if(!$project->save())
                        $this->flashSession->error("Ooops.. Gagal di update");

                    $this->flashSession->success('Hooray.. data berhasil diupdate.');
                    return $this->response->redirect("/project/$project->slug");
                }else{
                    $this->flashSession->error('Ooops.. Maaf data gagal diupdate.');
                    return $this->response->redirect("/project/$project->slug");
                }
    
            } catch (\Exception $e) {
                echo $e->getMessage() . '<br>';
                echo '<pre>' . $e->getTraceAsString() . '</pre>';
            }
        }
    }

    public function deleteAction($slug)
    {
        $project = Project::findFirst("slug = '$slug'");
        $this->deleteFile($project->image);
        if ($project->delete()) {
            $tasks = $project->getRelated('Tasks');
            if ($tasks->delete()) {
                $this->flashSession->success("Berhasil menghapus tasks.");
            } else {
                $this->flashSession->error("Gagal menghapus tasks.");
            }

            $tickets = $project->getRelated('Tickets');
            foreach ($tickets as $ticket) {
                $files = $ticket->getFiles();
                if (count($files) !== 0) {
                    foreach ($files as $file) {
                        $filePath = $file->path;
                        if ($file->delete())
                        {
                            $del = $this->deleteFile($filePath);
                            $this->flashSession->success("Berhasil menghapus file $file->name.");
                        }else{
                            $this->flashSession->error("Gagal menghapus file $file->name.");
                        }
                    }
                    if ($del) {
                        if ($ticket->delete()) {
                            $this->flashSession->success("Berhasil menghapus $ticket->subject.");
                        } else {
                            $this->flashSession->error("Gagal menghapus $ticket->subject.");
                        }
                    }
                }else{
                    if ($ticket->delete()) {
                        $this->flashSession->success("Berhasil menghapus $ticket->subject.");
                    } else {
                        $this->flashSession->error("Gagal menghapus $ticket->subject.");
                    }
                }
            }
            

            $notes = $project->getRelated('Notes');
            foreach ($notes as $note) {
                $files = $note->getFiles();
                if (count($files) !== 0) {
                    foreach ($files as $file) {
                        $filePath = $file->path;
                        if ($file->delete())
                        {
                            $del = $this->deleteFile($filePath);
                            $this->flashSession->success("Berhasil menghapus file $file->name.");
                        }else{
                            $this->flashSession->error("Gagal menghapus file $file->name.");
                        }
                    }
                    if ($del) {
                        if ($note->delete()) {
                            $this->flashSession->success("Berhasil menghapus $note->title.");
                        } else {
                            $this->flashSession->error("Gagal menghapus $note->title.");
                        }
                    }
                }else{
                    if ($note->delete()) {
                        $this->flashSession->success("Berhasil menghapus $note->title.");
                    } else {
                        $this->flashSession->error("Gagal menghapus $note->title.");
                    }
                }
            }

        }else{

        }
        
        return $this->response->redirect('/project');
        
    }

    public function uploadFile ($id)
    {
        $this->view->disable();
        $upFiles = $this->request->getUploadedFiles();

        if (empty($project = Project::findFirst("id = $id")))
        {
            $this->flashSession->error("Project tidak ditemukan.");
            return $this->response->redirect('/project');
        }

        foreach ($upFiles as $upFile)
        {
            $fileKey = $upFile->getKey();

            if ($fileKey === 'file')
            {
                $fileInfo = pathinfo($upFile->getName());
                $fileType = $upFile->getRealType();
                $fileSize = $upFile->getSize();
                $filePath = "project/".sprintf("%s/%s/%s_%s_%ss", $id, date("Y/m/d"), $id, $id, Utils::slugify($upFile->getName()));

                if (!$this->saveUploadedFile($upFile, $filePath))
                {
                    $this->flashSession->error("File gagal disimpan.");
                    return $this->response->redirect("/project/$project->slug");
                }

                $_filePath = $this->config->filePath . $filePath;

                $project->image = $filePath;
            }
        }
        
        if ($project->save()) {
            $this->flashSession->success("File berhasil disimpan.");
            return $this->response->redirect("/project/$project->slug");
        }

        $this->deleteFile ($filePath);

        $this->flashSession->error("Gagal mengupload dan menambahkan file.");
        return $this->response->redirect("/project/$project->slug");
    }
}