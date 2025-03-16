CREATE TABLE "rel_place_attribute" (
  "ID_place" int4 NOT NULL,
  "ID_attribute" int4 NOT NULL,
  PRIMARY KEY ("ID_place", "ID_attribute")
);

CREATE TABLE "tb_attribute" (
  "ID_attribute" int4 NOT NULL,
  "name" varchar(100),
  PRIMARY KEY ("ID_attribute")
);

CREATE TABLE "tb_comment" (

);

CREATE TABLE "tb_place" (
  "ID_place" int8 NOT NULL GENERATED ALWAYS AS IDENTITY (
INCREMENT 1
START 1
),
  "name" varchar(100),
  "ID_type" int4,
  "ID_company" int4,
  "description" text DEFAULT '',
  "city" varchar(100),
  "street" varchar(100),
  "zip_code" varchar(100),
  "location" geography DEFAULT NULL,
  "image" bytea,
  "active" bool,
  CONSTRAINT "tb_place_pkey" PRIMARY KEY ("ID_place")
);
ALTER TABLE "tb_place" OWNER TO "docker";
CREATE INDEX "type" ON "tb_place" (
  "ID_type"
);
CREATE INDEX "company" ON "tb_place" (
  "ID_company"
);
CREATE UNIQUE INDEX "name" ON "tb_place" (
  "name"
);
CREATE UNIQUE INDEX "address" ON "tb_place" (
  city, "street",
  "zip_code"
);
CREATE UNIQUE INDEX "location" ON "tb_place" (
  "location"
);

ALTER TABLE "rel_place_attribute" ADD CONSTRAINT "place" FOREIGN KEY ("ID_place") REFERENCES "tb_place" ("ID_place") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "rel_place_attribute" ADD CONSTRAINT "attribute" FOREIGN KEY ("ID_attribute") REFERENCES "tb_attribute" ("ID_attribute") ON DELETE NO ACTION ON UPDATE NO ACTION;

