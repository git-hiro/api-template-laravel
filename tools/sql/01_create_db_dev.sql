CREATE ROLE api_laravel_role WITH LOGIN PASSWORD 'api_laravel_pass';
CREATE DATABASE api_laravel_db WITH OWNER api_laravel_role;
