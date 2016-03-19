Caramel\Models\Storage
===============

this class handles all data storage
deep array setters and getters are separated by &quot;.&quot;
Class Storage




    * Class name: Storage
    * Namespace: Caramel\Models
                




    Properties
    ----------


    ### $storage

    private array $storage

    



    * Visibility: **private**
            

    Methods
    -------


    ### set

    boolean Caramel\Models\Storage::set($path, $value)

    



    * Visibility: **public**
                

            #### Arguments
                    * $path **mixed**
                    * $value **mixed**
        
    

    ### get

    mixed Caramel\Models\Storage::get(string $path)

    returns a value from the storage



    * Visibility: **public**
                

            #### Arguments
                    * $path **string**
        
    

    ### merge

    mixed Caramel\Models\Storage::merge(array $array)

    merge an array into the storage



    * Visibility: **public**
                

            #### Arguments
                    * $array **array**
        
    

    ### extend

    array Caramel\Models\Storage::extend(string $path, array|string $value)

    extends an array in the storage



    * Visibility: **public**
                

            #### Arguments
                    * $path **string**
                    * $value **array|string**
        
    

    ### has

    array Caramel\Models\Storage::has(string $path)

    returns if the storage has the passed value



    * Visibility: **public**
                

            #### Arguments
                    * $path **string**
        
    

    ### delete

    boolean Caramel\Models\Storage::delete($path)

    



    * Visibility: **public**
                

            #### Arguments
                    * $path **mixed**
        
    

    ### find

    array Caramel\Models\Storage::find(array|string $attrs, string $value, \Caramel\Models\Storage $item)

    searches for an item in the current tree
if we pass an array it has the same behaviour
iterates over the array values recursively



    * Visibility: **public**
                

            #### Arguments
                    * $attrs **array|string**
                    * $value **string**
                    * $item **[Caramel\Models\Storage](#Caramel-Models-Storage)**
        
    

    ### findHelper

    array Caramel\Models\Storage::findHelper(array $found, \Caramel\Models\Storage $item, array|string $attrs, string $value)

    outsourcing the repeating find process



    * Visibility: **private**
                

            #### Arguments
                    * $found **array**
                    * $item **[Caramel\Models\Storage](#Caramel-Models-Storage)**
                    * $attrs **array|string**
                    * $value **string**
        
    

    ### getter

    array Caramel\Models\Storage::getter($path)

    the method to set data



    * Visibility: **private**
                

            #### Arguments
                    * $path **mixed**
        
    

    ### setter

    boolean Caramel\Models\Storage::setter($path, $value)

    



    * Visibility: **private**
                

            #### Arguments
                    * $path **mixed**
                    * $value **mixed**
        
    

    ### createPath

    array|mixed Caramel\Models\Storage::createPath($path)

    returns the path as an array



    * Visibility: **private**
                

            #### Arguments
                    * $path **mixed**
        
    
