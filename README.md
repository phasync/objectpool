# phasync/objectpool

An efficient object pool implementation for PHP objects.

## Example

With any class that can benefit from object pooling:

```php
class Vector implements phasync\Util\ObjectPoolInterface {
    use phasync\Util\ObjectPoolTrait;

    public string $name;
    public ?PDO $dbConnection;

    /**
     * Instead of creating instances via the constructor, instances must be
     * created via a static method (such as this `create()` function). 
     */
    public static function create(string $name, PDO $dbConnection): static {
        $instance = static::popInstance() ?? new static();
        $instance->name = $name;
        $instance->dbConnection = $dbConnection;
    }

    /**
     * The constructor MUST be declared as private or protected, to ensure
     * you don't leak memory by adding an infinite number of instances to
     * the pool without ever recovering them.
     */
    private function __construct() {}

    /**
     * This function is used to return the instance to the object pool.
     * The function must ensure that a clean object is returned to the
     * object pool, removing any non-scalar values - even if the properties
     * would be overwritten when retrieved. This is to avoid memory leaks.
     */
    public function returnToPool(): void {
        /**
         * It is ESSENTIAL to unset references to values such as
         * `$this->dbInstance` before returning the object to the pool.
         */
        $this->name = '';
        $this->dbConnection = null;
        $this->pushInstance();
    }
}
```

## Warning

Only use this for internal objects that your code uses a lot, and which other developers don't directly work with. This is the only way to ensure that there are no remaining references to the instance when returning the object to the pool. You MUST avoid the case where a developer is retaining a reference to the instance when you add it to the object pool.
