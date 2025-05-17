#!/bin/bash

UPLOAD_DIR="../public/uploads"

echo "📁 Vérification du dossier $UPLOAD_DIR"

if [ -d "$UPLOAD_DIR" ]; then
  echo "✅ Dossier trouvé"
  echo "🔒 Attribution des permissions à www-data"
  sudo chown -R www-data:www-data "$UPLOAD_DIR"
  sudo chmod -R 775 "$UPLOAD_DIR"
  echo "✅ Permissions mises à jour"
else
  echo "❌ Dossier non trouvé : $UPLOAD_DIR"
  exit 1
fi
