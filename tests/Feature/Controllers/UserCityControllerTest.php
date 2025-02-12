<?php

use App\Models\User;
use App\Models\UserCity;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guest cannot access user cities', function () {
    $response = $this->get(route('user_cities.index'));
    $response->assertRedirect(route('login'));
});

test('user can view their cities', function () {
    $this->actingAs($this->user);
    
    $cities = UserCity::factory(3)->create([
        'user_id' => $this->user->id,
        'is_favorite' => false
    ]);
    
    $favoriteCity = UserCity::factory()->create([
        'user_id' => $this->user->id,
        'is_favorite' => true
    ]);

    $response = $this->get(route('user_cities.index'));

    $response->assertStatus(200)
        ->assertViewIs('user_cities.index')
        ->assertViewHas('favoriteCity', $favoriteCity)
        ->assertViewHas('otherCities');

    expect($response->viewData('otherCities'))->toHaveCount(3);
});

test('user can add a new city', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('user_cities.store'), [
        'city' => 'Paris'
    ]);

    $response->assertRedirect(route('user_cities.index'))
        ->assertSessionHas('success', 'City added successfully.');

    $this->assertDatabaseHas('user_cities', [
        'user_id' => $this->user->id,
        'city' => 'Paris',
        'is_favorite' => false,
        'send_forecast' => false
    ]);
});

test('user cannot add a city without a name', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('user_cities.store'), [
        'city' => ''
    ]);

    $response->assertSessionHasErrors(['city']);
});

test('user can toggle favorite city', function () {
    $this->actingAs($this->user);

    $city = UserCity::factory()->create([
        'user_id' => $this->user->id,
        'is_favorite' => false
    ]);

    $response = $this->patch(route('user_cities.toggle_favorite', $city));

    $response->assertRedirect(route('user_cities.index'))
        ->assertSessionHas('success', 'City set as favorite.');

    $this->assertDatabaseHas('user_cities', [
        'id' => $city->id,
        'is_favorite' => true
    ]);
});

test('user can untoggle favorite city', function () {
    $this->actingAs($this->user);

    $city = UserCity::factory()->create([
        'user_id' => $this->user->id,
        'is_favorite' => true
    ]);

    $response = $this->patch(route('user_cities.toggle_favorite', $city));

    $response->assertRedirect(route('user_cities.index'))
        ->assertSessionHas('success', 'City removed from favorites.');

    $this->assertDatabaseHas('user_cities', [
        'id' => $city->id,
        'is_favorite' => false
    ]);
});

test('only one city can be favorite', function () {
    $this->actingAs($this->user);

    $firstCity = UserCity::factory()->create([
        'user_id' => $this->user->id,
        'is_favorite' => true
    ]);

    $secondCity = UserCity::factory()->create([
        'user_id' => $this->user->id,
        'is_favorite' => false
    ]);

    $this->patch(route('user_cities.toggle_favorite', $secondCity));

    $this->assertDatabaseHas('user_cities', [
        'id' => $firstCity->id,
        'is_favorite' => false
    ]);

    $this->assertDatabaseHas('user_cities', [
        'id' => $secondCity->id,
        'is_favorite' => true
    ]);
});

test('user can toggle forecast setting', function () {
    $this->actingAs($this->user);

    $city = UserCity::factory()->create([
        'user_id' => $this->user->id,
        'send_forecast' => false
    ]);

    $response = $this->patch(route('user_cities.toggle_forecast', $city));

    $response->assertRedirect()
        ->assertSessionHas('success', 'Forecast settings updated.');

    $this->assertDatabaseHas('user_cities', [
        'id' => $city->id,
        'send_forecast' => true
    ]);
});

test('user cannot manage cities of other users', function () {
    $this->actingAs($this->user);

    $otherUser = User::factory()->create();
    $otherCity = UserCity::factory()->create([
        'user_id' => $otherUser->id
    ]);

    $initialState = [
        'id' => $otherCity->id,
        'user_id' => $otherUser->id,
        'city' => $otherCity->city,
        'is_favorite' => 0,
        'send_forecast' => 0,
        'created_at' => $otherCity->created_at->format('Y-m-d H:i:s'),
        'updated_at' => $otherCity->updated_at->format('Y-m-d H:i:s')
    ];

    $this->patch(route('user_cities.toggle_favorite', $otherCity));
    $this->patch(route('user_cities.toggle_forecast', $otherCity));
    $this->delete(route('user_cities.destroy', $otherCity));

    $this->assertDatabaseHas('user_cities', $initialState);
});

test('user cities are deleted when user is deleted', function () {
    $cities = UserCity::factory(3)->create([
        'user_id' => $this->user->id
    ]);

    $this->user->delete();

    foreach ($cities as $city) {
        $this->assertDatabaseMissing('user_cities', [
            'id' => $city->id
        ]);
    }
});