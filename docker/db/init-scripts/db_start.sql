CREATE SCHEMA "truckstop";

CREATE TABLE "truckstop"."rel_place_additional" (
  "ID_place" int4 NOT NULL,
  "ID_additional" int4 NOT NULL,
  PRIMARY KEY ("ID_place", "ID_additional")
);

CREATE TABLE "truckstop"."rel_place_type" (
  "ID_place" int4 NOT NULL,
  "ID_type" int4 NOT NULL,
  PRIMARY KEY ("ID_place", "ID_type")
);

CREATE TABLE "truckstop"."rel_user_role" (
  "ID_user" int4 NOT NULL,
  "ID_role" int4 NOT NULL,
  PRIMARY KEY ("ID_user", "ID_role")
);

CREATE TABLE "truckstop"."tb_additional" (
  "ID_additional" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 1000000000
START 1
CACHE 10
),
  "name_additional" varchar(255) NOT NULL,
  PRIMARY KEY ("ID_additional")
);
CREATE UNIQUE INDEX "tb_additional__name" ON "truckstop"."tb_additional" USING btree (
  "name_additional"
);

CREATE TABLE "truckstop"."tb_comment" (
  "ID_comment" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 1000000000
START 1
CACHE 10
),
  "ID_user" int4 NOT NULL,
  "ID_place" int4 NOT NULL,
  "dateAdd" timestamptz NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "rating" int4 NOT NULL,
  "text" text NOT NULL,
  "accepted" bool NOT NULL DEFAULT false,
  PRIMARY KEY ("ID_comment")
);
CREATE UNIQUE INDEX "tb_comment__user_place" ON "truckstop"."tb_comment" (
  "ID_user", "ID_place"
);

CREATE TABLE "truckstop"."tb_company" (
  "ID_company" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 1000000000
START 1
CACHE 10
),
  "name_company" varchar(255) NOT NULL,
  PRIMARY KEY ("ID_company")
);
CREATE UNIQUE INDEX "tb_company__name_company" ON "truckstop"."tb_company" USING btree (
  "name_company"
);

CREATE TABLE "truckstop"."tb_login" (
  "ID_user" int4 NOT NULL,
  "email" varchar(255) NOT NULL,
  "password" varchar(255) NOT NULL,
  PRIMARY KEY ("ID_user")
);
CREATE UNIQUE INDEX "tb_login__email" ON "truckstop"."tb_login" USING btree (
  "email"
);
CREATE UNIQUE INDEX "tb_login__user" ON "truckstop"."tb_login" USING btree (
  "ID_user"
);

CREATE TABLE "truckstop"."tb_place" (
  "ID_place" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 1000000000
START 1
CACHE 10
),
  "ID_company" int4 NOT NULL,
  "name_place" varchar(255) NOT NULL,
  "address_place" varchar(255) NOT NULL,
  "longitude_place" float8,
  "latitude_place" float8,
  "image_place" bytea,
  PRIMARY KEY ("ID_place")
);
CREATE UNIQUE INDEX "tb_place__address" ON "truckstop"."tb_place" USING btree (
  "address_place"
);

CREATE TABLE "truckstop"."tb_role" (
  "ID_role" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 1000000000
START 1
CACHE 10
),
  "name_role" varchar(255),
  PRIMARY KEY ("ID_role")
);
CREATE UNIQUE INDEX "tb_role__name" ON "truckstop"."tb_role" USING btree (
  "name_role"
);

CREATE TABLE "truckstop"."tb_type" (
  "ID_type" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 1000000000
START 1
CACHE 10
),
  "name_type" varchar(255) NOT NULL,
  PRIMARY KEY ("ID_type")
);
CREATE UNIQUE INDEX "tb_type__name_type" ON "truckstop"."tb_type" USING btree (
  "name_type"
);

CREATE TABLE "truckstop"."tb_user" (
  "ID_user" int4 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
MINVALUE  1
MAXVALUE 1000000000
START 1
CACHE 10
),
  "name" varchar(255) NOT NULL,
  "ID_comapny" int4,
  "ID_special" uuid NOT NULL DEFAULT gen_random_uuid(),
  PRIMARY KEY ("ID_user")
);

