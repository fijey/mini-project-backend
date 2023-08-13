<?php
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User; // Make sure to import the User model
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;

class ProductRepositoryTest extends DuskTestCase
{
    use DatabaseMigrations, WithFaker;

    public function testCreateProduct()
    {
        $this->browse(function (Browser $browser) {
            // Create a user and log in
            $user = User::factory()->create([
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
            $browser->loginAs($user);

            $productName = $this->faker->word;
            $productPrice = $this->faker->randomNumber(2);
            $productQuantity = $this->faker->numberBetween(1, 100);

            $browser->visit('/product/create')
                ->type('name', $productName)
                ->type('price', $productPrice)
                ->type('quantity', $productQuantity)
                ->press('Submit')
                ->assertSee('Product created successfully');
        });

        // Assert that the product with the generated attributes exists in the database
        $this->assertDatabaseHas('products', [
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => $productQuantity,
        ]);
    }
}
