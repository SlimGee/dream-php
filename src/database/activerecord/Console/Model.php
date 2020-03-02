<?php

namespace Dream\Database\ActiveRecord\Console;


use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Input\InputArgument,
    Output\OutputInterface,
    Formatter\OutputFormatterStyle
};

class Model extends Command
{
    protected function configure()
    {
        $this->setName('make:model')->setDescription('Creates a new model.')
        ->addArgument(
            'name', InputArgument::REQUIRED, 'Name of the model.'
        )
        ->addArgument(
            'fields', InputArgument::IS_ARRAY, 'Fields of the model'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $format = new OutputFormatterStyle('green');
        $output->writeln($format->apply("invoke") . ' active record.');

        $name = ucfirst($input->getArgument('name'));
        $path = models_path() . $name . '.php';

        $model = str_replace('{{ model }}', $name, file_get_contents(__DIR__ . '/Stubs/Model.stub.php'));

        $output->writeln($format->apply("create ") . $path);
        file_put_contents($path, $model);
        $output->writeln($format->apply("invoke ") . "composer");
        system('composer dumpautoload -o');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        var_dump($input->getArguments());die;
    }
}
