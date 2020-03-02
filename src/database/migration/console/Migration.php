<?php

namespace Dream\Database\Migration\Console;

use Dream\Database\Schema;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Input\InputArgument,
    Output\OutputInterface
};
use Symfony\Component\Finder\Finder;

class Migration extends Command
{
    protected function configure()
    {
        $this->setName('db:migrate')->setDescription('Run the latest migration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        require_once 'db/schema.php';
        $date = date('Y-m-d', Schema::version());
        $finder = Finder::create()->files()->name('*.php')->in('db/migrations');
        foreach ($finder->getIterator() as $file) {
            if ((int)$file->getMTime() > (int)Schema::version()) {
                require_once $file->getPathname();
                $tokens = explode('_', $file->getFilename());
                $name = implode('', array_map(function ($v) {
                                if (!is_numeric((string)$v)) {
                                    return ucfirst($v);
                                }
                            }, $tokens)
                        );
                $name = rtrim($name, '.php');
                file_put_contents(
                    'db/schema.php',
                    "<?php\n\nuse Dream\Database\Schema;\n\nSchema::version('{$file->getMTime()}');"
                );
                $output->writeln("Migrating ({$name}) ...");
                (new $name())->change();
            }
        }
    }
}
