<?php
namespace Dream\Container;

require_once 'vendor/autoload.php';
require_once '../../vendor/autoload.php';


/**
 *
 */
interface Domain
{
    public function getName();
}

/**
 *
 */
class User implements Domain
{
    public function getName()
    {
    }
}

/**
 *
 */
class Inventory
{
    public $user;

    function __construct(Domain $user)
    {
        $this->user = $user;
    }
}

/**
 *
 */
class Orders
{
    public $user;
    public function __construct(Domain $user)
    {
        $this->user = $user;
    }
}
/**
 *
 */

eval("
class B0
{

    function __construct()
    {

    }
}
");


for ($i=1; $i <= 100; $i++) {
    $v = $i - 1;
    eval("
    class B{$i}
    {
        public \$b{$i};

        function __construct(B{$v} \$a)
        {
            \$this->b{$i} = \$a;
        }
    }
    ");
}




$start = microtime(true);

$container = new Container;
$container = $container->configure([
    Inventory::class => [
        'user' => 'Given Ncube',
    ],
    'shared' => [
        User::class
    ]
]);


for ($i=0; $i < 20000; $i++) {
    $var = $container->get(\B100::class);
}

$end = microtime(true) - $start;

$info = [
    'time' => $end,
    'files' => count(get_included_files()),
    'memory' => memory_get_peak_usage() / 1024 / 1024
];
var_dump($info);
