<?php

namespace Dream\Database\Migration\Console;

use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Input\InputArgument,
    Output\OutputInterface
};

class Migrate extends Command
{
    protected function configure()
    {
        $this->setName('make:migration')->setDescription('Creates a new migration file.')
        ->addArgument(
            'name', InputArgument::REQUIRED, 'Name of the migration.'
        )
        ->addArgument(
            'fields', InputArgument::IS_ARRAY, 'Database fields'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = time();
        $keywords = [
            'create',
            'add'
        ];

        $name = $input->getArgument('name');
        $fname = $date . '_' . $name . '.php';
        $name = explode('_', $name);
        $name = implode('',array_map(function ($a){
                return ucfirst($a);
            }, $name)
        );
        $mig = str_replace(
            '{{ name }}',
            $name,
            file_get_contents(__DIR__ . '/stubs/migration.stub.php')
        );


        file_put_contents('db/migrations/' . $fname, $mig);
        // Some imaginary logic here...
        $output->writeln('Migration created.');
    }
}
