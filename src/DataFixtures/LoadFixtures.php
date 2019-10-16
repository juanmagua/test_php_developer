<?php
namespace App\DataFixtures;

use Nelmio\Alice\Loader\NativeLoader;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new Nelmio\Alice\Loader\NativeLoader();

        $objectSet = $loader->loadFile(__DIR__.'/fixtures.yml',  
                        [
                            'providers' => [$this]
                        ]
                    );
    }

    public function groups()
    {
        $groups = [
            'Group 1',
            'Group 2',
            'Group 3',
        ];
        
        $key = array_rand($groups);
        
        return $groups[$key];
    }
}