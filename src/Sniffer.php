<?php
namespace Shieldfy\Sniffer;
use Closure;

class Sniffer
{
    protected $types = [];

    /**
     * Detect constructor.
     *
     */
    public function __construct()
    {
        $this->types = [
            'integer'=>\Shieldfy\Sniffer\Types\IntegerType::class,
            'string'=>\Shieldfy\Sniffer\Types\StringType::class,
            'json'=>\Shieldfy\Sniffer\Types\JsonType::class,
            'serialize'=>\Shieldfy\Sniffer\Types\SerializeType::class,
        ];
    }

    /**
    * Defines which types to use to overwrite the default ones
    *
    * @param Array $types
    * 
    */
    public function use(Array $types)
    {
        $this->types = $types;
        return $this;
    }


    /**
    * Register new type on the runtime
    * 
    * @param String $name
    *
    * @param String $class
    */
    public function register($name,$class)
    {
        $this->types[$name] = $class;
        return $this;
    }

    /**
    * Start sniffing the content
    *
    * @param Mixed $input
    *
    * @return  $type
    */
    public function sniff($input){
        if(is_array($input)) return $this->sniffAll($input);
        return $this->run($input);
    }

    /**
    * Start Sniffing array
    *
    * @param Array $inputs
    *
    * @return Array $result
    */
    private function sniffAll(Array $inputs){
        $result = [];
        foreach ($inputs as $key=>$input):
            $result[$key] = $this->run($input);
        endforeach;
        return $result;
    }

    /**
    * Run the tests on the input
    *
    * @param Mixed $input
    *
    * @return Mixed result
    */
    private function run($input){
        foreach($this->types as $name=>$typeClass):

            //check if it custom type closure
            if(is_object($typeClass) && $typeClass instanceof Closure){
                if($typeClass($input) === true) return $name;
                continue;
            }

            if((new $typeClass)->sniff($input) === true){
                return $name;
            }

        endforeach;
        //nothing captuared
        return 'unknown';
    }

    
}
