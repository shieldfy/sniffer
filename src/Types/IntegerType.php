<?php
namespace Shieldfy\Sniffer\Types;
use Shieldfy\Sniffer\TypeInterface;

class IntegerType implements TypeInterface
{
    public function sniff($input)
    {
        return is_numeric($input);
    }

    public function __toString(){
    	return 'number';
    }
}
