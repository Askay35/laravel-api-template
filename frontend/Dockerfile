### Base stage – install dependencies only once, cached by Docker
FROM node:22-alpine AS deps

WORKDIR /app

COPY package*.json ./
RUN npm install


### Development stage – runs `npm run dev`
FROM deps AS dev

ENV NODE_ENV=development

# Копируем только package files для установки зависимостей
# Остальные файлы будут монтироваться через volume
COPY package*.json ./

EXPOSE 3000
CMD ["npm", "run", "dev", "--", "--host"]


### Build stage – production build
FROM deps AS build

ENV NODE_ENV=production

COPY . .
RUN npm run build


### Production runtime – serves already built static files
# Adjust /app/dist if your build output dir is different (e.g. /app/build)
FROM nginx:alpine AS production

COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
