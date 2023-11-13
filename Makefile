COMPOSE_FILES=./docker-compose.yml
POSTGRES_COMPOSE_FILES=./.docker/docker-compose-postgres.yml

help:
	@echo "build"
	@echo "  Build docker image and start containers."
	@echo ""
	@echo "up"
	@echo "  Create and start containers."
	@echo ""
	@echo "up-graylog"
	@echo "  Create and up graylog"
	@echo ""
	@echo "recreate"
	@echo " Force to recreate containers"
	@echo ""
	@echo "restart"
	@echo "  Restart containers."
	@echo ""
	@echo "status"
	@echo "  Shows the status of the current containers."
	@echo ""
	@echo "shell"
	@echo "  Starting a zsh shell as \"www-data\" user in web container."
	@echo ""
	@echo "shell-as-root"
	@echo "  Starting a bash shell as \"root\" user in web container."
	@echo ""
	@echo "destroy"
	@echo "  Stop and remove containers, networks, images, and volumes."
	@echo ""
	@echo "pull-git"
	@echo "  Pull git and submodule"
	@echo ""
	@echo "submodules"
	@echo "  Pull and update  submodules"
	@echo ""
	@echo "destroy"
	@echo "  Stop and remove containers, networks, images, and volumes."

build:
	docker compose -f $(COMPOSE_FILES) build --no-cache

up:
	docker compose -f $(COMPOSE_FILES) -f $(POSTGRES_COMPOSE_FILES) up -d inno_app inno_redis inno_postgres inno_pgadmin

down:
	docker compose -f $(COMPOSE_FILES) -f $(POSTGRES_COMPOSE_FILES) down

restart:
	docker compose -f $(COMPOSE_FILES) -f $(POSTGRES_COMPOSE_FILES) restart

recreate:
	docker compose -f $(COMPOSE_FILES) up -d --force-recreate

status:
	docker compose -f $(COMPOSE_FILES) ps

destroy:
	docker compose -f $(COMPOSE_FILES) down

shell:
	docker compose -f $(COMPOSE_FILES) exec --user=www-data inno_app ash

shell-as-root:
	docker compose -f $(COMPOSE_FILES) exec  --user=root inno_app ash

git:
	git pull --recurse-submodules
