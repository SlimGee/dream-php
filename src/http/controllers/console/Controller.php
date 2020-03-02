<?php

namespace Dream\Http\Controllers\Console;


use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Input\InputArgument,
    Output\OutputInterface,
    Formatter\OutputFormatterStyle
};

class Controller extends Command
{
    protected function configure()
    {
        $this->setName('make:controller')->setDescription('Creates a new controller.')
        ->addArgument(
            'name', InputArgument::REQUIRED, 'Name of the controller.'
        )
        ->addArgument(
            'actions', InputArgument::IS_ARRAY, 'Controller actions'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $format = new OutputFormatterStyle('green');
        $output->writeln($format->apply("invoke") . ' controller');

        $controller_name = ucfirst($input->getArgument('name'));
        $path = controllers_path() . $controller_name . '.php';

        $controller = str_replace(
            '{{ controller }}',
            $controller_name,
            file_get_contents(__DIR__ . '/stubs/controller.stub.php')
        );

        $code = "";
        $actions = $input->getArgument('actions');

        foreach ($actions as $name) {
            $action = str_replace(
                '{{ name }}',
                $name,
                file_get_contents(__DIR__ . '/stubs/action.stub.php')
            );
            $code .= $action . "\n";
            $view_path = views_path() . strtolower($controller_name);

            if (is_dir($view_path)) {
                file_put_contents(
                    $view_path . '/' . $name . '.php',
                    "<h1>" . strtolower($controller_name) . "#" . $name . "</h1>"
                );
            } else {
                mkdir($view_path);
                file_put_contents(
                    $view_path . '/' . $name . '.php',
                    "<h1>" . strtolower($controller_name) . "#" . $name . "</h1>"
                );
            }
            $output->writeln($format->apply("create ") . $view_path . '/' . $name . '.php');
        }

        $controller = str_replace(
            '{{ actions }}',
            rtrim($code),
            $controller
        );

        $output->writeln($format->apply("create ") . $path);
        file_put_contents($path, $controller);
        $output->writeln($format->apply("invoke ") . "composer");
        system('composer dumpautoload -o');
    }
}
