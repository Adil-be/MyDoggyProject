SYMFONY_CONSOLES = php bin/console

start:
	symfony server:start

stop:
	symfony server:stop

db.create:
	$(SYMFONY_CONSOLES) doctrine:database:create --if-not-exists

db.drop:
	$(SYMFONY_CONSOLES) doctrine:database:drop --force --if-exists

migration:
	$(SYMFONY_CONSOLES) make:migration

migrate:
	$(SYMFONY_CONSOLES) doctrine:migration:migrate -n

fixtures:
	$(SYMFONY_CONSOLES) doctrine:fixture:load -n

db.recreate:db.drop db.create migrate fixtures
