--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4 (Debian 17.4-1.pgdg120+2)
-- Dumped by pg_dump version 17.4

-- Started on 2025-05-27 13:04:56 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 6 (class 2615 OID 16385)
-- Name: truckstop; Type: SCHEMA; Schema: -; Owner: docker
--

CREATE SCHEMA truckstop;


ALTER SCHEMA truckstop OWNER TO docker;

--
-- TOC entry 243 (class 1255 OID 16386)
-- Name: fcn__avg_rating(integer); Type: FUNCTION; Schema: truckstop; Owner: docker
--

CREATE FUNCTION truckstop.fcn__avg_rating(p_place integer) RETURNS double precision
    LANGUAGE plpgsql
    AS $$
DECLARE
    avg_rating FLOAT;
BEGIN
    SELECT COALESCE(ROUND(AVG("rating") / 2.0, 1), 0) 
    INTO avg_rating
    FROM truckstop.tb_comment
    WHERE "ID_place" = p_place
	AND accepted = true;

    RETURN avg_rating;
END;
$$;


ALTER FUNCTION truckstop.fcn__avg_rating(p_place integer) OWNER TO docker;

--
-- TOC entry 244 (class 1255 OID 16387)
-- Name: fcn__getCommentByPlace(integer); Type: FUNCTION; Schema: truckstop; Owner: docker
--

CREATE FUNCTION truckstop."fcn__getCommentByPlace"(p_place integer) RETURNS TABLE("ID_comment" integer, "ID_place" integer, "dateAdd" timestamp with time zone, rating integer, text text, accepted boolean, name character varying, name_company character varying, name_place character varying)
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER FUNCTION truckstop."fcn__getCommentByPlace"(p_place integer) OWNER TO docker;

--
-- TOC entry 245 (class 1255 OID 16388)
-- Name: fcn__getRoles(uuid); Type: FUNCTION; Schema: truckstop; Owner: docker
--

CREATE FUNCTION truckstop."fcn__getRoles"(p_special_id uuid) RETURNS text[]
    LANGUAGE plpgsql
    AS $$
DECLARE
    roles TEXT[];
BEGIN
    SELECT ARRAY_AGG(r."name_role")
    INTO roles
	FROM "truckstop"."tb_user" u
	JOIN "truckstop"."rel_user_role" ur ON u."ID_user" = ur."ID_user"
	JOIN "truckstop"."tb_role" r ON ur."ID_role" = r."ID_role"
	WHERE u."ID_special" = p_special_id;

    RETURN roles;
END;
$$;


ALTER FUNCTION truckstop."fcn__getRoles"(p_special_id uuid) OWNER TO docker;

--
-- TOC entry 246 (class 1255 OID 16389)
-- Name: fcn__log(); Type: FUNCTION; Schema: truckstop; Owner: docker
--

