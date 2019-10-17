<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use App\Service\ContainerParametersHelper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovieRepository;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Media;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;



class ImportMediaCommand extends ContainerAwareCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import-media';

    private $uploadDir;

    private $container;

    private $doctrine;

    private $em;

    public function __construct($uploadDir, ContainerInterface $container) {
        parent::__construct();
        $this->uploadDir = $uploadDir;
        $this->container = $container;
        $this->doctrine  = $this->container->get('doctrine');
        $this->em = $this->doctrine->getEntityManager();
       // $this->params = $params;
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Import File and Create Event .')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows import files...')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
                // outputs multiple lines to the console (adding "\n" at the end of each line)
            $output->writeln([
                'Start Import Media',
                '============',
                '',
            ]);

            $this->import($output);
           
            $output->writeln([
                'Finish Import Media',
                '============',
                '',
            ]);
    }

    protected function import(OutputInterface $output){

        try{

            $finder = new Finder();

            $directory = $this->uploadDir;

            $output->writeln([
                'Searching File: ' . $this->uploadDir,
                '============',
                '',
            ]);
            
            $finder->files()->in($directory);

            // check if there are any search results
            if ($finder->hasResults()) {

                $this->em->getConnection()->beginTransaction(); 

                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    
                    $fileNameWithExtension = $file->getRelativePathname();

                    $output->writeln([
                        'Importe File: ' . $fileNameWithExtension,
                        '============',
                        '',
                    ]);
                    
                    
                    $this->saveEntity($fileNameWithExtension);
                   
                }

                $this->em->getConnection()->commit();

                $output->writeln([
                    'Importe Success',
                    '============',
                    '',
                ]);
            }
        }catch(\Exception $e){

                $em->getConnection()->rollBack();

                $output->writeln([
                    'Error: ' . $e->getMessage(),
                    '============',
                    '',
                ]);
        }
    } 
    
    public function saveEntity($fileNameWithExtension){

        // Get Random User
        $user =  $this->doctrine
        ->getRepository(User::class)
        ->findOneRandom();
        
        // Get Random Event
        $event =  $this->doctrine
                ->getRepository(Event::class)
                ->findOneRandom();
        
        // Create Media
        $media = new media();
        $media->setFilename($fileNameWithExtension);
        $media->setUser($user);
        $media->setEVent($event);

        $this->em->persist($media);
        $this->em->flush();
    }
}