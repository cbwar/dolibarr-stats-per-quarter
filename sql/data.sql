DELETE FROM llx_const WHERE name = 'CBWARQUARTERSTATS_CHARGES_PCT' AND entity = '__ENTITY__';
INSERT INTO llx_const (name, value, type, note, visible, entity) VALUES ('CBWARQUARTERSTATS_CHARGES_PCT', '22.1', 'chaine', NULL, 0, '__ENTITY__');
DELETE FROM llx_const WHERE name = 'CBWARQUARTERSTATS_ABTMT_PCT' AND entity = '__ENTITY__';
INSERT INTO llx_const (name, value, type, note, visible, entity) VALUES ('CBWARQUARTERSTATS_ABTMT_PCT', '34.7', 'chaine', NULL, 0, '__ENTITY__');