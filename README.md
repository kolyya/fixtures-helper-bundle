# Fixtures Helper Symfony Bundle

Symfony bundle with helpers for fixtures

### Load Command

`bin/console kolyya:fixtures:load`

Will completely clear the database. And load data from fixtures.

**Run the following commands:**

* `doctrine:schema:drop --full-database --force`
* `doctrine:schema:update --force`
* `doctrine:fixtures:load --append`

#### Options

* `--force` - Run the command without confirmation

* `--config[=CONFIG]` - The name of the config to run commands

To use your config, add it to the configuration.

```yaml
kolyya_fixtures_helper:
    # ...
    load:
        my_config:
            drop: { "--em": 'my_em' }
            update: { "--em": 'my_em' }
            load: { "--em": 'my_em' }
```

### Upload File

1. Create a directory, for example `<kernel_project_dir>/assets/fixtures/product`

2. Put files to upload there

3. Inherit DataFixtures class from `Kolyya\FixturesHelperBundle\DataFixtures\BaseUploadFileFixtures`

4. Add `getAssetPath` method to the DataFixtures class. It should return the relative path to the directory.
    ```php
        // ...
        public function getAssetPath(): string
        {
            return '/assets/fixtures/product';
        }
        // ...
    ```

5. Get file
    ```php
        // ...
        $imageFile = $this->getUploadedFile('image.jpg');
        $product->setImageFile($imageFile);
        // ...
    ```

## Development

At the root of the symfony project, create a directory `Kolyya`.

Clone this repository to this directory:

```shell
git clone git@github.com:kolyya/fixtures-helper-bundle.git
```

Add to file `composer.json`:

```json5
{
  //...
  "autoload": {
    "psr-4": {
      //...
      "Kolyya\\FixturesHelperBundle\\": "Kolyya\\fixtures-helper-bundle"
    }
  },
}
```

And execute the command:

```shell
composer dump-autoload
```
