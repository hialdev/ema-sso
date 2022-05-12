<?php

class Employee extends BaseModel
{
    protected $tablename = 'd_employee';
    protected $keys = ["id"];

    static function findDetailById ($id)
    {
        return Employee::query()
            ->columns([
                "Employee.*", "department.*", "designation.*"
            ])
            ->leftJoin("Department", "Employee.id_department = department.id", "department")
            ->leftJoin("Designation", "Employee.id_designation = designation.id", "designation")
            ->where("Employee.id = :id:")
            ->bind(['id' => $id ])
            ->execute()
            ->getfirst();
    }

    public function normalizeToArray ()
    {
        return Employee::normalize($this);
    }

    public static function normalize ($data)
    {
        if ($data instanceof Employee)
            $data = $data->toArray();

        $data['full_name'] = self::setFullName($data['first_name'], $data['middle_name'], $data['last_name']);

/*         $data['agama_txt'] = Utils::getArrayValue(Constant::$agama, $data['agama'], "-");
        $data['status_nikah_txt'] = Utils::getArrayValue(Constant::$statusNikah, $data['status_nikah']);
        $data['tanggal_lahir_txt'] = Utils::formatTanggal($data['tanggal_lahir'], true, true, false);
        $data['gender_txt'] = Utils::genderText($data['jenis_kelamin']);
        $data['masa_kerja'] = $data['tanggal_bergabung'] ? utils::formatDifference(time(), strtotime($data['tanggal_bergabung'])) : '-';
        $data['umur'] = $data['tanggal_lahir'] ? utils::formatDifference(time(), strtotime($data['tanggal_lahir'])) : '-';
        $data['status_data_txt'] = Utils::getArrayValue($constant->tipe_data, $data['tipe_data']);
        $data['tanggal_bergabung_txt'] = Utils::formatTanggal($data['tanggal_bergabung'], true, true, false);
 */
        return $data;
    }

    static function setFullName ($first, $middle = null, $last = null)
    {
        return str_replace("  "," ",$first." ".$middle." ".$last);
    }

    public function getFullName ()
    {
        return self::setFullName($this->first_name, $this->middle_name, $this->lasst_name);
    }

    public function getDepartment ()
    {
        if ($department = Department::findById($this->id_department))
        {
            return $department->name;
        }

        return '';
    }

    public function getDesignation ()
    {
        if ($designation = Designation::findById($this->id_designation))
        {
            return $designation->name;
        }

        return '';
    }
}