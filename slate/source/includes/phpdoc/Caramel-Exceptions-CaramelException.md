## CaramelException

Class CaramelException




* Class name: CaramelException
* Namespace: Caramel\Exceptions
* Parent class: Exception





### Properties


#### $caramelFile

    private boolean $caramelFile





* Visibility: **private**
* This property is defined by [CaramelException](#caramelexceptionscaramelexception)


#### $caramelLine

    private integer $caramelLine





* Visibility: **private**
* This property is defined by [CaramelException](#caramelexceptionscaramelexception)


### Methods


#### __construct

    mixed Caramel\Exceptions\CaramelException::__construct($message, $file, $line, $code, \Exception $previous)





* Visibility: **public**
* This method is defined by [CaramelException](#caramelexceptionscaramelexception)


##### Arguments
* $message **mixed**
* $file **mixed**
* $line **mixed**
* $code **mixed**
* $previous **Exception**



#### getCaramelFile

    boolean|string Caramel\Exceptions\CaramelException::getCaramelFile()

returns the caramel file



* Visibility: **public**
* This method is defined by [CaramelException](#caramelexceptionscaramelexception)




#### getCaramelLine

    boolean|integer|string Caramel\Exceptions\CaramelException::getCaramelLine()

returns the caramel line



* Visibility: **public**
* This method is defined by [CaramelException](#caramelexceptionscaramelexception)




#### splitFile

    array Caramel\Exceptions\CaramelException::splitFile($file, $root)

splits file into name and path



* Visibility: **private**
* This method is defined by [CaramelException](#caramelexceptionscaramelexception)


##### Arguments
* $file **mixed**
* $root **mixed**



#### displayCaramelErrorFile

    mixed Caramel\Exceptions\CaramelException::displayCaramelErrorFile($root, $file, $line, $function)

displays an exception file



* Visibility: **public**
* This method is defined by [CaramelException](#caramelexceptionscaramelexception)


##### Arguments
* $root **mixed**
* $file **mixed**
* $line **mixed**
* $function **mixed**


