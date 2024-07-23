<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        /////الانواع/////
        \App\Models\Category::create([
            'name' => 'Oriental food'
        ]);
        \App\Models\Category::create([
            'name' => 'Western food'
        ]);
        \App\Models\Category::create([
            'name' => 'Sea food'
        ]);
        \App\Models\Category::create([
            'name' => 'Candies'
        ]);
        \App\Models\Category::create([
            'name' => 'Juices'
        ]);
        \App\Models\Category::create([
            'name' => 'Soups'
        ]);
        ///////المكونات/////
        \App\Models\Ingredients::create([
            'name' => 'egg'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'milk'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'salt'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'sugar'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'oil'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'patato'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'tomatoes'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'rice'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'bulgur'
        ]);
        \App\Models\Ingredients::create([
            'name' => 'egg'
        ]);







        // إضافة وصفة رقم 1
        $recipe1 = \App\Models\Recipe::create([
            'category_id' => 1,
            'user_id' => 2, // تغييره إلى معرف المستخدم الفعلي
            'name' => 'Hummus',
            'description' => 'A delicious Middle Eastern dip made from chickpeas and tahini.',
            'tips' => 'Serve with warm pita bread.',
            'main_img_url' => 'hummus.jpg', // اسم الصورة في المجلد الصحيح
            'views' => 0,
            'expected_cost' => 5.99,
            'expected_time' => 15,
            'difficulty_level' => 2,

        ]);

        // إضافة مكونات وصفة رقم 1
        $ingredient1 = \App\Models\Ingredients::create([
            'name' => 'chickpeas'
        ]);
        $ingredient2 = \App\Models\Ingredients::create([
            'name' => 'tahini'
        ]);
        $ingredient3 = \App\Models\Ingredients::create([
            'name' => 'lemon juice'
        ]);

        $recipeIngredient1 = new \App\Models\RecipeIngredients([
            'quanttity' => 2,
            'is_main_ingredient' => true,
            'reduserCompany' => 'company1'
        ]);
        $recipeIngredient1->recipe()->associate($recipe1);
        $recipeIngredient1->ingredients()->associate($ingredient1);
        $recipeIngredient1->save();

        $recipeIngredient2 = new \App\Models\RecipeIngredients([
            'quanttity' => 0.25,
            'is_main_ingredient' => false,
            'reduserCompany' => 'company3'
        ]);
        $recipeIngredient2->recipe()->associate($recipe1);
        $recipeIngredient2->ingredients()->associate($ingredient2);
        $recipeIngredient2->save();

        $recipeIngredient3 = new \App\Models\RecipeIngredients([
            'quanttity' => 1,
            'is_main_ingredient' => false,
            'reduserCompany' => 'company2'
        ]);
        $recipeIngredient3->recipe()->associate($recipe1);
        $recipeIngredient3->ingredients()->associate($ingredient3);
        $recipeIngredient3->save();

        // إضافة خطوات وصفة رقم 1
        $step1 = new \App\Models\Step([
            'content' => 'Drain and rinse the chickpeas.',
            'number' => 1,

        ]);
        $step1->recipe()->associate($recipe1);
        $step1->save();

        $step2 = new \App\Models\Step([
            'content' => 'Blend the chickpeas, tahini, and lemon juice until smooth.',
            'number' => 2,
        ]);
        $step2->recipe()->associate($recipe1);
        $step2->save();
        ///////////////////////////////////////////////////////////////////////////////////////
        ///// إضافة وصفة رقم 2 /////
        $recipe2 = \App\Models\Recipe::create([
            'category_id' => 1,
            'user_id' => 2, // تعديله إلى معرف المستخدم الفعلي
            'name' => 'Falafel',
            'description' => 'Deep-fried balls made from ground chickpeas and spices.',
            'tips' => 'Serve with tahini sauce and pita bread.',
            'main_img_url' => 'falafel.jpg',
            'views' => 0,
            'expected_cost' => 6.99,
            'expected_time' => 30,
            'difficulty_level' => 3,
        ]);

        $groundChickpeas = \App\Models\Ingredients::create([
            'name' => 'ground chickpeas'
        ]);
        $spices = \App\Models\Ingredients::create([
            'name' => 'spices'
        ]);

        $recipeIngredient4 = new \App\Models\RecipeIngredients([
            'quanttity' => 2,
            'is_main_ingredient' => true,
            'reduserCompany' => 'company1'
        ]);
        $recipeIngredient4->recipe()->associate($recipe2);
        $recipeIngredient4->ingredients()->associate($groundChickpeas);
        $recipeIngredient4->save();

        $recipeIngredient5 = new \App\Models\RecipeIngredients([
            'quanttity' => 0.5,
            'is_main_ingredient' => false,
            'reduserCompany' => 'company1'
        ]);
        $recipeIngredient5->recipe()->associate($recipe2);
        $recipeIngredient5->ingredients()->associate($spices);
        $recipeIngredient5->save();

        $step3 = new \App\Models\Step([
            'content' => 'Mix ground chickpeas and spices in a bowl.',
            'number' => 1,
        ]);
        $step3->recipe()->associate($recipe2);
        $step3->save();

        $step4 = new \App\Models\Step([
            'content' => 'Shape the mixture into small balls and deep-fry until golden brown.',
            'number' => 2,
        ]);
        $step4->recipe()->associate($recipe2);
        $step4->save();
        ///////////////////////////////////////////////////////////////////////
        //3
        $recipe1 = \App\Models\Recipe::create([
            'category_id' => 1,
            'user_id' => 1,
            'name' => 'Chicken Curry',
            'description' => 'A flavorful Indian chicken curry with spices and herbs.',
            'tips' => 'Serve with basmati rice and naan bread.',
            'main_img_url' => 'chicken_curry.jpg',
            'views' => 0,
            'expected_cost' => 10.99,
            'expected_time' => 45,
            'difficulty_level' => 3,
        ]);

        $chicken = \App\Models\Ingredients::create([
            'name' => 'chicken'
        ]);
        $spices = \App\Models\Ingredients::create([
            'name' => 'spices'
        ]);

        $recipeIngredient1 = new \App\Models\RecipeIngredients([
            'quanttity' => 500,
            'is_main_ingredient' => true,
            'reduserCompany' => 'company1'
        ]);
        $recipeIngredient1->recipe()->associate($recipe1);
        $recipeIngredient1->ingredients()->associate($chicken);
        $recipeIngredient1->save();

        $recipeIngredient2 = new \App\Models\RecipeIngredients([
            'quanttity' => 0.1,
            'is_main_ingredient' => false,
            'reduserCompany' => 'company2'
        ]);
        $recipeIngredient2->recipe()->associate($recipe1);
        $recipeIngredient2->ingredients()->associate($spices);
        $recipeIngredient2->save();

        $step1 = new \App\Models\Step([
            'content' => 'Marinate the chicken with spices for 30 minutes.',
            'number' => 1,
        ]);
        $step1->recipe()->associate($recipe1);
        $step1->save();

        $step2 = new \App\Models\Step([
            'content' => 'Cook the chicken in a pan until done.',
            'number' => 2,
        ]);
        $step2->recipe()->associate($recipe1);
        $step2->save();
        /////////////////////////////////////////////////////////////////////////////////////

        ///4
        $recipe1 = \App\Models\Recipe::create([
            'category_id' => 1,
            'user_id' => 1,
            'name' => 'Spaghetti Bolognese',
            'description' => 'Classic Italian pasta dish with meat sauce.',
            'tips' => 'Serve with freshly grated Parmesan cheese.',
            'main_img_url' => 'spaghetti.jpg',
            'views' => 0,
            'expected_cost' => 8.50,
            'expected_time' => 40,
            'difficulty_level' => 2,
        ]);

        $groundBeef = \App\Models\Ingredients::create([
            'name' => 'ground beef'
        ]);
        $tomatoSauce = \App\Models\Ingredients::create([
            'name' => 'tomato sauce'
        ]);

        $recipeIngredient1 = new \App\Models\RecipeIngredients([
            'quanttity' => 300,
            'is_main_ingredient' => true,
            'reduserCompany' => 'company1'
        ]);
        $recipeIngredient1->recipe()->associate($recipe1);
        $recipeIngredient1->ingredients()->associate($groundBeef);
        $recipeIngredient1->save();

        $recipeIngredient2 = new \App\Models\RecipeIngredients([
            'quanttity' => 1,
            'is_main_ingredient' => false,
            'reduserCompany' => 'company2'
        ]);
        $recipeIngredient2->recipe()->associate($recipe1);
        $recipeIngredient2->ingredients()->associate($tomatoSauce);
        $recipeIngredient2->save();

        $step1 = new \App\Models\Step([
            'content' => 'Cook the ground beef in a pan until browned.',
            'number' => 1,
        ]);
        $step1->recipe()->associate($recipe1);
        $step1->save();

        $step2 = new \App\Models\Step([
            'content' => 'Add the tomato sauce and simmer for 20 minutes.',
            'number' => 2,
        ]);
        $step2->recipe()->associate($recipe1);
        $step2->save();
        /////////////////////////////////////////////////////////////////////////
        ///5
        $recipe2 = \App\Models\Recipe::create([
            'category_id' => 2,
            'user_id' => 2,
            'name' => 'Chicken Stir-Fry',
            'description' => 'Quick and healthy chicken stir-fry with vegetables.',
            'tips' => 'Serve with steamed rice.',
            'main_img_url' => 'stirfry.jpg',
            'views' => 0,
            'expected_cost' => 7.20,
            'expected_time' => 25,
            'difficulty_level' => 2,
        ]);

        $chickenBreast = \App\Models\Ingredients::create([
            'name' => 'chicken breast'
        ]);
        $mixedVegetables = \App\Models\Ingredients::create([
            'name' => 'mixed vegetables'
        ]);

        $recipeIngredient3 = new \App\Models\RecipeIngredients([
            'quanttity' => 2,
            'is_main_ingredient' => true,
            'reduserCompany' => 'company3'
        ]);
        $recipeIngredient3->recipe()->associate($recipe2);
        $recipeIngredient3->ingredients()->associate($chickenBreast);
        $recipeIngredient3->save();

        $recipeIngredient4 = new \App\Models\RecipeIngredients([
            'quanttity' => 1,
            'is_main_ingredient' => false,
            'reduserCompany' => 'company4'
        ]);
        $recipeIngredient4->recipe()->associate($recipe2);
        $recipeIngredient4->ingredients()->associate($mixedVegetables);
        $recipeIngredient4->save();

        $step3 = new \App\Models\Step([
            'content' => 'Slice the chicken into thin strips.',
            'number' => 1,
        ]);
        $step3->recipe()->associate($recipe2);
        $step3->save();

        $step4 = new \App\Models\Step([
            'content' => 'Stir-fry the chicken and vegetables in a hot pan.',
            'number' => 2,
        ]);
        $step4->recipe()->associate($recipe2);
        $step4->save();
        /////////////////////////////
        ///6
        $recipe7 = \App\Models\Recipe::create([
            'category_id' => 2,
            'user_id' => 2,
            'name' => 'Vegetable Curry',
            'description' => 'Flavorful curry with mixed vegetables and aromatic spices.',
            'tips' => 'Serve with rice or naan bread.',
            'main_img_url' => 'vegetable_curry.jpg',
            'views' => 0,
            'expected_cost' => 8.99,
            'expected_time' => 45,
            'difficulty_level' => 2,
        ]);

        $mixedVegetables2 = \App\Models\Ingredients::create([
            'name' => 'mixed vegetables'
        ]);
        $currySpices = \App\Models\Ingredients::create([
            'name' => 'curry spices'
        ]);

        $recipeIngredient6 = new \App\Models\RecipeIngredients([
            'quanttity' => 500,
            'is_main_ingredient' => true,
            'reduserCompany' => 'company3'
        ]);
        $recipeIngredient6->recipe()->associate($recipe7);
        $recipeIngredient6->ingredients()->associate($mixedVegetables2);
        $recipeIngredient6->save();

        $recipeIngredient7 = new \App\Models\RecipeIngredients([
            'quanttity' => 0.05,
            'is_main_ingredient' => false,
            'reduserCompany' => 'company4'
        ]);
        $recipeIngredient7->recipe()->associate($recipe7);
        $recipeIngredient7->ingredients()->associate($currySpices);
        $recipeIngredient7->save();

        $step5 = new \App\Models\Step([
            'content' => 'Saute the mixed vegetables in a pan.',
            'number' => 1,
        ]);
        $step5->recipe()->associate($recipe7);
        $step5->save();

        $step6 = new \App\Models\Step([
            'content' => 'Add the curry spices and simmer for 15 minutes.',
            'number' => 2,
        ]);
        $step6->recipe()->associate($recipe7);
        $step6->save();
        ///////////////////////////////////////////////




    }
}
