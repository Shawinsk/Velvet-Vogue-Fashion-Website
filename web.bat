@echo off
echo Creating Velvet Vogue Project Structure...
echo.

:: Create main project directory
mkdir velvet-vogue
cd velvet-vogue

:: Create admin directory and files
echo Creating Admin Panel...
mkdir admin
cd admin

:: Create admin PHP files
echo ^<?php echo "Admin Index Dashboard"; ?^> > index.php
echo ^<?php echo "Manage Products"; ?^> > manage_products.php
echo ^<?php echo "View Orders"; ?^> > view_orders.php
echo ^<?php echo "Admin Settings"; ?^> > settings.php
echo ^<?php echo "Get Setting API"; ?^> > get_setting.php
echo ^<?php echo "Process Update Setting"; ?^> > process_update_setting.php

:: Create admin includes
mkdir includes
echo ^<?php echo "Admin Authentication"; ?^> > includes\admin_auth.php

cd ..

:: Create assets directory structure
echo Creating Assets...
mkdir assets

:: Create CSS directory
mkdir assets\css
echo /* Velvet Vogue Custom Styles */ > assets\css\style.css
echo /* Admin Panel Styles */ > assets\css\admin.css

:: Create JavaScript directory
mkdir assets\js
echo // Velvet Vogue Main JavaScript > assets\js\main.js
echo // Admin Panel JavaScript > assets\js\admin.js

:: Create images directory
mkdir assets\images
mkdir assets\images\product_images
echo. > assets\images\logo.png

:: Create includes directory
echo Creating Includes...
mkdir includes
echo ^<?php echo "Database Connection"; ?^> > includes\db_connect.php
echo ^<?php echo "Website Header"; ?^> > includes\header.php
echo ^<?php echo "Website Footer"; ?^> > includes\footer.php
echo ^<?php echo "Common Functions"; ?^> > includes\functions.php

:: Create main website files
echo Creating Main Website Files...
echo ^<?php echo "Homepage"; ?^> > index.php
echo ^<?php echo "Products Listing"; ?^> > products.php
echo ^<?php echo "Product Details"; ?^> > product_detail.php
echo ^<?php echo "Shopping Cart"; ?^> > cart.php
echo ^<?php echo "Checkout"; ?^> > checkout.php
echo ^<?php echo "User Login"; ?^> > login.php
echo ^<?php echo "User Registration"; ?^> > register.php

:: Create configuration files
echo Creating Configuration...
echo ^<?php echo "Site Configuration"; ?^> > config.php

:: Create uploads directory for user uploads
mkdir uploads
mkdir uploads\products

:: Create documentation
echo Creating Documentation...
echo # Velvet Vogue E-commerce Website > README.md
echo. >> README.md
echo ## Project Structure >> README.md
echo This is a PHP-based e-commerce website for Velvet Vogue. >> README.md

echo.
echo ================================
echo Project Structure Created Successfully!
echo ================================
echo.
echo Directory: velvet-vogue\
echo.
echo Next Steps:
echo 1. Run the SQL database setup queries
echo 2. Configure database connection in includes\db_connect.php
echo 3. Set up your web server to point to this directory
echo 4. Access admin panel at: /admin/index.php
echo.
echo Project ready for development!
pause