CREATE FUNCTION truckstop.fcn__log() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO truckstop.tb_log (operation, new_data)
        VALUES ('INSERT', to_jsonb(NEW));
        RETURN NEW;

    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO truckstop.tb_log (operation, old_data, new_data)
        VALUES ('UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;

    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO truckstop.tb_log (operation, old_data)
        VALUES ('DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;

    RETURN NULL;
END;
$$;


ALTER FUNCTION truckstop.fcn__log() OWNER TO docker;

--
-- TOC entry 247 (class 1255 OID 16390)
-- Name: fcn__loginUser(character varying, character varying); Type: FUNCTION; Schema: truckstop; Owner: docker
--

CREATE FUNCTION truckstop."fcn__loginUser"(p_email character varying, p_password character varying) RETURNS uuid
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER FUNCTION truckstop."fcn__loginUser"(p_email character varying, p_password character varying) OWNER TO docker;

--
-- TOC entry 248 (class 1255 OID 16391)
-- Name: fcn__registerUser_wrapper(character varying, character varying, character varying); Type: FUNCTION; Schema: truckstop; Owner: docker
--

CREATE FUNCTION truckstop."fcn__registerUser_wrapper"(p_name character varying, p_email character varying, p_password character varying) RETURNS TABLE(uuid uuid, message text)
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER FUNCTION truckstop."fcn__registerUser_wrapper"(p_name character varying, p_email character varying, p_password character varying) OWNER TO docker;

--
-- TOC entry 260 (class 1255 OID 16392)
-- Name: prc__registerUser(character varying, character varying, character varying); Type: PROCEDURE; Schema: truckstop; Owner: docker
--

CREATE PROCEDURE truckstop."prc__registerUser"(IN p_name character varying, IN p_email character varying, IN p_password character varying, OUT p_user_uuid uuid)
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER PROCEDURE truckstop."prc__registerUser"(IN p_name character varying, IN p_email character varying, IN p_password character varying, OUT p_user_uuid uuid) OWNER TO docker;

--
-- TOC entry 261 (class 1255 OID 16393)
-- Name: prc__updateUserRoles(integer, json); Type: PROCEDURE; Schema: truckstop; Owner: docker
--

CREATE PROCEDURE truckstop."prc__updateUserRoles"(IN p_user_id integer, IN p_roles json)
    LANGUAGE plpgsql
    AS $$BEGIN
	DELETE FROM truckstop.rel_user_role
	WHERE "ID_user" = p_user_id;

	IF p_roles IS NOT NULL THEN
		INSERT INTO truckstop.rel_user_role ("ID_user", "ID_role")
		SELECT p_user_id, (role_id::INT)
		FROM json_array_elements_text(p_roles) AS role_id;
	END IF;

EXCEPTION
	WHEN OTHERS THEN
		ROLLBACK;
		RAISE;
END;
$$;


ALTER PROCEDURE truckstop."prc__updateUserRoles"(IN p_user_id integer, IN p_roles json) OWNER TO docker;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 16394)
-- Name: rel_place_additional; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.rel_place_additional (
    "ID_place" integer NOT NULL,
    "ID_additional" integer NOT NULL
);


ALTER TABLE truckstop.rel_place_additional OWNER TO docker;

--
-- TOC entry 219 (class 1259 OID 16397)
-- Name: rel_place_type; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.rel_place_type (
    "ID_place" integer NOT NULL,
    "ID_type" integer NOT NULL
);


ALTER TABLE truckstop.rel_place_type OWNER TO docker;

--
-- TOC entry 220 (class 1259 OID 16400)
-- Name: rel_user_role; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.rel_user_role (
    "ID_user" integer NOT NULL,
    "ID_role" integer NOT NULL
);


ALTER TABLE truckstop.rel_user_role OWNER TO docker;

--
-- TOC entry 221 (class 1259 OID 16403)
-- Name: tb_additional; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_additional (
    "ID_additional" integer NOT NULL,
    name_additional character varying(255) NOT NULL
);


ALTER TABLE truckstop.tb_additional OWNER TO docker;

--
-- TOC entry 222 (class 1259 OID 16406)
-- Name: tb_additional_ID_additional_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

ALTER TABLE truckstop.tb_additional ALTER COLUMN "ID_additional" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME truckstop."tb_additional_ID_additional_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000000
    CACHE 10
);


--
-- TOC entry 223 (class 1259 OID 16407)
-- Name: tb_comment; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_comment (
    "ID_comment" integer NOT NULL,
    "ID_user" integer NOT NULL,
    "ID_place" integer NOT NULL,
    "dateAdd" timestamp with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    rating integer NOT NULL,
    text text NOT NULL,
    accepted boolean DEFAULT false NOT NULL
);


ALTER TABLE truckstop.tb_comment OWNER TO docker;

--
-- TOC entry 224 (class 1259 OID 16414)
-- Name: tb_comment_ID_comment_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

ALTER TABLE truckstop.tb_comment ALTER COLUMN "ID_comment" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME truckstop."tb_comment_ID_comment_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000000
    CACHE 10
);


--
-- TOC entry 225 (class 1259 OID 16415)
-- Name: tb_company; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_company (
    "ID_company" integer NOT NULL,
    name_company character varying(255) NOT NULL
);


ALTER TABLE truckstop.tb_company OWNER TO docker;

--
-- TOC entry 226 (class 1259 OID 16418)
-- Name: tb_company_ID_company_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

ALTER TABLE truckstop.tb_company ALTER COLUMN "ID_company" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME truckstop."tb_company_ID_company_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000000
    CACHE 10
);


--
-- TOC entry 242 (class 1259 OID 16571)
-- Name: tb_favourite; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_favourite (
    "ID_user" integer NOT NULL,
    "ID_place" integer NOT NULL
);


ALTER TABLE truckstop.tb_favourite OWNER TO docker;

--
-- TOC entry 227 (class 1259 OID 16419)
-- Name: tb_log; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_log (
    id integer NOT NULL,
    operation character varying(10) NOT NULL,
    changed_at timestamp without time zone DEFAULT now() NOT NULL,
    old_data jsonb,
    new_data jsonb
);


