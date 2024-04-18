DO $$ BEGIN
CREATE ROLE main WITH LOGIN PASSWORD 'main';
EXCEPTION WHEN DUPLICATE_OBJECT THEN
-- Do nothing, role already exists
END $$;

DO $$ BEGIN
CREATE DATABASE main WITH OWNER = main;
EXCEPTION WHEN DUPLICATE_DATABASE THEN
-- Do nothing, database already exists
END $$;

ALTER SYSTEM SET listen_addresses = '*';
SELECT pg_reload_conf();