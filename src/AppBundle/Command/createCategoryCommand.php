<?php
/**
 * Created by PhpStorm.
 * User: melaifa
 * Date: 14/05/2018
 * Time: 17:42
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class createCategoryCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:create-category')
            ->setDescription('Creates a new category.')
            ->setHelp('This command allows you to create a category...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Category Creator',
            '============',
            '',
        ]);

        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a category.');
    }
}