ALTER TABLE truckstop.tb_log OWNER TO docker;

--
-- TOC entry 228 (class 1259 OID 16425)
-- Name: tb_log_id_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

CREATE SEQUENCE truckstop.tb_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE truckstop.tb_log_id_seq OWNER TO docker;

--
-- TOC entry 3524 (class 0 OID 0)
-- Dependencies: 228
-- Name: tb_log_id_seq; Type: SEQUENCE OWNED BY; Schema: truckstop; Owner: docker
--

ALTER SEQUENCE truckstop.tb_log_id_seq OWNED BY truckstop.tb_log.id;


--
-- TOC entry 229 (class 1259 OID 16426)
-- Name: tb_login; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_login (
    "ID_user" integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL
);


ALTER TABLE truckstop.tb_login OWNER TO docker;

--
-- TOC entry 230 (class 1259 OID 16431)
-- Name: tb_nationality; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_nationality (
    "ID_nationality" integer NOT NULL,
    name_nationality character varying(100) NOT NULL
);


ALTER TABLE truckstop.tb_nationality OWNER TO docker;

--
-- TOC entry 231 (class 1259 OID 16434)
-- Name: tb_place; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_place (
    "ID_place" integer NOT NULL,
    "ID_company" integer NOT NULL,
    name_place character varying(255) NOT NULL,
    address_place character varying(255) NOT NULL,
    longitude_place double precision,
    latitude_place double precision,
    image_place bytea
);


ALTER TABLE truckstop.tb_place OWNER TO docker;

--
-- TOC entry 232 (class 1259 OID 16439)
-- Name: tb_place_ID_place_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

ALTER TABLE truckstop.tb_place ALTER COLUMN "ID_place" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME truckstop."tb_place_ID_place_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000000
    CACHE 10
);


--
-- TOC entry 233 (class 1259 OID 16440)
-- Name: tb_role; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_role (
    "ID_role" integer NOT NULL,
    name_role character varying(255)
);


ALTER TABLE truckstop.tb_role OWNER TO docker;

--
-- TOC entry 234 (class 1259 OID 16443)
-- Name: tb_role_ID_role_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

ALTER TABLE truckstop.tb_role ALTER COLUMN "ID_role" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME truckstop."tb_role_ID_role_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000000
    CACHE 10
);


--
-- TOC entry 235 (class 1259 OID 16444)
-- Name: tb_type; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_type (
    "ID_type" integer NOT NULL,
    name_type character varying(255) NOT NULL
);


ALTER TABLE truckstop.tb_type OWNER TO docker;

--
-- TOC entry 236 (class 1259 OID 16447)
-- Name: tb_type_ID_type_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

ALTER TABLE truckstop.tb_type ALTER COLUMN "ID_type" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME truckstop."tb_type_ID_type_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000000
    CACHE 10
);


--
-- TOC entry 237 (class 1259 OID 16448)
-- Name: tb_user; Type: TABLE; Schema: truckstop; Owner: docker
--

CREATE TABLE truckstop.tb_user (
    "ID_user" integer NOT NULL,
    name character varying(255) NOT NULL,
    "ID_company" integer,
    "ID_special" uuid DEFAULT gen_random_uuid() NOT NULL,
    "ID_nationality" integer
);


ALTER TABLE truckstop.tb_user OWNER TO docker;

--
-- TOC entry 238 (class 1259 OID 16452)
-- Name: tb_user_ID_user_seq; Type: SEQUENCE; Schema: truckstop; Owner: docker
--

ALTER TABLE truckstop.tb_user ALTER COLUMN "ID_user" ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME truckstop."tb_user_ID_user_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000000
    CACHE 10
);


--
-- TOC entry 239 (class 1259 OID 16453)
-- Name: vw_comment; Type: VIEW; Schema: truckstop; Owner: docker
--

CREATE VIEW truckstop.vw_comment AS
 SELECT tb_comment."ID_comment" AS id,
    tb_comment."dateAdd" AS date,
    tb_comment.rating,
    tb_comment.text,
    tb_comment.accepted,
    tb_user.name AS "user",
    tb_company.name_company AS company,
    tb_place.name_place AS place,
    tb_place.address_place AS address
   FROM (((truckstop.tb_comment
     JOIN truckstop.tb_user ON ((tb_comment."ID_user" = tb_user."ID_user")))
     JOIN truckstop.tb_place ON ((tb_comment."ID_place" = tb_place."ID_place")))
     JOIN truckstop.tb_company ON ((tb_place."ID_company" = tb_company."ID_company")));


