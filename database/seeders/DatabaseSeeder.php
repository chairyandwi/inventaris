<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,
            KategoriSeeder::class,
            BarangSeeder::class,
            RuangSeeder::class,
            InventarisServerSeeder::class,
            InventarisLabKomputerSeeder::class,
            InventarisC202Seeder::class,
            InventarisLKBHSeeder::class,
            InventarisRKMHSeeder::class,
            InventarisLabFisikaSeeder::class,
            InventarisLabMesinSeeder::class,
            InventarisPerpustakaanSeeder::class,
            InventarisRuangDekanSeeder::class,
            InventarisRuangDekanatSeeder::class,
            InventarisRuangDosenSeeder::class,
            InventarisRuangKaprodiSeeder::class,
            InventarisRuangSDMSeeder::class,
            InventarisRuangUmumSeeder::class,
            InventarisLabPSKESeeder::class,
            InventarisB101Seeder::class,
            InventarisB102Seeder::class,
            InventarisB103Seeder::class,
            InventarisB106Seeder::class,
            InventarisRuangAkademikSeeder::class,
            InventarisRuangKeuanganSeeder::class,
            InventarisLPPMSeeder::class,
            InventarisB202Seeder::class,
            InventarisB203Seeder::class,
            InventarisB204Seeder::class,
            InventarisB205Seeder::class,
            InventarisB206Seeder::class,
            InventarisB207Seeder::class,
            InventarisB301Seeder::class,
            InventarisB302Seeder::class,
            InventarisB303Seeder::class,
            InventarisB304Seeder::class,
            InventarisB305Seeder::class,
            InventarisB306Seeder::class,
            InventarisB307Seeder::class,
            InventarisB308Seeder::class,
            InventarisAdminDosenMHSeeder::class,
            InventarisRuangKuliahMHSeeder::class,
        ]);
    }
}
