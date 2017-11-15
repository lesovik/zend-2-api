<?php

use Phinx\Migration\AbstractMigration;

class ActionLog extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up() {
       
    
        $this->execute('
          CREATE SEQUENCE action_log_id_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
        ');

        $this->execute("
            CREATE TABLE action_log
                (
                  id integer NOT NULL DEFAULT nextval('action_log_id_seq'::regclass),
                  token_id integer NOT NULL,
                  route character varying(100),
                  method character varying(10),
                  created timestamp without time zone NOT NULL DEFAULT now(),
                  data text,
                  CONSTRAINT action_log_pkey PRIMARY KEY (id),
                  CONSTRAINT access_token_token_id_fkey FOREIGN KEY (token_id)
                      REFERENCES access_token (id) MATCH SIMPLE
                      ON UPDATE NO ACTION ON DELETE NO ACTION
                )
                WITH (
                  OIDS=FALSE
                );
        ");
       
        
    }

    /**
     * Migrate Down.
     */
    public function down() {
        $this->execute('DROP TABLE action_log;');
        $this->execute('DROP SEQUENCE action_log_id_seq;');
    }

}