ALTER VIEW truckstop.vw_comment OWNER TO docker;

--
-- TOC entry 240 (class 1259 OID 16458)
-- Name: vw_place; Type: VIEW; Schema: truckstop; Owner: docker
--

CREATE VIEW truckstop.vw_place AS
 WITH type_agg AS (
         SELECT rpt."ID_place",
            string_agg(DISTINCT (tt.name_type)::text, ', '::text) AS types
           FROM (truckstop.rel_place_type rpt
             JOIN truckstop.tb_type tt ON ((rpt."ID_type" = tt."ID_type")))
          GROUP BY rpt."ID_place"
        ), additional_agg AS (
         SELECT rpa."ID_place",
            string_agg(DISTINCT (ta.name_additional)::text, ', '::text) AS placetags
           FROM (truckstop.rel_place_additional rpa
             JOIN truckstop.tb_additional ta ON ((rpa."ID_additional" = ta."ID_additional")))
          GROUP BY rpa."ID_place"
        )
 SELECT p."ID_place",
    p.name_place,
    p.address_place,
    p.longitude_place,
    p.latitude_place,
    p.image_place,
    c.name_company,
    t.types,
    a.placetags,
    truckstop.fcn__avg_rating(p."ID_place") AS fcn__avg_rating
   FROM (((truckstop.tb_place p
     JOIN truckstop.tb_company c ON ((p."ID_company" = c."ID_company")))
     LEFT JOIN type_agg t ON ((p."ID_place" = t."ID_place")))
     LEFT JOIN additional_agg a ON ((p."ID_place" = a."ID_place")));


ALTER VIEW truckstop.vw_place OWNER TO docker;

--
-- TOC entry 241 (class 1259 OID 16463)
-- Name: vw_user; Type: VIEW; Schema: truckstop; Owner: docker
--

CREATE VIEW truckstop.vw_user AS
 SELECT tb_user."ID_user",
    tb_user."ID_special",
    tb_user.name,
    tb_login.email,
    tb_company.name_company AS company,
    tb_nationality.name_nationality AS nationality,
    truckstop."fcn__getRoles"(tb_user."ID_special") AS roles
   FROM (((truckstop.tb_user
     JOIN truckstop.tb_login USING ("ID_user"))
     LEFT JOIN truckstop.tb_company ON ((tb_company."ID_company" = tb_user."ID_company")))
     LEFT JOIN truckstop.tb_nationality ON ((tb_nationality."ID_nationality" = tb_user."ID_nationality")));


ALTER VIEW truckstop.vw_user OWNER TO docker;

--
-- TOC entry 3292 (class 2604 OID 16467)
-- Name: tb_log id; Type: DEFAULT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_log ALTER COLUMN id SET DEFAULT nextval('truckstop.tb_log_id_seq'::regclass);


--
-- TOC entry 3497 (class 0 OID 16394)
-- Dependencies: 218
-- Data for Name: rel_place_additional; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.rel_place_additional VALUES (2, 4);
INSERT INTO truckstop.rel_place_additional VALUES (1, 9);
INSERT INTO truckstop.rel_place_additional VALUES (1, 8);
INSERT INTO truckstop.rel_place_additional VALUES (1, 7);
INSERT INTO truckstop.rel_place_additional VALUES (1, 6);
INSERT INTO truckstop.rel_place_additional VALUES (1, 1);
INSERT INTO truckstop.rel_place_additional VALUES (2, 11);
INSERT INTO truckstop.rel_place_additional VALUES (2, 8);
INSERT INTO truckstop.rel_place_additional VALUES (2, 2);
INSERT INTO truckstop.rel_place_additional VALUES (2, 1);


--
-- TOC entry 3498 (class 0 OID 16397)
-- Dependencies: 219
-- Data for Name: rel_place_type; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.rel_place_type VALUES (1, 8);
INSERT INTO truckstop.rel_place_type VALUES (1, 1);
INSERT INTO truckstop.rel_place_type VALUES (2, 2);
INSERT INTO truckstop.rel_place_type VALUES (2, 11);


--
-- TOC entry 3499 (class 0 OID 16400)
-- Dependencies: 220
-- Data for Name: rel_user_role; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.rel_user_role VALUES (71, 1);
INSERT INTO truckstop.rel_user_role VALUES (41, 1);
INSERT INTO truckstop.rel_user_role VALUES (41, 2);