ALTER TABLE "truckstop"."rel_place_additional" ADD CONSTRAINT "rel_place_additional__place" FOREIGN KEY ("ID_place") REFERENCES "truckstop"."tb_place" ("ID_place") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."rel_place_additional" ADD CONSTRAINT "rel_place_additional__additional" FOREIGN KEY ("ID_additional") REFERENCES "truckstop"."tb_additional" ("ID_additional") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."rel_place_type" ADD CONSTRAINT "rel_place_type__place" FOREIGN KEY ("ID_place") REFERENCES "truckstop"."tb_place" ("ID_place") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."rel_place_type" ADD CONSTRAINT "rel_place_type__type" FOREIGN KEY ("ID_type") REFERENCES "truckstop"."tb_type" ("ID_type") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."rel_user_role" ADD CONSTRAINT "rel_user_role__user" FOREIGN KEY ("ID_user") REFERENCES "truckstop"."tb_user" ("ID_user") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."rel_user_role" ADD CONSTRAINT "rel_user_role__role" FOREIGN KEY ("ID_role") REFERENCES "truckstop"."tb_role" ("ID_role") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."tb_comment" ADD CONSTRAINT "tb_comment__place" FOREIGN KEY ("ID_place") REFERENCES "truckstop"."tb_place" ("ID_place") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."tb_comment" ADD CONSTRAINT "tb_comment__user" FOREIGN KEY ("ID_user") REFERENCES "truckstop"."tb_user" ("ID_user") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."tb_login" ADD CONSTRAINT "tb_login__user" FOREIGN KEY ("ID_user") REFERENCES "truckstop"."tb_user" ("ID_user") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "truckstop"."tb_place" ADD CONSTRAINT "tb_place__company" FOREIGN KEY ("ID_company") REFERENCES "truckstop"."tb_company" ("ID_company") ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE OR REPLACE FUNCTION truckstop.fcn__avg_rating (IN p_place INT4)
RETURNS FLOAT AS
$$
DECLARE
    avg_rating FLOAT;
BEGIN
    SELECT COALESCE(ROUND(AVG("rating") / 2.0, 1), 0) 
    INTO avg_rating
    FROM truckstop.tb_comment
    WHERE "ID_place" = p_place;

    RETURN avg_rating;
END;
$$
LANGUAGE plpgsql;;

CREATE OR REPLACE FUNCTION truckstop."fcn__getCommentByPlace"(p_place INT4)
RETURNS TABLE (
    "ID_comment" INT,
    "ID_place" INT,
    "dateAdd" TIMESTAMP WITH TIME ZONE, 
    "rating" INT,
    "text" TEXT,
    "accepted" BOOLEAN,
    "name" VARCHAR(255),
    "name_company" VARCHAR(255),
    "name_place" VARCHAR(255)
) AS $$
BEGIN
  RETURN QUERY 
  SELECT 
    vw."ID_comment",
    vw."ID_place",
    vw."dateAdd",
    vw."rating",
    vw."text",
    vw."accepted",
    vw."name",
    vw."name_company",
    vw."name_place"
  FROM vw_comment vw 
  WHERE vw."ID_place" = p_place;
END;
$$ LANGUAGE plpgsql;;

CREATE OR REPLACE FUNCTION truckstop."fcn__loginUser" (
  IN p_email VARCHAR(255),
  IN p_password VARCHAR(255)
)
RETURNS UUID
LANGUAGE plpgsql
AS $BODY$
DECLARE
  v_user_id INT;
  v_user_uuid UUID;
BEGIN
  -- Check if user exists
  SELECT "ID_user" INTO v_user_id
  FROM truckstop."tb_login"
  WHERE "email" = p_email
    AND "password" = md5(p_password);

  IF NOT FOUND THEN
    RAISE EXCEPTION 'Login failed: incorrect email or password.';
  END IF;

  -- Select UUID
  SELECT "ID_special" INTO v_user_uuid
  FROM truckstop."tb_user"
  WHERE "ID_user" = v_user_id;

  RETURN v_user_uuid;
END;
$BODY$;;

CREATE OR REPLACE FUNCTION truckstop."fcn_registerUser_wrapper"(
  p_name VARCHAR(255),
  p_email VARCHAR(255),
  p_password VARCHAR(255)
)
RETURNS TABLE(uuid UUID, message TEXT)
LANGUAGE plpgsql
AS $BODY$
DECLARE
  v_user_uuid UUID := NULL;
BEGIN
  BEGIN
    CALL truckstop."prc__registerUser"(p_name, p_email, p_password, v_user_uuid);
    RETURN QUERY SELECT v_user_uuid, NULL;
  EXCEPTION
    WHEN OTHERS THEN
      RETURN QUERY SELECT NULL::UUID, SQLERRM;
  END;
