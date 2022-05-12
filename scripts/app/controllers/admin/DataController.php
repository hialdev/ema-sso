<?php

class DataController extends BaseAppController
{
    public function employeeAjax ()
    {
        $this->view->disable();

        $search = $this->request->getPost("query");

        $aParams = [
            'from'          => "d_employee p",
            'column'        => ['p.*', "d.name as department_name"],
            'join'          => ["LEFT JOIN d_departments d ON p.id_department=d.id"],
            /* 'sortColumn'    => [
                "department"   => 'TRIM(d.name)',
                'name'         => 'TRIM(p.name)'
            ] */
        ];

        /* if (!empty($search)){
            $aParams['condition'][] = "(p.nama LIKE '%$search%' OR p.id LIKE '%$search%')";
        } */

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];
        foreach ($result['data'] as $row)
        {
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function dataUnitAjax ()
    {
        $this->view->disable();

        $sahara = $this->config->database->db->sahara;
        $search = $this->request->getPost("query");

        $aParams = [
            'from'          => $sahara.".t_unit u",
            'column'        => ["u.*", "y.nama_yayasan", "k.kota as nama_kota"],
            'join'          => [
                "LEFT JOIN $sahara.t_yayasan y ON u.id_yayasan=y.id_yayasan",
                "LEFT JOIN $sahara.t_kota k ON k.id_kota=y.id_kota"
            ],
            'searchColumn'  => [
                "id_yayasan" => ['field' => "u.id_yayasan", 'exact' => true],
            ],
            'sortColumn'    => [
                "id_yayasan"   => 'k.kota',
                "nama_unit" => 'TRIM(u.nama_unit)',
            ]
        ];

        if (!empty($search)){
            $aParams['condition'][] = "(u.nama_unit LIKE '%$search%')";
        }

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];
        foreach ($result['data'] as $row)
        {
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function akseslevelAjax()
    {
        $application_id = $this->request->getPost('application_id');
        $list_items = [];

        if ($records = ApplicationAccessLevel::findDetailByApplication($application_id))
        {
            foreach ($records as $record)
            {
                $list_items[] = [
                    'id'    => $record->id,
                    'name'  => $record->name,
                    'data'  => $record
                ];
            }
        }

        return $this->responseSuccess ($list_items);
    }

}