--
-- TOC entry 3500 (class 0 OID 16403)
-- Dependencies: 221
-- Data for Name: tb_additional; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (1, 'Parking');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (2, 'Shop');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (3, 'Laundry');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (4, 'Fuel');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (5, 'Food');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (6, 'Shower');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (7, 'Rest Area');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (8, 'WC');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (9, 'Security');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (10, 'Charging');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (11, 'ATM');
INSERT INTO truckstop.tb_additional OVERRIDING SYSTEM VALUE VALUES (12, 'Repair');


--
-- TOC entry 3502 (class 0 OID 16407)
-- Dependencies: 223
-- Data for Name: tb_comment; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_comment OVERRIDING SYSTEM VALUE VALUES (1, 61, 2, '2025-03-27 21:56:08.58702+00', 7, 'Great facilities for drivers, but it can get crowded during peak hours.', false);
INSERT INTO truckstop.tb_comment OVERRIDING SYSTEM VALUE VALUES (4, 41, 1, '2025-03-27 21:56:08.58702+00', 8, 'Reliable service, fast loading and unloading times. A good place for quick business.', true);
INSERT INTO truckstop.tb_comment OVERRIDING SYSTEM VALUE VALUES (2, 81, 2, '2025-03-27 21:56:08.58702+00', 8, 'Perfect for a quick fuel stop on long hauls. Always well-maintained.', true);
INSERT INTO truckstop.tb_comment OVERRIDING SYSTEM VALUE VALUES (3, 41, 2, '2025-03-27 21:56:08.58702+00', 10, 'Friendly staff, easy access for trucks, but could use better parking for drivers.', true);


--
-- TOC entry 3504 (class 0 OID 16415)
-- Dependencies: 225
-- Data for Name: tb_company; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_company OVERRIDING SYSTEM VALUE VALUES (2, 'Orlen');
INSERT INTO truckstop.tb_company OVERRIDING SYSTEM VALUE VALUES (1, 'Saint-Gobain');
INSERT INTO truckstop.tb_company OVERRIDING SYSTEM VALUE VALUES (11, 'TransMar');


--
-- TOC entry 3518 (class 0 OID 16571)
-- Dependencies: 242
-- Data for Name: tb_favourite; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_favourite VALUES (41, 1);


--
-- TOC entry 3506 (class 0 OID 16419)
-- Dependencies: 227
-- Data for Name: tb_log; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_log VALUES (1, 'DELETE', '2025-04-10 22:55:40.195804', '{"name": "admin1", "ID_user": 101, "ID_company": null, "ID_special": "dadeedd4-5379-440a-b99b-480f50415e2e", "ID_nationality": null}', NULL);
INSERT INTO truckstop.tb_log VALUES (2, 'UPDATE', '2025-04-10 22:56:07.685125', '{"name": "user2", "ID_user": 81, "ID_company": null, "ID_special": "49c4f8ff-e663-49f5-8737-108f0b2d4ba6", "ID_nationality": null}', '{"name": "user232", "ID_user": 81, "ID_company": null, "ID_special": "49c4f8ff-e663-49f5-8737-108f0b2d4ba6", "ID_nationality": null}');


--
-- TOC entry 3508 (class 0 OID 16426)
-- Dependencies: 229
-- Data for Name: tb_login; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_login VALUES (41, 'admin@mail', '21232f297a57a5a743894a0e4a801fc3');
INSERT INTO truckstop.tb_login VALUES (61, 'user@mail', 'ee11cbb19052e40b07aac0ca060c23ee');
INSERT INTO truckstop.tb_login VALUES (71, 'moderator@mail', '0408f3c997f309c03b08bf3a4bc7b730');
INSERT INTO truckstop.tb_login VALUES (81, 'user2@mail', '7e58d63b60197ceb55a1c487989a3720');


--
-- TOC entry 3509 (class 0 OID 16431)
-- Dependencies: 230
-- Data for Name: tb_nationality; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_nationality VALUES (1, 'Poland');
INSERT INTO truckstop.tb_nationality VALUES (2, 'Germany');


--
-- TOC entry 3510 (class 0 OID 16434)
-- Dependencies: 231
-- Data for Name: tb_place; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_place OVERRIDING SYSTEM VALUE VALUES (1, 1, 'Adfors Polska', 'Biecka 11, 38-300 Gorlice', 21.168802386816964, 49.666897085010234, NULL);
INSERT INTO truckstop.tb_place OVERRIDING SYSTEM VALUE VALUES (2, 2, 'Stacja Orlen w Gorlicach', 'Węgierska 29A, 38-300 Gorlice', 21.16645043758691, 49.648803330763194, NULL);


