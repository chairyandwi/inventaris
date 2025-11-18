<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipePeminjam = fake()->randomElement(['umum', 'mahasiswa', 'pegawai']);
        $prodiList = [
            'Teknik Industri',
            'Administrasi Publik',
            'Manajemen',
            'Psikologi',
            'Hukum',
            'Teknologi Informasi',
            'Teknik Lingkungan',
            'Teknik Perminyakan',
            'Teknik Mesin',
        ];

        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'username' => fake()->unique()->userName(),
            'nohp' => fake()->e164PhoneNumber(),
            'role' => 'peminjam',
            'tipe_peminjam' => $tipePeminjam,
            'prodi' => $tipePeminjam === 'mahasiswa' ? fake()->randomElement($prodiList) : null,
            'angkatan' => $tipePeminjam === 'mahasiswa' ? (string) fake()->numberBetween(2018, 2025) : null,
            'nim' => $tipePeminjam === 'mahasiswa' ? fake()->unique()->numerify('23#######') : null,
            'divisi' => $tipePeminjam === 'pegawai' ? fake()->randomElement(['Akademik', 'Keuangan', 'Fasilitas']) : null,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
