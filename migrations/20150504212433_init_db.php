<?php

use Phinx\Migration\AbstractMigration;

class InitDb extends AbstractMigration {
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
      public function change()
      {
      }
     */

    /**
     * Migrate Up.
     */
    public function up() {
        $this->execute("
          CREATE TYPE access_class AS ENUM
            ('DELETED',
             'SUSPENDED',
             'ACTIVE');
        ");
        $this->execute("
          CREATE TYPE user_class AS ENUM
            ('CUSTOM',
             'INTERN',
             'MANAGER',
             'ADMIN',
             'ROOT');
        ");
        $this->execute('
          CREATE OR REPLACE FUNCTION update_change_last_modified()
            RETURNS trigger AS
          $BODY$
          BEGIN
              NEW.last_modified = now(); 
              RETURN NEW;
          END;
          $BODY$
            LANGUAGE plpgsql VOLATILE
            COST 100;
        ');
        $this->execute('
          CREATE SEQUENCE login_id_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
        ');
        $this->execute('
          CREATE SEQUENCE access_token_id_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
        ');
        $this->execute("
          CREATE TABLE login
            (
              id integer NOT NULL DEFAULT nextval('login_id_seq'::regclass),
              username character varying(20) NOT NULL,
              password character varying(100) NOT NULL,
              user_role user_class NOT NULL DEFAULT 'INTERN'::user_class,
              created timestamp without time zone NOT NULL DEFAULT now(),
              login_status access_class NOT NULL DEFAULT 'ACTIVE'::access_class,
              email character varying(100) NOT NULL,
              password_reset_token character varying(10),
              last_modified timestamp without time zone NOT NULL DEFAULT now(),
              first_name character varying(256),
              last_name character varying(256),
              avatar_url character varying(1056),
              CONSTRAINT login_pkey PRIMARY KEY (id),
              CONSTRAINT login_username_key UNIQUE (username)
            )
            WITH (
              OIDS=FALSE
            );
        ");
        $this->execute("
            CREATE TRIGGER update_login_last_modified
                BEFORE UPDATE
                ON login
                FOR EACH ROW
                EXECUTE PROCEDURE update_change_last_modified();
        ");
        $this->execute("
            CREATE TABLE access_token
                (
                  id integer NOT NULL DEFAULT nextval('access_token_id_seq'::regclass),
                  login_id integer NOT NULL,
                  token character varying(25),
                  ip character varying(25),
                  expires timestamp without time zone NOT NULL,
                  CONSTRAINT access_token_pkey PRIMARY KEY (id),
                  CONSTRAINT access_token_login_id_fkey FOREIGN KEY (login_id)
                      REFERENCES login (id) MATCH SIMPLE
                      ON UPDATE NO ACTION ON DELETE CASCADE
                )
                WITH (
                  OIDS=FALSE
                );
        ");
        $this->execute("
           INSERT INTO login(
                username, 
                password, 
                user_role, 
                login_status, 
                email,    
                first_name, 
                last_name)
           VALUES (
                'testuser',
               'sha256:1000:lnN9Vwvy1WSCi2s+lOmWogZwdSHo6HX8:7lCFq+VXv+G9VyEmPySOqPxXki7VLqH1',
                'ROOT',
                'ACTIVE',
                'dlesov@gmail.com',
                'Test',
                'User'
               );
        ");
        
    }

    /**
     * Migrate Down.
     */
    public function down() {
        $this->execute('DROP TABLE access_token;');
        $this->execute('DROP TRIGGER update_login_last_modified ON login;');
        $this->execute('DROP TABLE login;');
        $this->execute('DROP SEQUENCE access_token_id_seq;');
        $this->execute('DROP SEQUENCE login_id_seq;');
        $this->execute('DROP FUNCTION update_change_last_modified();');
        $this->execute('DROP TYPE user_class');
        $this->execute('DROP TYPE access_class');
    }

}
