#!/bin/bash

# Helper multi-commande pour le projet ferme-back

DOCKER_COMPOSE_FILE="_docker/docker-compose.yml"
DOCKER_COMPOSE_PROD_FILE="_docker/docker-compose.prod.yml"

PHP_CONTAINER="ferme-back-php"
NGINX_CONTAINER="ferme-back-nginx"

show_help() {
  echo ""
  echo "🛠️  Helper Docker - ferme-back"
  echo ""
  echo "Commandes disponibles :"
  echo "  up                 → Démarrer les services (développement)"
  echo "  down               → Arrêter les services (développement)"
  echo "  destroy            → Supprimer complètement les conteneurs (développement)"
  echo "  refresh            → Redémarrer complètement les services (développement)"
  echo "  restart            → Redémarrer les services (développement)"
  echo "  logs-php           → Afficher les logs du conteneur PHP"
  echo "  logs-nginx         → Afficher les logs du conteneur Nginx"
  echo "  sh-php             → Accès shell dans le conteneur PHP"
  echo "  composer [...]     → Lancer Composer dans le conteneur PHP"
  echo "  symfony [...]      → Lancer la CLI Symfony dans le conteneur PHP"
}

if [ $# -lt 1 ]; then
  show_help
  exit 0
fi

COMMAND=$1
shift

case "$COMMAND" in
  up)
    docker compose -f "$DOCKER_COMPOSE_FILE" up -d
    ;;
  down)
    docker compose -f "$DOCKER_COMPOSE_FILE" down
    ;;
  destroy)
    docker compose -f "$DOCKER_COMPOSE_FILE" down --volumes --remove-orphans
    ;;
  refresh)
    docker compose -f "$DOCKER_COMPOSE_FILE" down
    docker compose -f "$DOCKER_COMPOSE_FILE" up -d --build
    ;;
  restart)
    docker compose -f "$DOCKER_COMPOSE_FILE" restart
    ;;
  logs-php)
    docker logs -f "$PHP_CONTAINER"
    ;;
  logs-nginx)
    docker logs -f "$NGINX_CONTAINER"
    ;;
  sh-php)
    docker exec -it "$PHP_CONTAINER" sh
    ;;
  composer)
    docker exec -it "$PHP_CONTAINER" composer "$@"
    ;;
  symfony)
    docker exec -it "$PHP_CONTAINER" symfony "$@"
    ;;
  *)
    echo "❌ Commande inconnue: $COMMAND"
    show_help
    exit 1
    ;;
esac
