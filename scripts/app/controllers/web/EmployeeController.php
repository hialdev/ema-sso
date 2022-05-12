<?php

class EmployeeController extends BaseAppController
{
    protected $pageTitle = "Employee";

    public function indexAction()
    {
        $this->view->list_department = Department::getAsOptionList();
        $this->view->list_designation = Designation::getAsOptionList();
        $this->view->list_employment_type = EmploymentType::getAsOptionList();
    }

    public function datatableAjax ()
    {
        $this->view->disable();

        $query = $this->request->getPost('query');

        $aParams = [
            'from'          => 'd_employee e',
            'column'        => [
                'e.id', 'e.first_name', 'e.middle_name', 'e.last_name', 'e.nickname', 'e.status', 'e.phone', 'e.email',
                'd.name as department', 't.name as title', "et.name as employment_type_txt"
            ],
            'join'          => [
                "LEFT JOIN d_departments d ON e.id_department=d.id",
                "LEFT JOIN d_designations t ON e.id_designation=t.id",
                "LEFT JOIN d_employment_types et ON e.employment_type=et.id"
            ],
            //'filter'        => ["id_company=1"],
            'searchColumn'  => [
                'department'        => ['exact' => true, 'field' => 'e.id_department'],
                'employment_type'   => ['exact' => true, 'field' => 'e.employment_type'],
            ],
            'sortColumn'    => [
                1      => 'e.first_name'
            ]
        ];

        if ($query)
        {
            $aParams['condition'][] = "(e.first_name LIKE '%$query%' OR e.middle_name LIKE '%$query%'  OR e.last_name LIKE '%$query%' OR e.nickname LIKE '%$query%')";
        }

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];
        foreach ($result['data'] as $row)
        {
            $row['full_name'] = Employee::setFullName($row['first_name'], $row['middle_name'], $row['last_name']);
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function getAjax ()
    {
        if ($record = Employee::findDetailById($this->request->getPost("id")))
        {
            $data = $record->employee->normalizeToArray();
            $data['department'] = $record->department->name;
            $data['designation'] = $record->designation->name;

            return $this->responseSuccess($data);
        }

        return $this->responseError("Employee tidak ditemukan");
    }

    protected function setEmployeeData ($employee)
    {
        $employee->first_name = $this->request->getPost('first_name');
        $employee->last_name = $this->request->getPost('last_name');
        $employee->email = $this->request->getPost('email');
        $employee->phone = $this->request->getPost('phone');
        $employee->id_department = $this->request->getPost('id_department');
        $employee->id_designation = $this->request->getPost('id_designation');
        $employee->employment_type = $this->request->getPost('employment_type');
    }

    public function createAjax ()
    {
        $employee = new Employee;

        $employee->id = Employee::generateId();

        $this->setEmployeeData($employee);

        $employee->created_by = $this->account->id;
        $employee->created_at = Utils::now();

        if ($employee->save())
        {
            return $this->responseSuccess($employee->normalizeToArray());
        }

        return $this->responseError("Employee tidak berhasil disimpan");
    }

    public function editAjax ()
    {
        $employee_id = $this->request->getPost('id');
        if ($employee = Employee::findById($employee_id))
        {
            $this->setEmployeeData($employee);

            if ($employee->save())
            {
                $data = $employee->normalizeToArray();
                $data['department'] = $employee->getDepartment();
                $data['designation'] = $employee->getDesignation();

                return $this->responseSuccess($data);
            }

            return $this->responseError("Data Employee tidak berhasil disimpan");
        }

        return $this->responseError("Employee tidak ditemukan");
    }

    public function deleteAjax ()
    {
        $employee_id = $this->request->getPost('id');
        if ($employee = Employee::findById($employee_id))
        {
            if ($employee->delete())
            {
                return $this->responseSuccess();
            }

            return $this->responseError("Employee tidak berhasil dihapus");
        }

        return $this->responseError("Employee tidak ditemukan");
    }

}