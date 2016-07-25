-- Add column for sharing orders view option for libraries 
ALTER TABLE `libraries`  ADD `has_shared_ordres` TINYINT(1) NULL DEFAULT NULL ;