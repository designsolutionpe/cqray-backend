<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoriaArticulo>
 */
class CategoriaArticuloFactory extends Factory
{
    protected $table = 'categoria_articulos';

    protected $fillable = [
        'nombre',
        'estado'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // public function definition(): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
