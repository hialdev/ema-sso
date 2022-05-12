<?php

class PegawaiHelper extends  Phalcon\Di\Injectable
{
    static function isGuru ($id_pegawai)
    {
        return Guru::findfirst(["id_pegawai='$id_pegawai'"]);
    }

    static function isWaliKelas ($id_pegawai)
    {
        $tahunAjaran = TahunAjaran::getActive();
        $parameters = ["id_ta='{$tahunAjaran->id_ta}' AND (guru1_id='$id_pegawai' OR guru2_id='$id_pegawai')"];

        return Kelas::findfirst($parameters);
    }

    static function getJadwalGuru ($pegawaiId, $id_ta, $day = null)
    {
        /* SELECT jk.id, jk.id_jampelajaran, j.jam_mulai, j.jam_selesai, j.hari, jk.id_kelas, k.nama_kelas, p.nama, m.nama_mapel FROM t_jadwal_kelas jk
        LEFT JOIN t_jam_pelajaran j ON j.id=jk.id_jampelajaran
        LEFT JOIN t_kelas k ON k.id_kelas=jk.id_kelas
        LEFT JOIN t_guru_bidangstudi g ON jk.id_guru=g.id
        LEFT JOIN t_pegawai p ON g.id_pegawai = p.id_pegawai
        LEFT JOIN t_bidangstudi bs ON g.id_bidangstudi=bs.id
        LEFT JOIN t_pelajaran m ON m.id_mapel=bs.id_mapel
        WHERE g.id_pegawai='INTAM01160018' AND j.id IS NOT NULL AND UPPER(hari)=UPPER(DAYNAME(CURDATE()))
        ORDER BY jam_mulai */

        $dayname = $day ? "UPPER('$day')" : 'UPPER(DAYNAME(CURDATE()))';

        return JadwalKelas::query()
            ->columns([
                "JadwalKelas.*", "jampelajaran.*", "kelas.*", "pelajaran.*", "pegawai.*", "bidangstudi.*"
            ])
            ->leftjoin("JamPelajaran", "JadwalKelas.id_jampelajaran=jampelajaran.id", "jampelajaran")
            ->leftjoin("Kelas", "JadwalKelas.id_kelas=kelas.id_kelas", "kelas")
            ->leftjoin("Guru", "JadwalKelas.id_guru=guru.id", "guru")
            ->leftjoin("Pegawai", "guru.id_pegawai=pegawai.id_pegawai", "pegawai")
            ->leftjoin("BidangStudi", "guru.id_bidangstudi=bidangstudi.id", "bidangstudi")
            ->leftjoin("Pelajaran", "bidangstudi.id_mapel=pelajaran.id_mapel", "pelajaran")
            ->where("guru.id_pegawai = '$pegawaiId'")
            ->andWhere("jampelajaran.id_ta='$id_ta'")
            ->andWhere("jampelajaran.id IS NOT NULL")
            ->andWhere("UPPER(jampelajaran.hari)=$dayname")
            ->orderBy("jampelajaran.hari, jampelajaran.jam_mulai")
            ->execute();

    }
}