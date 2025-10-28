# Docker Setup for TimeSheet Application

## ✅ تم إنشاء Dockerfile بنجاح!

تم إنشاء الملفات التالية:
- `Dockerfile` - لبناء صورة Docker
- `docker-compose.yml` - لإدارة الحاويات
- `.dockerignore` - لاستبعاد الملفات غير الضرورية
- `README-Docker.md` - تعليمات مفصلة

## 🚀 كيفية التشغيل

### 1. تشغيل سريع
```bash
# بناء وتشغيل التطبيق
docker-compose up -d --build

# الوصول للتطبيق
open http://localhost:8000
```

### 2. أوامر Docker منفصلة
```bash
# بناء الصورة
docker build -t timesheet-app .

# تشغيل الحاوية
docker run -d --name timesheet-app -p 8000:80 timesheet-app

# إيقاف الحاوية
docker stop timesheet-app
docker rm timesheet-app
```

## 🔑 بيانات الدخول الافتراضية

### Super Admin
- **Email**: `admin@example.com`
- **Password**: `password`

### Manager
- **Email**: `manager1@example.com`
- **Password**: `password`

### Client
- **Email**: `client@example.com`
- **Password**: `password`

## 🛠️ أوامر مفيدة

### إدارة الحاويات
```bash
# عرض الحاويات النشطة
docker ps

# عرض logs
docker logs timesheet-app

# الدخول للحاوية
docker exec -it timesheet-app bash

# إيقاف وإزالة الحاوية
docker-compose down
```

### أوامر Laravel داخل الحاوية
```bash
# تشغيل migrations
docker exec timesheet-app php artisan migrate

# تشغيل seeders
docker exec timesheet-app php artisan db:seed

# مسح cache
docker exec timesheet-app php artisan cache:clear
docker exec timesheet-app php artisan config:clear

# إعادة تعيين قاعدة البيانات
docker exec timesheet-app php artisan migrate:fresh --seed
```

## 📁 بنية الملفات

```
timesheet-app/
├── Dockerfile              # إعدادات Docker
├── docker-compose.yml      # إعدادات Docker Compose
├── .dockerignore          # ملفات مستبعدة من Docker
├── README-Docker.md       # تعليمات مفصلة
├── DOCKER-SETUP.md        # هذا الملف
└── ... (ملفات Laravel)
```

## 🔧 إعدادات Docker

### Dockerfile
- **Base Image**: PHP 8.2 with Apache
- **Extensions**: PDO, SQLite, GD, BCMath, etc.
- **Composer**: Latest version
- **Database**: SQLite (مدمج)
- **Port**: 80

### docker-compose.yml
- **Port Mapping**: 8000:80
- **Volumes**: storage, database
- **Environment**: Production ready
- **Network**: timesheet-network

## 🐛 استكشاف الأخطاء

### مشكلة 500 Error
```bash
# تفعيل debug mode
docker exec timesheet-app sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' /var/www/html/.env

# مسح cache
docker exec timesheet-app php artisan cache:clear
```

### مشكلة قاعدة البيانات
```bash
# إعادة تعيين قاعدة البيانات
docker exec timesheet-app php artisan migrate:fresh --seed
```

### مشكلة الصلاحيات
```bash
# إصلاح صلاحيات الملفات
docker exec timesheet-app chown -R www-data:www-data /var/www/html/storage
docker exec timesheet-app chmod -R 775 /var/www/html/storage
```

## 📊 المميزات

✅ **Multi-Manager System** - نظام إدارة متعدد المدراء  
✅ **Role-Based Access** - صلاحيات حسب الدور  
✅ **Time Tracking** - تتبع الوقت  
✅ **Invoice System** - نظام الفواتير  
✅ **Client Approval** - موافقة العملاء  
✅ **Reports & Analytics** - التقارير والإحصائيات  
✅ **Docker Ready** - جاهز للتشغيل في Docker  

## 🌐 الوصول للتطبيق

- **URL**: http://localhost:8000
- **Login**: http://localhost:8000/login
- **Register**: http://localhost:8000/register

---

**تم إنشاء Dockerfile بنجاح! 🎉**
