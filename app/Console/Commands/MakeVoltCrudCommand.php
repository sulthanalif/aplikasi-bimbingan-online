<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File; // <-- Tambahkan ini
use Illuminate\Support\Str;           // <-- Tambahkan ini

class MakeVoltCrudCommand extends Command
{
    protected $signature = 'make:volt-crud';
    protected $description = 'Create a new CRUD Volt component page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Tanya nama page ke pengguna
        $name = $this->ask('Masukkan nama page yang ingin dibuat (contoh: masters/faculty/index)');

        // Hentikan jika pengguna tidak memasukkan apa-apa
        if (!$name) {
            $this->error('Nama page tidak boleh kosong!');
            return 1; // Keluar dengan status error
        }

        // 2. Tentukan path tujuan file
        $path = $this->getPath($name);

        // 3. Pastikan direktori tujuan sudah ada, jika belum maka buat
        $directory = dirname($path);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        // 4. Ambil konten dari file stub
        $stub = File::get($this->getStub());

        // 5. Simpan konten stub ke file baru di path tujuan
        File::put($path, $stub);

        // 6. Tampilkan pesan sukses
        $this->info("Volt CRUD page [{$name}] berhasil dibuat di: {$path}");

        return 0; // Selesai dengan sukses
    }

    /**
     * Mengambil file stub yang akan digunakan.
     */
    protected function getStub()
    {
        return base_path('stubs/volt.crud.stub');
    }

    /**
     * Menentukan path tujuan di mana file akan dibuat.
     */
    protected function getPath($name)
    {
        $name = str_replace('\\', '/', $name);
        $fileName = Str::of($name)->kebab()->lower();

        return resource_path("views/livewire/{$fileName}.blade.php");
    }
}
