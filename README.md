# Fixtures Helper Symfony Bundle
Symfony bundle with with helpers for fixtures


### Load Command
`bin/console kolyya:fixtures:load`

Will completely clear the database.
And load data from fixtures.

**Run the following commands:**
* `doctrine:database:drop --force`
* `doctrine:database:create`
* `doctrine:schema:update --force`
* `doctrine:fixtures:load --append`

#### Flags
* `--force` - Run the command without confirmation

