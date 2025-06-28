-- Create default user
-- DO
-- $$
-- BEGIN
--    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'notes') THEN
--       CREATE ROLE "notes" LOGIN PASSWORD 'notes';
--    END IF;
-- END
-- $$;
CREATE ROLE "notes" WITH LOGIN PASSWORD 'notes';

-- Create databases
CREATE DATABASE "notes" WITH OWNER "notes";
CREATE DATABASE "notes_test" WITH OWNER "notes";

-- Grant privileges on databases
GRANT ALL PRIVILEGES ON DATABASE "notes" TO "notes";
GRANT ALL PRIVILEGES ON DATABASE "notes_test" TO "notes";

--------------------------------------------------------------------------------------------

-- Connect to the database to apply schema permissions
\connect notes

-- Grant privileges on the public schema
GRANT ALL ON SCHEMA public TO notes;

-- Grant privileges to create tables and sequences
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO notes;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO notes;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO notes;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO notes;

--------------------------------------------------------------------------------------------

-- Connect to the database to apply schema permissions
\connect notes_test

-- Grant privileges on the public schema
GRANT ALL ON SCHEMA public TO notes;

-- Grant privileges to create tables and sequences
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO notes;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO notes;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO notes;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO notes;

--------------------------------------------------------------------------------------------

-- Grant SELECT on information_schema (optional, rarely needed)
GRANT CONNECT ON DATABASE "notes" TO "notes";
GRANT CONNECT ON DATABASE "notes_test" TO "notes";

-- Set session time zone to UTC
ALTER ROLE "notes" SET timezone = 'UTC';

