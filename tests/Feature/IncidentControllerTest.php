<?php

namespace Tests\Feature;

use App\Models\Incident;
use App\Models\User;
use App\Models\WantedPerson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class IncidentControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_listar_incidentes_de_una_persona()
    {
        // Autenticar un usuario
        $this->actingAs(User::factory()->create());

        $wanted_person = WantedPerson::factory()->create();
        // Crear un incidente
        $incidentes = Incident::factory()->count(10)->make([
            'wanted_person_id' => $wanted_person->id
        ]);

        $incidentes->each(function ($incident) {
            $incident->save();
        });

        // Hacer una petición GET a la ruta /api/incidents
        $response = $this->get('/api/incidents/list-by-wanted-person/'.$wanted_person->id);

        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    public function test_guardar_incidente()
    {
        // Autenticar un usuario
        $this->actingAs(User::factory()->create());

        // Crear un incidente
        $incident = Incident::factory()->make();

        $photos = [
            getImageFromUrl('https://picsum.photos/200'),
            getImageFromUrl('https://picsum.photos/200'),
            getImageFromUrl('https://picsum.photos/200'),
        ];


        $data = $incident->toArray();
        $data['dni'] = '72655590';
        $data['photos'] = $photos;
        // Hacer una petición POST a la ruta /api/incidents
        $response = $this->post('/api/incidents/store', $data);
        $response->assertStatus(Response::HTTP_CREATED);
    }
}
