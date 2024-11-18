<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Actor;
use App\Entity\Movie;
use App\Entity\Category;
use Xylis\FakerCinema\Provider\Person as FakerPerson;
use Xylis\FakerCinema\Provider\Movie as FakerMovie;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $faker->addProvider(new FakerPerson($faker));
        $actors = $faker->actors(null, 190, false);
        $createdActors = [];

        foreach ($actors as $item) {
            $fullname = $item;
            $fullnameExploded = explode(' ', $fullname);
            $firstname = $fullnameExploded[0];
            $lastname = $fullnameExploded[1];

            $actor = new Actor();
            $actor->setLastname($lastname);
            $actor->setFirstname($firstname);
            $dob = $faker->dateTimeThisCentury();
            $actor->setDob($dob);
            $actor->setNationality($faker->country());
            $actor->setGender($faker->randomElement(['male', 'female']));
            $actor->setMedia($faker->imageUrl());
            $actor->setAwards($faker->optional()->numberBetween(0, 10));
            $actor->setBio($faker->realText(200));

            $deathDate = $faker->optional(0.3)->dateTimeBetween($dob, 'now');
            $actor->setDeathDate($deathDate);

            $actor->setCreatedAt(new DateTimeImmutable());

            $createdActors[] = $actor;
            $manager->persist($actor);
        }

        $faker->addProvider(new FakerMovie($faker));
        $movies = $faker->movies(100);

        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setTitle($faker->movieGenre());
            $categories[] = $category;
            $manager->persist($category);
        }

        foreach ($movies as $item) {
            $movie = new Movie();
            $movie->setTitle($item);
            $movie->setReleaseDate($faker->dateTimeBetween('-30 years', 'now'));
            $movie->setDirector($faker->director());
            $movie->setDescription($faker->realText(200));
            $movie->setMedia($faker->imageUrl());
            $movie->setEntries($faker->numberBetween(1000, 100000));
            $movie->setRating($faker->randomFloat(1, 1, 10));

            // conversion
            $runtimeString = $faker->runtime();
            $runtimeParts = explode(':', $runtimeString);
            $durationInMinutes = ($runtimeParts[0] * 60) + $runtimeParts[1];
            $movie->setDuration($durationInMinutes);
            $movie->setCreatedAt(new DateTimeImmutable());

            // Associer 4 acteurs aléatoires au film
            shuffle($createdActors);
            $createdActorsSliced = array_slice($createdActors, 0, 4);
            foreach ($createdActorsSliced as $actor) {
                $movie->addActor($actor);
            }

            // Associer une catégorie aléatoire
            shuffle($categories);
            $movie->addCategory($categories[0]);

            $manager->persist($movie);
        }

        $manager->flush();
    }
}
