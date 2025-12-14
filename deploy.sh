#!/bin/bash
set -e  # остановиться при любой ошибке

echo "🔄 Сборка Docker-образов..."
docker-compose build

echo "✅ Docker-образы собраны"

echo "🚀 Поднимаем контейнеры..."
docker-compose up -d


echo "🔄 Выполняем миграции..."
docker-compose exec backend php artisan migrate --force

echo "✅ Миграции выполнены"

echo "🔄 Перезапускаем очереди..."
docker-compose exec backend php artisan queue:restart

echo "✅ Очереди перезапущены"


echo "✅ Деплой завершен"