--
-- TOC entry 3512 (class 0 OID 16440)
-- Dependencies: 233
-- Data for Name: tb_role; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_role OVERRIDING SYSTEM VALUE VALUES (1, 'moderator');
INSERT INTO truckstop.tb_role OVERRIDING SYSTEM VALUE VALUES (2, 'admin');


--
-- TOC entry 3514 (class 0 OID 16444)
-- Dependencies: 235
-- Data for Name: tb_type; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (1, 'Warehouse');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (2, 'Gas Station');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (3, 'Parking');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (4, 'Rest Area');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (5, 'Hotel/Motel');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (6, 'Customs Office');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (7, 'Service Point');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (8, 'Weigh Station');
INSERT INTO truckstop.tb_type OVERRIDING SYSTEM VALUE VALUES (11, 'Restaurant');


--
-- TOC entry 3516 (class 0 OID 16448)
-- Dependencies: 237
-- Data for Name: tb_user; Type: TABLE DATA; Schema: truckstop; Owner: docker
--

INSERT INTO truckstop.tb_user OVERRIDING SYSTEM VALUE VALUES (71, 'moderator', NULL, 'e774623c-36d3-435c-9f19-8a13fb5564a7', NULL);
INSERT INTO truckstop.tb_user OVERRIDING SYSTEM VALUE VALUES (41, 'admin', NULL, '7653d93e-100b-4d99-9880-935e96e7c1fa', 1);
INSERT INTO truckstop.tb_user OVERRIDING SYSTEM VALUE VALUES (61, 'user', 11, '9a4d2c10-ac75-4f77-af94-487165dc7703', 2);
INSERT INTO truckstop.tb_user OVERRIDING SYSTEM VALUE VALUES (81, 'user232', NULL, '49c4f8ff-e663-49f5-8737-108f0b2d4ba6', NULL);


--
-- TOC entry 3525 (class 0 OID 0)
-- Dependencies: 222
-- Name: tb_additional_ID_additional_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop."tb_additional_ID_additional_seq"', 20, true);


--
-- TOC entry 3526 (class 0 OID 0)
-- Dependencies: 224
-- Name: tb_comment_ID_comment_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop."tb_comment_ID_comment_seq"', 10, true);


--
-- TOC entry 3527 (class 0 OID 0)
-- Dependencies: 226
-- Name: tb_company_ID_company_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop."tb_company_ID_company_seq"', 20, true);


--
-- TOC entry 3528 (class 0 OID 0)
-- Dependencies: 228
-- Name: tb_log_id_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop.tb_log_id_seq', 2, true);


--
-- TOC entry 3529 (class 0 OID 0)
-- Dependencies: 232
-- Name: tb_place_ID_place_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop."tb_place_ID_place_seq"', 10, true);


--
-- TOC entry 3530 (class 0 OID 0)
-- Dependencies: 234
-- Name: tb_role_ID_role_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop."tb_role_ID_role_seq"', 10, true);


--
-- TOC entry 3531 (class 0 OID 0)
-- Dependencies: 236
-- Name: tb_type_ID_type_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop."tb_type_ID_type_seq"', 20, true);


--
-- TOC entry 3532 (class 0 OID 0)
-- Dependencies: 238
-- Name: tb_user_ID_user_seq; Type: SEQUENCE SET; Schema: truckstop; Owner: docker
--

SELECT pg_catalog.setval('truckstop."tb_user_ID_user_seq"', 120, true);


--
-- TOC entry 3296 (class 2606 OID 16469)
-- Name: rel_place_additional rel_place_additional_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_place_additional
    ADD CONSTRAINT rel_place_additional_pkey PRIMARY KEY ("ID_place", "ID_additional");


--
-- TOC entry 3298 (class 2606 OID 16471)
-- Name: rel_place_type rel_place_type_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_place_type
    ADD CONSTRAINT rel_place_type_pkey PRIMARY KEY ("ID_place", "ID_type");


--
-- TOC entry 3300 (class 2606 OID 16473)
-- Name: rel_user_role rel_user_role_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_user_role
    ADD CONSTRAINT rel_user_role_pkey PRIMARY KEY ("ID_user", "ID_role");


--
-- TOC entry 3303 (class 2606 OID 16475)
-- Name: tb_additional tb_additional_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_additional
    ADD CONSTRAINT tb_additional_pkey PRIMARY KEY ("ID_additional");


