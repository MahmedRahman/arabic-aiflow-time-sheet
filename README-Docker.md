# Docker Setup for TimeSheet Application

## Prerequisites
- Docker
- Docker Compose

## Quick Start

### 1. Build and Run with Docker Compose
```bash
# Build and start the application
docker-compose up --build

# Run in background
docker-compose up -d --build
```

### 2. Access the Application
- Open your browser and go to: `http://localhost:8000`

### 3. Default Login Credentials
- **Super Admin**: 
  - Email: `admin@example.com`
  - Password: `password`
- **Manager**: 
  - Email: `manager1@example.com`
  - Password: `password`
- **Client**: 
  - Email: `client@example.com`
  - Password: `password`

## Manual Docker Commands

### Build the Docker Image
```bash
docker build -t timesheet-app .
```

### Run the Container
```bash
docker run -d \
  --name timesheet-app \
  -p 8000:80 \
  -v $(pwd)/storage:/var/www/html/storage \
  -v $(pwd)/database:/var/www/html/database \
  timesheet-app
```

### Access Container Shell
```bash
docker exec -it timesheet-app bash
```

### View Logs
```bash
docker logs timesheet-app
```

## Development Commands

### Run Migrations (if needed)
```bash
docker exec -it timesheet-app php artisan migrate
```

### Run Seeders (if needed)
```bash
docker exec -it timesheet-app php artisan db:seed
```

### Clear Cache
```bash
docker exec -it timesheet-app php artisan cache:clear
docker exec -it timesheet-app php artisan config:clear
```

### Generate Application Key
```bash
docker exec -it timesheet-app php artisan key:generate
```

## Stopping the Application

### Stop Docker Compose
```bash
docker-compose down
```

### Stop and Remove Container
```bash
docker stop timesheet-app
docker rm timesheet-app
```

## Troubleshooting

### Check Container Status
```bash
docker ps
```

### View Container Logs
```bash
docker logs timesheet-app
```

### Reset Database
```bash
docker exec -it timesheet-app php artisan migrate:fresh --seed
```

### Fix Permissions
```bash
docker exec -it timesheet-app chown -R www-data:www-data /var/www/html/storage
docker exec -it timesheet-app chmod -R 775 /var/www/html/storage
```

## Production Considerations

1. **Environment Variables**: Update `.env` file for production settings
2. **Database**: Consider using MySQL/PostgreSQL for production
3. **SSL**: Configure SSL certificates for HTTPS
4. **Backup**: Set up regular database backups
5. **Monitoring**: Add health checks and monitoring

## File Structure
```
timesheet-app/
├── Dockerfile
├── docker-compose.yml
├── .dockerignore
├── README-Docker.md
└── ... (Laravel application files)
```