END;
$BODY$;;

CREATE OR REPLACE PROCEDURE truckstop."prc__registerUser" (
  IN p_name VARCHAR(255),
  IN p_email VARCHAR(255),
  IN p_password VARCHAR(255),
  OUT p_user_uuid UUID
)
LANGUAGE plpgsql
AS $BODY$
DECLARE
  v_existing_email INT;
  v_existing_name INT;
  v_user_id INT;
BEGIN
  p_user_uuid := NULL;

  -- Email check
  SELECT COUNT(*) INTO v_existing_email
  FROM truckstop."tb_login"
  WHERE "email" = p_email;

  IF v_existing_email > 0 THEN
    RAISE EXCEPTION 'Email already in use';
  END IF;

  -- Name check
  SELECT COUNT(*) INTO v_existing_name
  FROM truckstop."tb_user"
  WHERE "name" = p_name;

  IF v_existing_name > 0 THEN
    RAISE EXCEPTION 'Name already in use';
  END IF;

  -- Transaction
  BEGIN
    p_user_uuid := gen_random_uuid();

    INSERT INTO truckstop."tb_user" ("name", "ID_special")
    VALUES (p_name, p_user_uuid)
    RETURNING "ID_user" INTO v_user_id;

    INSERT INTO truckstop."tb_login" ("ID_user", "email", "password")
    VALUES (v_user_id, p_email, md5(p_password));

  EXCEPTION
    WHEN OTHERS THEN
      RAISE EXCEPTION 'Insert failed: %', SQLERRM;
  END;
END;
$BODY$;;



CREATE VIEW "truckstop"."vw_comment" AS SELECT
	tb_comment."ID_comment",  
	tb_comment."ID_place",
	tb_comment."dateAdd", 
	tb_comment.rating, 
	tb_comment."text", 
	tb_comment.accepted, 
	tb_user."name", 
	tb_company.name_company, 
	tb_place.name_place
FROM
	"truckstop".tb_comment
	INNER JOIN
	"truckstop".tb_user
	ON 
		"truckstop".tb_comment."ID_user" = "truckstop".tb_user."ID_user"
	INNER JOIN
	"truckstop".tb_place
	ON 
		"truckstop".tb_comment."ID_place" = "truckstop".tb_place."ID_place"
	INNER JOIN
	"truckstop".tb_company
	ON 
		"truckstop".tb_place."ID_company" = "truckstop".tb_company."ID_company";

CREATE VIEW "truckstop"."vw_place" AS SELECT
	tb_place."ID_place", 
	tb_place.name_place, 
	tb_place.address_place, 
	tb_place.longitude_place, 
	tb_place.latitude_place, 
	tb_place.image_place, 
	tb_company.name_company, 
	STRING_AGG(tb_type.name_type, ', ') AS types,
  STRING_AGG(tb_additional.name_additional, ', ') AS placeTags,
  truckstop.fcn__avg_rating(tb_place."ID_place") AS fcn__avg_rating
FROM
	"truckstop".tb_place
	INNER JOIN
	"truckstop".tb_company
	ON 
		"truckstop".tb_place."ID_company" = "truckstop".tb_company."ID_company"
	LEFT JOIN
	"truckstop".rel_place_type
	ON 
		"truckstop".tb_place."ID_place" = "truckstop".rel_place_type."ID_place"
	LEFT JOIN
	"truckstop".tb_type
	ON 
		"truckstop".rel_place_type."ID_type" = "truckstop".tb_type."ID_type"
  LEFT JOIN
	"truckstop".rel_place_additional
	ON 
		"truckstop".tb_place."ID_place" = "truckstop".rel_place_additional."ID_place"
	LEFT JOIN
	"truckstop".tb_additional
	ON 
		"truckstop".rel_place_additional."ID_additional" = "truckstop".tb_additional."ID_additional"
	LEFT JOIN
	"truckstop".tb_comment
	ON 
		"truckstop".tb_place."ID_place" = "truckstop".tb_comment."ID_place"
GROUP BY
	"truckstop".tb_place."ID_place", 
	"truckstop".tb_place.name_place, 
	"truckstop".tb_place.address_place, 
	"truckstop".tb_place.longitude_place, 
	"truckstop".tb_place.latitude_place, 
	"truckstop".tb_place.image_place, 
	"truckstop".tb_company.name_company;;