--
-- TOC entry 3306 (class 2606 OID 16477)
-- Name: tb_comment tb_comment_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_comment
    ADD CONSTRAINT tb_comment_pkey PRIMARY KEY ("ID_comment");


--
-- TOC entry 3309 (class 2606 OID 16479)
-- Name: tb_company tb_company_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_company
    ADD CONSTRAINT tb_company_pkey PRIMARY KEY ("ID_company");


--
-- TOC entry 3332 (class 2606 OID 16575)
-- Name: tb_favourite tb_favourite_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_favourite
    ADD CONSTRAINT tb_favourite_pkey PRIMARY KEY ("ID_user", "ID_place");


--
-- TOC entry 3311 (class 2606 OID 16481)
-- Name: tb_log tb_log_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_log
    ADD CONSTRAINT tb_log_pkey PRIMARY KEY (id);


--
-- TOC entry 3315 (class 2606 OID 16483)
-- Name: tb_login tb_login_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_login
    ADD CONSTRAINT tb_login_pkey PRIMARY KEY ("ID_user");


--
-- TOC entry 3317 (class 2606 OID 16485)
-- Name: tb_nationality tb_nationality_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_nationality
    ADD CONSTRAINT tb_nationality_pkey PRIMARY KEY ("ID_nationality");


--
-- TOC entry 3320 (class 2606 OID 16487)
-- Name: tb_place tb_place_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_place
    ADD CONSTRAINT tb_place_pkey PRIMARY KEY ("ID_place");


--
-- TOC entry 3323 (class 2606 OID 16489)
-- Name: tb_role tb_role_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_role
    ADD CONSTRAINT tb_role_pkey PRIMARY KEY ("ID_role");


--
-- TOC entry 3326 (class 2606 OID 16491)
-- Name: tb_type tb_type_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_type
    ADD CONSTRAINT tb_type_pkey PRIMARY KEY ("ID_type");


--
-- TOC entry 3330 (class 2606 OID 16493)
-- Name: tb_user tb_user_pkey; Type: CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_user
    ADD CONSTRAINT tb_user_pkey PRIMARY KEY ("ID_user");


--
-- TOC entry 3327 (class 1259 OID 16494)
-- Name: fki_tb_user_fk_company; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE INDEX fki_tb_user_fk_company ON truckstop.tb_user USING btree ("ID_company");


--
-- TOC entry 3328 (class 1259 OID 16495)
-- Name: fki_tb_user_fk_nationality; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE INDEX fki_tb_user_fk_nationality ON truckstop.tb_user USING btree ("ID_nationality");


--
-- TOC entry 3301 (class 1259 OID 16496)
-- Name: tb_additional__name; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_additional__name ON truckstop.tb_additional USING btree (name_additional);


--
-- TOC entry 3304 (class 1259 OID 16497)
-- Name: tb_comment__user_place; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_comment__user_place ON truckstop.tb_comment USING btree ("ID_user", "ID_place");


--
-- TOC entry 3307 (class 1259 OID 16498)
-- Name: tb_company__name_company; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_company__name_company ON truckstop.tb_company USING btree (name_company);


--
-- TOC entry 3312 (class 1259 OID 16499)
-- Name: tb_login__email; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_login__email ON truckstop.tb_login USING btree (email);


--
-- TOC entry 3313 (class 1259 OID 16500)
-- Name: tb_login__user; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_login__user ON truckstop.tb_login USING btree ("ID_user");


--
-- TOC entry 3318 (class 1259 OID 16501)
-- Name: tb_place__address; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_place__address ON truckstop.tb_place USING btree (address_place);


--
-- TOC entry 3321 (class 1259 OID 16502)
-- Name: tb_role__name; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_role__name ON truckstop.tb_role USING btree (name_role);


--
-- TOC entry 3324 (class 1259 OID 16503)
-- Name: tb_type__name_type; Type: INDEX; Schema: truckstop; Owner: docker
--

CREATE UNIQUE INDEX tb_type__name_type ON truckstop.tb_type USING btree (name_type);


--
-- TOC entry 3348 (class 2620 OID 16504)
-- Name: tb_user trg_tb_log1; Type: TRIGGER; Schema: truckstop; Owner: docker
--

CREATE TRIGGER trg_tb_log1 AFTER INSERT OR DELETE OR UPDATE ON truckstop.tb_user FOR EACH ROW EXECUTE FUNCTION truckstop.fcn__log();


