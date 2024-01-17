# Wind Eloquent

Async/Coroutine Laravel Database for Wind Framework.

Usage:

1. Make sure you have right database config in `config/database.php`.
2. Add `\Wind\Eloquent\Component::class` to `config/components.php`.
3. Now you can use async/coroutine, connection pool, high concurrency Eloquent.

Examples:

```php
// use DB Facade
use Illuminate\Support\Facades\DB

$user = DB::table('users')->first();
print_r($user);

// define a user model, and query by model
class User extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'users';

    public function books()
    {
        return $this->hasMany(Books::class);
    }

}

$user = User::query()->with('books')->first();
print_r($user?->toArray());

```
