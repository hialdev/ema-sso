<?php

class BaseApi extends Phalcon\Mvc\Micro
{
    protected $jsonFormat = true;
    protected $controllerName;
    protected $actionName;

    public function initialize()
    {
    }

    public function apiNotFound ($message = '404:NotFound')
    {
        $this->response->setStatusCode(404, "Not Found")->sendHeaders();
        return $this->responseError($message);
    }

    protected function ajaxResponse($status, $message, $data = null, $code = null)
    {
        $resp = [
            'status'    => $status,
            'message'   => $message
        ];

        if (!is_null($code))
            $resp['code'] = $code;

        if (!is_null($data))
            $resp['data'] = $data;

        $this->logInfo(
            sprintf("Request: %s | Response: %s", http_build_query($this->request->get()), json_encode($resp))
        );

        return $this->jsonResponse($resp);
    }

    protected function textResponse($status, $message, $data = null, $code = null)
    {
        $response = $status? 'OK' : 'NOK:'.$message;

        $this->logInfo(
            sprintf("Request: %s | Response: %s", http_build_query($this->request->get()), $response)
        );

        return $response;
    }

    protected function jsonResponse ($resp)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->sendHeaders();

        return json_encode($resp);
    }

    protected function responseSuccess($data = null, $message='success')
    {
        return $this->jsonFormat ?
            $this->ajaxResponse(true, $message, $data):
            $this->textResponse(true, $message, $data);
    }

    protected function responseError($message, $code = null, $data = null)
    {
        return $this->jsonFormat ?
            $this->ajaxResponse(false, $message, $data, $code):
            $this->textResponse(false, $message, $data, $code);
    }

    private function _generateLog ($message)
    {
        $di =  Phalcon\DI::getDefault();
        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setDI($this->getDI());

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        return '['.$controller.':'.$action.'] '.$message;
    }

    protected function logDebug ($message)
    {
        $this->log->debug($this->_generateLog($message));
    }

    protected function logError ($message)
    {
        $this->log->error($this->_generateLog($message));
    }

    protected function logInfo ($message)
    {
        $this->log->info($this->_generateLog($message));
    }

    protected function logWarning ($message)
    {
        $this->log->warning($this->_generateLog($message));
    }
}