--
-- TOC entry 3347 (class 2620 OID 16505)
-- Name: tb_place trg_tb_log2; Type: TRIGGER; Schema: truckstop; Owner: docker
--

CREATE TRIGGER trg_tb_log2 AFTER INSERT OR DELETE OR UPDATE ON truckstop.tb_place FOR EACH ROW EXECUTE FUNCTION truckstop.fcn__log();


--
-- TOC entry 3346 (class 2620 OID 16506)
-- Name: tb_comment trg_tb_log3; Type: TRIGGER; Schema: truckstop; Owner: docker
--

CREATE TRIGGER trg_tb_log3 AFTER INSERT OR DELETE OR UPDATE ON truckstop.tb_comment FOR EACH ROW EXECUTE FUNCTION truckstop.fcn__log();


--
-- TOC entry 3333 (class 2606 OID 16507)
-- Name: rel_place_additional rel_place_additional__additional; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_place_additional
    ADD CONSTRAINT rel_place_additional__additional FOREIGN KEY ("ID_additional") REFERENCES truckstop.tb_additional("ID_additional") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3334 (class 2606 OID 16512)
-- Name: rel_place_additional rel_place_additional__place; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_place_additional
    ADD CONSTRAINT rel_place_additional__place FOREIGN KEY ("ID_place") REFERENCES truckstop.tb_place("ID_place") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3335 (class 2606 OID 16517)
-- Name: rel_place_type rel_place_type__place; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_place_type
    ADD CONSTRAINT rel_place_type__place FOREIGN KEY ("ID_place") REFERENCES truckstop.tb_place("ID_place") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3336 (class 2606 OID 16522)
-- Name: rel_place_type rel_place_type__type; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_place_type
    ADD CONSTRAINT rel_place_type__type FOREIGN KEY ("ID_type") REFERENCES truckstop.tb_type("ID_type") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3337 (class 2606 OID 16527)
-- Name: rel_user_role rel_user_role__role; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_user_role
    ADD CONSTRAINT rel_user_role__role FOREIGN KEY ("ID_role") REFERENCES truckstop.tb_role("ID_role") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3338 (class 2606 OID 16532)
-- Name: rel_user_role rel_user_role__user; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.rel_user_role
    ADD CONSTRAINT rel_user_role__user FOREIGN KEY ("ID_user") REFERENCES truckstop.tb_user("ID_user") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3339 (class 2606 OID 16537)
-- Name: tb_comment tb_comment__place; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_comment
    ADD CONSTRAINT tb_comment__place FOREIGN KEY ("ID_place") REFERENCES truckstop.tb_place("ID_place") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3340 (class 2606 OID 16542)
-- Name: tb_comment tb_comment__user; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_comment
    ADD CONSTRAINT tb_comment__user FOREIGN KEY ("ID_user") REFERENCES truckstop.tb_user("ID_user") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3345 (class 2606 OID 16576)
-- Name: tb_favourite tb_favourite_fk_user; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_favourite
    ADD CONSTRAINT tb_favourite_fk_user FOREIGN KEY ("ID_user") REFERENCES truckstop.tb_user("ID_user") ON UPDATE CASCADE ON DELETE CASCADE NOT VALID;


--
-- TOC entry 3341 (class 2606 OID 16547)
-- Name: tb_login tb_login__user; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_login
    ADD CONSTRAINT tb_login__user FOREIGN KEY ("ID_user") REFERENCES truckstop.tb_user("ID_user") ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 3342 (class 2606 OID 16552)
-- Name: tb_place tb_place__company; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_place
    ADD CONSTRAINT tb_place__company FOREIGN KEY ("ID_company") REFERENCES truckstop.tb_company("ID_company") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3343 (class 2606 OID 16557)
-- Name: tb_user tb_user_fk_company; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_user
    ADD CONSTRAINT tb_user_fk_company FOREIGN KEY ("ID_company") REFERENCES truckstop.tb_company("ID_company") NOT VALID;


--
-- TOC entry 3344 (class 2606 OID 16562)
-- Name: tb_user tb_user_fk_nationality; Type: FK CONSTRAINT; Schema: truckstop; Owner: docker
--

ALTER TABLE ONLY truckstop.tb_user
    ADD CONSTRAINT tb_user_fk_nationality FOREIGN KEY ("ID_nationality") REFERENCES truckstop.tb_nationality("ID_nationality") NOT VALID;


-- Completed on 2025-05-27 13:04:56 UTC

--
-- PostgreSQL database dump complete
--

