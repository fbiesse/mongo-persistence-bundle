fbiesse/mongo-persistence-bundle
======

Minimal bundle to use MongoDB for developpers that want a simple way to communicate with MongoDb Database.

The bundle just consists in some utility classes (counter for sequences, abstract repository to unserialize collections) and the profiling.

Installation
-------------

```bash
composer require fbiesse/mongo-persistence-bundle
```

In your app/AppKernel.php

```php
public function registerBundles()
    {
        $bundles = [
        ...
            new FBiesse\Sf\Bundle\MongoPersistenceBundle\FBiesseSfBundleMongoPersistenceBundle(),
        ...
       ];
       return $bundles;
   }
```

In your app/config/config.yml

```yml
f_biesse_sf_bundle_mongo_persistence:
  server_uri: %database_uri% # eg : mongodb://127.0.0.1
  authmechanism: '' # SCRAM-SHA-1 or MONGODB-CR
  db_name: '%db_name%'
```

Usage
-------------

