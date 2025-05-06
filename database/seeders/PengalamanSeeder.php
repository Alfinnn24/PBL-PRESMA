<?php
namespace Database\Seeders;
// database/seeders/PengalamanSeeder.php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PengalamanModel;
use Carbon\Carbon;

class PengalamanSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengalaman')->insert([
            [
                'pengalaman'     => 'Magang di PT Telkom Indonesia',
                'kategori'       => 'Web Development',
                'mahasiswa_nim'  => '2201001',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ]);
        
    }
}

?>