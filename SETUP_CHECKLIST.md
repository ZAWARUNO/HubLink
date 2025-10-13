# HubLink Setup Checklist

Quick checklist untuk setup HubLink setelah clone/install.

## ✅ Initial Setup

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
```bash
# Update .env dengan database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hublink
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate
```

### 4. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Create digital products folder
mkdir storage/app/digital-products

# Set permissions (Linux/Mac)
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 5. Midtrans Configuration
```bash
# Add to .env
MIDTRANS_SERVER_KEY=your-sandbox-server-key
MIDTRANS_CLIENT_KEY=your-sandbox-client-key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

Get your Midtrans keys from:
- Sandbox: https://dashboard.sandbox.midtrans.com/
- Production: https://dashboard.midtrans.com/

### 6. Test Midtrans Config
```bash
php artisan midtrans:test
```

## ✅ Development Workflow

### Start Development Server
```bash
php artisan serve
```

### Watch Assets (if using Vite/Mix)
```bash
npm run dev
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## ✅ Feature-Specific Setup

### Digital Products
1. **Folder Structure:**
   ```
   storage/app/digital-products/
   ├── .gitignore
   ├── README.md
   └── [uploaded files]
   ```

2. **Upload Limits:**
   - Check `php.ini` settings:
     ```ini
     upload_max_filesize = 10M
     post_max_size = 10M
     max_execution_time = 300
     ```

3. **Test Upload:**
   - Login to CMS
   - Create Template component
   - Upload test PDF/ZIP
   - Verify file in `storage/app/digital-products/`

### Payment Testing
1. **Sandbox Mode:**
   - Use test credit cards from Midtrans docs
   - QRIS: Use simulator in Midtrans dashboard
   - GoPay: Use test account

2. **Debug Orders:**
   ```bash
   php artisan order:debug ORDER-xxx
   ```

3. **Check Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

## ✅ Production Deployment

### Before Going Live

- [ ] Update `.env` to production values
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Use production Midtrans keys
- [ ] Set `MIDTRANS_IS_PRODUCTION=true`
- [ ] Configure notification URL in Midtrans dashboard
- [ ] Setup SSL certificate
- [ ] Configure proper file permissions
- [ ] Setup automated backups for `storage/app/digital-products/`
- [ ] Setup cron for Laravel scheduler (if used)
- [ ] Optimize application:
  ```bash
  composer install --optimize-autoloader --no-dev
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

### Backup Strategy

```bash
# Backup database
mysqldump -u root -p hublink > backup-$(date +%Y%m%d).sql

# Backup digital products
tar -czf digital-products-backup-$(date +%Y%m%d).tar.gz storage/app/digital-products/

# Backup entire storage
tar -czf storage-backup-$(date +%Y%m%d).tar.gz storage/
```

## ✅ Common Issues

### Issue: "Class not found"
```bash
composer dump-autoload
```

### Issue: "Permission denied"
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Or for development
chmod -R 777 storage bootstrap/cache
```

### Issue: "No application encryption key"
```bash
php artisan key:generate
```

### Issue: "SQLSTATE connection refused"
- Check database is running
- Verify credentials in `.env`
- Check `DB_HOST` and `DB_PORT`

### Issue: "419 Page Expired"
- Clear browser cache
- Check CSRF token in forms
- Verify `APP_URL` in `.env` matches actual URL

## 📚 Documentation

- **Midtrans Setup:** `MIDTRANS_SETUP.md`
- **Digital Products:** `DIGITAL_PRODUCT_GUIDE.md`
- **Laravel Docs:** https://laravel.com/docs

## 🔧 Useful Commands

```bash
# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName -m

# Run specific migration
php artisan migrate --path=/database/migrations/filename.php

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (WARNING: deletes all data)
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Create new command
php artisan make:command CommandName

# List all routes
php artisan route:list

# Tinker (Laravel REPL)
php artisan tinker
```

## 🎯 Testing Checklist

- [ ] User registration works
- [ ] User login works
- [ ] Domain creation works
- [ ] Page builder loads
- [ ] Component drag & drop works
- [ ] Digital product upload works
- [ ] Checkout page loads
- [ ] Midtrans payment works
- [ ] Payment callback updates order status
- [ ] Download page accessible after payment
- [ ] File download works
- [ ] Download token expires correctly

## 📞 Support

If you encounter issues:
1. Check logs: `storage/logs/laravel.log`
2. Enable debug mode temporarily: `APP_DEBUG=true`
3. Check documentation files
4. Review error messages carefully
5. Google the error message
6. Check Laravel/Midtrans documentation

---

**Last Updated:** October 13, 2025
