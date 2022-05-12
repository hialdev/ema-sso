<?php
/*
 *
 *
 *
 *
 * @author: me@tes123.id
 */

Class Constant
{
    const LAKI_LAKI     = 'L';
    const PEREMPUAN     = 'P';

    const NEGARA_INDONESIA   = 'ID';

    const PILIHAN_JAWABAN_LAIN = 0;

    static $gender = [
        self::LAKI_LAKI    => 'Laki-Laki',
        self::PEREMPUAN    => 'Perempuan',
    ];

    static $agama = [
        '01'    => 'Islam',
        '02'    => 'Kristen/Protestan',
        '03'    => 'Katolik',
        '04'    => 'Hindu',
        '05'    => 'Budha',
        '06'    => 'Kong Hu Cu',
        '99'    => 'Lainnya'
    ];

    static $pendidikan = [
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
    ];

    static $statusYaTidak = [
        '1'     => 'Ya',
        '0'     => 'Tidak',
    ];

    static $statusAktif = [
        '1'     => 'Aktif',
        '0'     => 'NonAktif',
    ];

    static $bulan = [
        '1'     => 'Januari',
        '2'     => 'Februari',
        '3'     => 'Maret',
        '4'     => 'April',
        '5'     => 'Mei',
        '6'     => 'Juni',
        '7'     => 'Juli',
        '8'     => 'Agustus',
        '9'     => 'September',
        '10'    => 'Oktober',
        '11'    => 'Nopember',
        '12'    => 'Desember',
    ];

    static $statusNikah  = [
        '0'    => 'Belum Menikah',
        '1'    => 'Menikah',
        '2'    => 'Janda/Duda',
    ];

}