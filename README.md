# Wordpress_Plugin---Custom-Admin-API
# Custom Admin & API Plugin

This WordPress plugin provides the following custom features:

## Features

1. **Custom Login Page Styling**
   - Adds a modern, clean design to the WordPress login screen using Tailwind-inspired styles and Google Fonts.

2. **Dashboard Redirect Button**
   - Adds a custom meta box to the admin dashboard with a button that redirects to a specific admin page (`/xyz`).

3. **Custom API Endpoints**
   - **REST API:** `GET /wp-json/custom/v1/users/{id}` → Returns a fixed user ID.
   - **AJAX Endpoint:** Sends a POST request with an `id` and receives a fixed user ID in response.

## Installation

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin via the **Plugins** menu in WordPress.

## Author

Created by **Amratanshu**
