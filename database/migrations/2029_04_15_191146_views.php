<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::statement("
        create or replace  view `vlv_alert` as (
select
    `a`.`id` as `id`,
    `a`.`source_id` as `source_id`,
    `a`.`leader_id` as `leader_id`,
    `a`.`user_id` as `user_id`,
    `a`.`subject` as `subject`,
    `a`.`message` as `message`,
    `a`.`response` as `response`,
    `a`.`msg_cliente` as `msg_cliente`,
    `a`.`msg_mejora` as `msg_mejora`,
    `a`.`msg_fortaleza` as `msg_fortaleza`,
    `a`.`msg_acciones` as `msg_acciones`,
    `a`.`result` as `result`,
    `a`.`path_local` as `path_local`,
    `a`.`path_public` as `path_public`,
    `a`.`extension` as `extension`,
    `a`.`path2_public` as `path2_public`,
    `a`.`status` as `status`,
    `a`.`isactive` as `isactive`,
    `a`.`token` as `token`,
    `a`.`response_at` as `response_at`,
    `a`.`program` as `program`,
    `a`.`created_by` as `created_by`,
    `a`.`updated_by` as `updated_by`,
    `a`.`created_at` as `created_at`,
    `a`.`updated_at` as `updated_at`,
    `b`.`identity` as `origen`,
    `lea`.`lastname` as `supervidor`,
    `ase`.`lastname` as `asesor`
from
    (((`vl_alerts` `a`
join `vl_sources` `b` on
    (`b`.`id` = `a`.`source_id`))
left join `vl_users` `lea` on
    (`lea`.`id` = `a`.`leader_id`))
left join `vl_users` `ase` on
    (`ase`.`id` = `a`.`user_id`)));
        ");
        /*
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
        */
        DB::statement("
        create or replace  VIEW `vlv_buzon_logs` AS (
SELECT
  `a`.`id`         AS `id`,
  `a`.`user_id`    AS `user_id`,
  `a`.`host`       AS `host`,
  `a`.`token`      AS `token`,
  `a`.`droptime`   AS `droptime`,
  `a`.`created_by` AS `created_by`,
  `a`.`updated_by` AS `updated_by`,
  `a`.`created_at` AS `created_at`,
  `a`.`updated_at` AS `updated_at`,
  `b`.`email`      AS `email`,
  UCASE(`b`.`lastname`) AS `lastname`
FROM (`vl_buzon_logs` `a`
   JOIN `vl_users` `b`
     ON (`b`.`id` = `a`.`user_id`)));
        ");
/*
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
        */
        DB::statement("
        create or replace  VIEW `vlv_dimensionado` AS (
SELECT
  `a`.`id`          AS `id`,
  `a`.`name`        AS `name`,
  `a`.`documentno`  AS `documentno`,
  `a`.`lastname`    AS `lastname`,
  `le`.`name`       AS `leader_name`,
  `le`.`documentno` AS `leader_documentno`,
  `le`.`lastname`   AS `leader_lastname`,
  `a`.`program`     AS `program`,
  `a`.`age`         AS `age`
FROM (`vl_users` `a`
   LEFT JOIN `vl_users` `le`
     ON (`le`.`id` = `a`.`leader_id`))
WHERE `a`.`id` >= 4);
        ");
        /*
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
        */
        DB::statement("
        create or replace  VIEW `vlv_reason` AS 
SELECT
  `a`.`identity`  AS `subreasonname`,
  `a`.`reason_id` AS `reason_id`,
  `a`.`id`        AS `subreason_id`,
  `b`.`identity`  AS `reasonname`,
  CONCAT(`b`.`identity`,' - ',`a`.`identity`) AS `motivo`
FROM (`vl_sub_reasons` `a`
   JOIN `vl_reasons` `b`
     ON (`a`.`reason_id` = `b`.`id`));
        ");
        /*
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
            -----------------------------------------------------------------------------------------------------------------
        */
        DB::statement("
        create or replace  VIEW `vlv_reporte` AS (
SELECT
  `pal`.`id`            AS `id`,
  `pal`.`datetrx`       AS `datetrx`,
  `pal`.`nodo`          AS `nodo`,
  `pal`.`documentno`    AS `documentno`,
  `pal`.`did`           AS `did`,
  `pal`.`comment`       AS `comment`,
  `pal`.`program`       AS `program`,
  `pal`.`month`         AS `month`,
  `pal`.`isincidencia`  AS `isincidencia`,
  `pal`.`incidencia_id` AS `incidencia_id`,
  `pal`.`subreason_id`  AS `subreason_id`,
  `pal`.`isactive`      AS `isactive`,
  `pal`.`token`         AS `token`,
  `pal`.`created_by`    AS `created_by`,
  `pal`.`updated_by`    AS `updated_by`,
  `pal`.`created_at`    AS `created_at`,
  `pal`.`updated_at`    AS `updated_at`,
  `sr`.`identity`       AS `subreason`,
  `r`.`identity`        AS `reason`,
  `u`.`name`            AS `name`,
  `u`.`lastname`        AS `lastname`,
  `u`.`documentno`      AS `documentno2`
FROM (((`vl_paloteos` `pal`
     JOIN `vl_sub_reasons` `sr`
       ON (`pal`.`subreason_id` = `sr`.`id`))
    JOIN `vl_reasons` `r`
      ON (`sr`.`reason_id` = `r`.`id`))
   JOIN `vl_users` `u`
     ON (`pal`.`created_by` = `u`.`id`)));
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::statement('DROP VIEW IF EXISTS vlv_alert');
    }
};
