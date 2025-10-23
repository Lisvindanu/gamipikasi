#!/bin/bash

# GDGoC Backup Script
# Create backup of database and storage files for migration

echo "🎯 GDGoC Backup Tool"
echo "===================="

# Configuration
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="$HOME/gdgoc_backups"
DB_NAME="gamifikasi_gdgoc"
APP_DIR="/var/www/gamipikasi"

# Create backup directory
mkdir -p $BACKUP_DIR
echo "📁 Backup directory: $BACKUP_DIR"

# Database credentials
read -p "Enter MySQL username [root]: " DB_USER
DB_USER=${DB_USER:-root}
read -sp "Enter MySQL password: " DB_PASS
echo ""

# Step 1: Backup Database
echo ""
echo "📦 Step 1: Backing up database..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/gdgoc_db_$TIMESTAMP.sql.gz

if [ $? -eq 0 ]; then
    echo "✅ Database backup created: gdgoc_db_$TIMESTAMP.sql.gz"
    DB_SIZE=$(du -h $BACKUP_DIR/gdgoc_db_$TIMESTAMP.sql.gz | cut -f1)
    echo "   Size: $DB_SIZE"
else
    echo "❌ Database backup failed"
    exit 1
fi

# Step 2: Backup Storage Files
echo ""
echo "📦 Step 2: Backing up storage files..."
cd $APP_DIR
tar -czf $BACKUP_DIR/gdgoc_storage_$TIMESTAMP.tar.gz \
    storage/app/public \
    storage/app/avatars \
    storage/app/attachments \
    2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Storage backup created: gdgoc_storage_$TIMESTAMP.tar.gz"
    STORAGE_SIZE=$(du -h $BACKUP_DIR/gdgoc_storage_$TIMESTAMP.tar.gz | cut -f1)
    echo "   Size: $STORAGE_SIZE"
else
    echo "❌ Storage backup failed"
    exit 1
fi

# Step 3: Backup .env file
echo ""
echo "📦 Step 3: Backing up .env configuration..."
cp $APP_DIR/.env $BACKUP_DIR/env_$TIMESTAMP.backup
echo "✅ .env backup created: env_$TIMESTAMP.backup"

# Step 4: Create backup info file
echo ""
echo "📝 Creating backup info..."
cat > $BACKUP_DIR/backup_info_$TIMESTAMP.txt <<EOF
GDGoC Backup Information
========================
Created: $(date)
Server: $(hostname)
Database: $DB_NAME
App Directory: $APP_DIR

Files:
------
1. Database: gdgoc_db_$TIMESTAMP.sql.gz ($DB_SIZE)
2. Storage: gdgoc_storage_$TIMESTAMP.tar.gz ($STORAGE_SIZE)
3. Config: env_$TIMESTAMP.backup

Restore Instructions:
--------------------
1. Transfer files to new server:
   scp gdgoc_db_$TIMESTAMP.sql.gz user@new-server:/tmp/
   scp gdgoc_storage_$TIMESTAMP.tar.gz user@new-server:/tmp/

2. On new server, run:
   ./migrate.sh /tmp/gdgoc_db_$TIMESTAMP.sql.gz /tmp/gdgoc_storage_$TIMESTAMP.tar.gz

3. Update .env with new configuration

Database Stats:
--------------
EOF

# Add database stats
mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "
SELECT 'Users' as Table_Name, COUNT(*) as Count FROM users
UNION ALL
SELECT 'Tasks', COUNT(*) FROM tasks
UNION ALL
SELECT 'Points', COUNT(*) FROM points
UNION ALL
SELECT 'Badges', COUNT(*) FROM badges
UNION ALL
SELECT 'Departments', COUNT(*) FROM departments;
" >> $BACKUP_DIR/backup_info_$TIMESTAMP.txt 2>/dev/null

echo "✅ Backup info created: backup_info_$TIMESTAMP.txt"

# Summary
echo ""
echo "🎉 Backup completed successfully!"
echo "=================================="
echo ""
echo "📂 Backup location: $BACKUP_DIR"
echo ""
echo "📦 Files created:"
ls -lh $BACKUP_DIR/*_$TIMESTAMP.* | awk '{print "   " $9 " (" $5 ")"}'
echo ""
echo "📋 Next steps:"
echo "1. Download backup files to your local machine:"
echo "   scp -r $(whoami)@$(hostname):$BACKUP_DIR ."
echo ""
echo "2. Upload to new server:"
echo "   scp $BACKUP_DIR/gdgoc_db_$TIMESTAMP.sql.gz user@new-server:/tmp/"
echo "   scp $BACKUP_DIR/gdgoc_storage_$TIMESTAMP.tar.gz user@new-server:/tmp/"
echo ""
echo "3. On new server, run migration script:"
echo "   ./migrate.sh /tmp/gdgoc_db_$TIMESTAMP.sql.gz /tmp/gdgoc_storage_$TIMESTAMP.tar.gz"
echo ""
echo "⚠️  Keep these backup files safe until migration is verified!"
