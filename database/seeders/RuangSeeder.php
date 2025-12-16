<?php

namespace Database\Seeders;

use App\Models\Ruang;
use Illuminate\Database\Seeder;

class RuangSeeder extends Seeder
{
    /**
     * Seed ruang with predefined codes.
     */
    public function run(): void
    {
        $Alt1 = [
            // GEDUNG A - LANTAI 1
            ['nama' => 'Auditorium', 'kode' => 'RAD'],
            ['nama' => 'PMB', 'kode' => 'PMB'],
            ['nama' => 'A103', 'kode' => 'A103'],
            ['nama' => 'Galeri Investasi', 'kode' => 'GLRI'],
            ['nama' => 'BPK', 'kode' => 'BPK'],
            ['nama' => 'Yayasan (KUI)', 'kode' => 'YYSN03'],
            ['nama' => 'Lab Psikologi', 'kode' => 'LAB05'],
        ];

        $Alt2 = [
            // GEDUNG A - LANTAI 2
            ['nama' => 'Fakultas Teknik', 'kode' => 'RFT'],
            ['nama' => 'Sekretariat', 'kode' => 'RKST'],  
            ['nama' => 'Rektor', 'kode' => 'RREK'],
            ['nama' => 'Wakil Rektor', 'kode' => 'RWRK'],
            ['nama' => 'Fakultas Psikologi', 'kode' => 'RFPSI'],
            ['nama' => 'KPM', 'kode' => 'KPM'],
        ];

        $Alt3 = [
            // GEDUNG A - LANTAI 3
            ['nama' => 'A301', 'kode' => 'A301'],
            ['nama' => 'A302', 'kode' => 'A302'],
            ['nama' => 'A303', 'kode' => 'A303'],
            ['nama' => 'A304', 'kode' => 'A304'],
        ];

        $Blt1 = [
            ['nama' => 'Lab PSKE', 'kode' => 'PSKE'],
            ['nama' => 'Akademik', 'kode' => 'RAKD'],
            ['nama' => 'Keuangan', 'kode' => 'RKEU'],
            ['nama' => 'LPPM', 'kode' => 'LPPM'],
            ['nama' => 'B101', 'kode' => 'B101'],
            ['nama' => 'B102', 'kode' => 'B102'],
            ['nama' => 'B103', 'kode' => 'B103'],
            ['nama' => 'B106', 'kode' => 'B106'],
        ];

        $Blt2 = [
            ['nama' => 'Kelas MH', 'kode' => 'KMH1'],
            ['nama' => 'Dosen-Admin MH', 'kode' => 'RMH'],
            ['nama' => 'B202', 'kode' => 'B202'],
            ['nama' => 'B203', 'kode' => 'B203'],
            ['nama' => 'B204', 'kode' => 'B204'],
            ['nama' => 'B205', 'kode' => 'B205'],
            ['nama' => 'B206', 'kode' => 'B206'],
            ['nama' => 'B207', 'kode' => 'B207'],

        ];

        $Blt3 = [
            ['nama' => 'B301', 'kode' => 'B301'],
            ['nama' => 'B302', 'kode' => 'B302'],
            ['nama' => 'B303', 'kode' => 'B303'],
            ['nama' => 'B304', 'kode' => 'B304'],
            ['nama' => 'B305', 'kode' => 'B305'],
            ['nama' => 'B306', 'kode' => 'B306'],
            ['nama' => 'B307', 'kode' => 'B307'],
            ['nama' => 'B308', 'kode' => 'B308'],
        ];

        $Clt1 = [
            // GEDUNG C - LANTAI 1
            ['nama' => 'Lab Fisika', 'kode' => 'LAB02'],
            ['nama' => 'LKBH', 'kode' => 'LKBH'],
            ['nama' => 'Kemahasiswaan', 'kode' => 'RKMH'],
        ];

        $Clt2 = [
            // GEDUNG C - LANTAI 2
            ['nama' => 'Lab Komputer', 'kode' => 'LAB01'],
            ['nama' => 'C202', 'kode' => 'C202'],
            ['nama' => 'Server', 'kode' => 'RSV1'],
        ];

        $Dlt1 = [
            // GEDUNG D - LANTAI 1
            ['nama' => 'Perpustakaan', 'kode' => 'PERP'],
            ['nama' => 'Lab Mesin', 'kode' => 'LAB06'],
        ];

        $Dlt2 = [
            // GEDUNG D - LANTAI 2
            ['nama' => 'SDM', 'kode' => 'RSDM'],
            ['nama' => 'Umum', 'kode' => 'RUMM'],
            ['nama' => 'Kaprodi', 'kode' => 'RKPD'],
            ['nama' => 'Dosen', 'kode' => 'RDSN'],
            ['nama' => 'Dekan', 'kode' => 'RDKN'],
            ['nama' => 'Dekanat', 'kode' => 'RDKT'],
        ];

        foreach ($Alt1 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'A',
                    'nama_lantai' => '1',
                    'keterangan' => null,
                ]
            );
        }

        foreach ($Alt2 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'A',
                    'nama_lantai' => '2',
                    'keterangan' => null,
                ]
            );
        }

        foreach ($Alt3 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'A',
                    'nama_lantai' => '3',
                    'keterangan' => null,
                ]
            );
        }

        foreach ($Blt1 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'B',
                    'nama_lantai' => '1',
                    'keterangan' => null,
                ]
            );
        }

        foreach ($Blt2 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'B',
                    'nama_lantai' => '2',
                    'keterangan' => null,
                ]
            );
        }

        foreach ($Blt3 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'B',
                    'nama_lantai' => '3',
                    'keterangan' => null,
                ]
            );
        }

        foreach ($Clt1 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'C',
                    'nama_lantai' => '1',
                    'keterangan' => null,
                ]
            );
        }
        foreach ($Clt2 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'C',
                    'nama_lantai' => '2',
                    'keterangan' => null,
                ]
            );
        }

        foreach ($Dlt1 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'D',
                    'nama_lantai' => '1',
                    'keterangan' => null,
                ]
            );
        }
        foreach ($Dlt2 as $ruang) {
            Ruang::updateOrCreate(
                ['kode_ruang' => $ruang['kode']],
                [
                    'nama_ruang' => $ruang['nama'],
                    'nama_gedung' => 'D',
                    'nama_lantai' => '2',
                    'keterangan' => null,
                ]
            );
        }
    }
}
