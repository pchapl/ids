# Symfony bundle provides id type autoconfiguration for doctrine

## Usage

`composer require pchapl/ids`

Enable bundle in `config/bundles.php`:

```php
    PChapl\DoctrineIdBundle\DoctrineIdBundle::class => ['all' => true],
```

Create Id classes like

```php

namespace App\Data;

use PChapl\DoctrineIdBundle\Id\Base;

final class AccountId extends Base
{
}

```

Configure the bundle with types in the `config/services.yaml`:

```yaml
parameters:
  doctrine_id:
    types:
      account_id: App\Data\AccountId
      user_id: App\Data\UserId
```

Then just use in entities

```php

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\Column(type="account_id")
     */
    private AccountId $id;

    public function __construct(AccountId $id)
    {
        $this->id = $id;
    }

    public function getId(): AccountId
    {
        return $this->id;
    }
```

There are two ways to instantiate ids: implement Factory interface for `Base::new` method or just call `Base::fromValue`
with generated string id:

```php
/** @var \Hidehalo\Nanoid\Client $nanoidClient */

$factory = new class($nanoidClient) implements \PChapl\DoctrineIdBundle\Id\Factory {
    public function __construct(private \Hidehalo\Nanoid\Client $nano)
    {
    }

    public function generate(): string
    {
        return $this->nano->generateId();
    }
};

$account1 = new Account(AccountId::new($factory));
$account2 = new Account(AccountId::fromValue($nanoidClient->generateId()));
$account3 = new Account(AccountId::fromValue(\Ramsey\Uuid\Uuid::uuid4()->toString()));

```
