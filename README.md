# Fixtures Helper Symfony Bundle
Symfony bundle with helpers for fixtures


### Load Command
`bin/console kolyya:fixtures:load`

Will completely clear the database.
And load data from fixtures.

**Run the following commands:**
* `doctrine:database:drop --force`
* `doctrine:database:create`
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
      drop: { "--connection": 'my_connection' }
      create: { "--connection": 'my_connection' }
      update: { "--em": 'my_em' }
      load: { "--em": 'my_em' }
```

