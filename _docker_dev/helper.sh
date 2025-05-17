#!/bin/bash

# Helper multi-commande pour le projet ferme-back (exécuté depuis son propre dossier)

PHP_CONTAINER="ferme-back-php"
NGINX_CONTAINER="ferme-back-nginx"

show_help() {
  echo ""
  echo "🛠️  Helper Docker - ferme-back"
  echo ""
  echo "Commandes disponibles :"
  echo "  up                 → Démarrer les services"
  echo "  down               → Arrêter les services"
  echo "  destroy            → Supprimer complètement les conteneurs"
  echo "  refresh            → Redémarrer complètement les services avec rebuild"
  echo "  restart            → Redémarrer les services"
  echo "  logs-php           → Afficher les logs du conteneur PHP"
  echo "  logs-nginx         → Afficher les logs du conteneur Nginx"
  echo "  sh-php             → Accès shell dans le conteneur PHP"
  echo "  composer [...]     → Lancer Composer dans le conteneur PHP"
  echo "  symfony [...]      → Lancer la CLI Symfony dans le conteneur PHP"
  echo "  env                → Afficher les variables d'environnement actuelles"
}

if [ $# -lt 1 ]; then
  show_help
  exit 0
fi

COMMAND=$1
shift

case "$COMMAND" in
  up)
    docker compose up -d
    ;;
  down)
    docker compose down
    ;;
  destroy)
    docker compose down --volumes --remove-orphans
    ;;
  refresh)
    docker compose down
    docker compose up -d --build
    ;;
  restart)
    docker compose restart
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
    docker exec -it "$PHP_CONTAINER" symfony console "$@"
    ;;
  env)
    docker exec -it "$PHP_CONTAINER" sh -c '
      echo "APP_ENV=$APP_ENV"
      echo "APP_DEBUG=$APP_DEBUG"
      echo "DATABASE_URL=$DATABASE_URL"
      echo "JWT_PASSPHRASE=$JWT_PASSPHRASE"
    '
   ;;
  *)
    echo "❌ Commande inconnue: $COMMAND"
    show_help
    exit 1
    ;;
esac
