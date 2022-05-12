<?php

class DataHelper extends Phalcon\Di\Injectable
{
    static $gender= [
        'L'     => 'Laki-laki',
        'P'     => 'Perempuan'
    ];

    static $agama = array (
        '01'    => 'Islam',
        '02'    => 'Kristen/Protestan',
        '03'    => 'Katolik',
        '04'    => 'Hindu',
        '05'    => 'Budha',
        '06'    => 'Kong Hu Cu',
        '99'    => 'Lainnya'
    );

    static $pekerjaan = array (
        '01'    => 'Tidak Bekerja',
        '02'    => 'Nelayan',
        '03'    => 'Petani',
        '04'    => 'Peternak',
        '05'    => 'PNS/TNI/Polri',
        '06'    => 'Karyawan Swasta',
        '07'    => 'Pedagang Kecil',
        '08'    => 'Pedagang Besar',
        '09'    => 'Wiraswasta',
        '10'    => 'Wirausaha',
        '11'    => 'Buruh',
        '12'    => 'Pensiunan',
        '99'    => 'Lainnya'
    );

    static $pendidikan = array (
        '01'    => 'Tidak Sekolah',
        '02'    => 'Putus SD',
        '03'    => 'SD Sederajat',
        '04'    => 'SMP Sederajat',
        '05'    => 'SMA Sederajat',
        '06'    => 'D1',
        '07'    => 'D2',
        '08'    => 'D3',
        '09'    => 'D4/S1',
        '10'    => 'S2',
        '11'    => 'S3',
        '99'    => 'Lainnya'
    );

    static $statusYaTidak = array (
        '1'     => 'Ya',
        '0'     => 'Tidak',
    );

    static $statusAktif = array (
        '1'     => 'Aktif',
        '0'     => 'NonAktif',
    );

    static $statusNikah = array (
        '0'    => 'Belum Menikah',
        '1'    => 'Menikah',
        '2'    => 'Janda/Duda',
    );
}